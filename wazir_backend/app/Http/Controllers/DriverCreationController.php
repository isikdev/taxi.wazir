<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Services\DriverImageService;

class DriverCreationController extends Controller
{
    /**
     * Сервис для работы с изображениями водителей
     */
    protected $imageService;

    /**
     * Конструктор класса
     */
    public function __construct(DriverImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Получает общий баланс всех водителей
     * 
     * @return float
     */
    private function getTotalBalance()
    {
        // Используем кеширование для оптимизации
        return Cache::remember('total_balance', 60, function () {
            return Driver::sum('balance');
        });
    }

    /**
     * Шаг 1: Персональные данные + загрузка ID/ВУ
     */
    public function showStep1()
    {
        $totalBalance = $this->getTotalBalance();
        $drivers = Driver::all();
        return view('disp.drivers_control_edit', compact('totalBalance', 'drivers'));
    }

    public function processStep1(Request $request)
    {
        // Кастомные сообщения на русском
        $messages = [
            'full_name.required'           => 'Поле «Ф.И.О.» обязательно для заполнения.',
            'full_name.max'                => 'Поле «Ф.И.О.» не может превышать :max символов.',
            
            'date_of_birth.required'       => 'Поле «Дата рождения» обязательно для заполнения.',
            'date_of_birth.date_format'    => 'Дата рождения должна быть в формате дд.мм.гггг.',
            'license_issue_date.required'  => 'Поле «Дата выдачи» обязательно для заполнения.',
            'license_issue_date.date_format' => 'Дата выдачи должна быть в формате дд.мм.гггг.',
            'license_expiry_date.required' => 'Поле «Срок действия» обязательно для заполнения.',
            'license_expiry_date.date_format' => 'Срок действия должно быть в формате дд.мм.гггг.',

            'passport_front.file'         => 'Файл «Паспорт (лицевая сторона)» должен быть корректным.',
            'passport_front.mimes'        => 'Разрешённые форматы: jpg, jpeg, png.',
            'passport_front.max'          => 'Максимальный размер файла 2 МБ.',
            
            'passport_back.file'          => 'Файл «Паспорт (задняя сторона)» должен быть корректным.',
            'passport_back.mimes'         => 'Разрешённые форматы: jpg, jpeg, png.',
            'passport_back.max'           => 'Максимальный размер файла 2 МБ.',
            
            'license_front.file'          => 'Файл «ВУ (лицевая сторона)» должен быть корректным.',
            'license_front.mimes'         => 'Разрешённые форматы: jpg, jpeg, png.',
            'license_front.max'           => 'Максимальный размер файла 2 МБ.',
            
            'license_back.file'           => 'Файл «ВУ (задняя сторона)» должен быть корректным.',
            'license_back.mimes'          => 'Разрешённые форматы: jpg, jpeg, png.',
            'license_back.max'            => 'Максимальный размер файла 2 МБ.',
        ];

        $validated = $request->validate([
            // Основные поля
            'full_name'          => 'required|string|max:255',
            'phone'              => 'nullable|string|max:50',
            'city'               => 'nullable|string|max:100',
            'status'             => 'nullable|string|max:50', // если нужно

            // Даты
            'date_of_birth'      => 'required|date_format:d.m.Y',
            'license_number'     => 'nullable|string|max:50',
            'license_issue_date' => 'required|date_format:d.m.Y',
            'license_expiry_date'=> 'required|date_format:d.m.Y',

            // Файлы (необязательные)
            'passport_front'     => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'passport_back'      => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'license_front'      => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'license_back'       => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ], $messages);

        // Генерируем персональный номер
        $validated['personal_number'] = Str::random(17);

        // Обработка телефонного номера в киргизском формате
        if (!empty($validated['phone'])) {
            // Нормализуем номер телефона, удаляем все нецифровые символы кроме плюса
            $phone = preg_replace('/[^0-9+]/', '', $validated['phone']);
            
            // Добавляем киргизский код страны если его нет
            if (!str_starts_with($phone, '+996')) {
                // Если номер начинается с "0", убираем его
                if (str_starts_with($phone, '0')) {
                    $phone = substr($phone, 1);
                }
                
                // Если начинается с "996", добавляем "+"
                if (str_starts_with($phone, '996')) {
                    $phone = '+' . $phone;
                } 
                // Если нет кода, но есть остальные цифры, добавляем код +996
                else if (!str_starts_with($phone, '+')) {
                    $phone = '+996' . $phone;
                }
            }
            
            $validated['phone'] = $phone;
        }

        // Конвертируем даты в формат Y-m-d
        $validated['date_of_birth']       = Carbon::createFromFormat('d.m.Y', $validated['date_of_birth'])->format('Y-m-d');
        $validated['license_issue_date']  = Carbon::createFromFormat('d.m.Y', $validated['license_issue_date'])->format('Y-m-d');
        $validated['license_expiry_date'] = Carbon::createFromFormat('d.m.Y', $validated['license_expiry_date'])->format('Y-m-d');
        
        $validated['is_confirmed'] = true;
        $validated['survey_status'] = 'approved';
        $validated['approved_at'] = now();
        
        // Загрузка файлов (если есть)
        if ($request->hasFile('passport_front')) {
            $validated['passport_front'] = $request->file('passport_front')
                ->store("drivers/{$validated['personal_number']}/passport", 'public');
        }
        if ($request->hasFile('passport_back')) {
            $validated['passport_back'] = $request->file('passport_back')
                ->store("drivers/{$validated['personal_number']}/passport", 'public');
        }
        if ($request->hasFile('license_front')) {
            $validated['license_front'] = $request->file('license_front')
                ->store("drivers/{$validated['personal_number']}/license", 'public');
        }
        if ($request->hasFile('license_back')) {
            $validated['license_back'] = $request->file('license_back')
                ->store("drivers/{$validated['personal_number']}/license", 'public');
        }

        // Создаём запись в БД
        $driver = Driver::create($validated);

        // Переход на шаг 2
        return redirect()->route('dispatcher.backend.drivers_num_edit', ['driver' => $driver->id]);
    }

    /**
     * Шаг 2: Данные об автомобиле
     */
    public function showStep2($driverId)
    {
        $driver = Driver::findOrFail($driverId);
        $carSelects = json_decode(file_get_contents(public_path('js/car_selects.json')), true);
        $totalBalance = $this->getTotalBalance();
        $drivers = Driver::all();

        return view('disp.drivers_num_edit', compact('driver', 'carSelects', 'totalBalance', 'drivers'));
    }

    public function processStep2(Request $request, $driverId)
    {
        $driver = Driver::findOrFail($driverId);

        $request->validate([
            'car_brand'    => 'required|string|max:255',
            'car_model'    => 'required|string|max:255',
            'car_color'    => 'required|string|max:255',
            'car_year'     => 'required|digits:4',

            // Доп. поля, если нужны:
            'vin'          => 'nullable|string|max:50',
            'body_number'  => 'nullable|string|max:50',
            'sts'          => 'nullable|string|max:50',
            'callsign'     => 'nullable|string|max:50',

            'service_type' => 'required|string|max:255',
            'category'     => 'required|string|max:255',
            'tariff'       => 'required|string|max:255',
            'license_plate'=> 'required|string|max:20',

            'has_nakleyka' => 'nullable|boolean',
            'has_lightbox' => 'nullable|boolean',
            'has_child_seat' => 'nullable|boolean',
        ]);

        $data = $request->only([
            'car_brand', 'car_model', 'car_color', 'car_year',
            'vin', 'body_number', 'sts', 'callsign',
            'service_type', 'category', 'tariff', 'license_plate',
            'has_nakleyka', 'has_lightbox', 'has_child_seat',
        ]);

        // Убедимся, что водитель имеет статус одобренной заявки
        if (!$driver->survey_status) {
            $data['survey_status'] = 'approved';
            $data['approved_at'] = now();
        }

        $driver->update($data);

        return redirect()->route('dispatcher.backend.drivers_car_edit', ['driver' => $driver->id]);
    }

    /**
     * Шаг 3: Фото автомобиля
     */
    public function showStep3($driverId)
    {
        $driver = Driver::findOrFail($driverId);
        $totalBalance = $this->getTotalBalance();
        $drivers = Driver::all();
        
        return view('disp.drivers_car_edit', compact('driver', 'totalBalance', 'drivers'));
    }

    public function processStep3(Request $request, $driverId)
    {
        $driver = Driver::findOrFail($driverId);
    
        $request->validate([
            'car_front'           => 'nullable|file|mimes:jpg,jpeg,png,heic,heif|max:102400',
            'car_back'            => 'nullable|file|mimes:jpg,jpeg,png,heic,heif|max:102400',
            'license_photo'       => 'nullable|file|mimes:jpg,jpeg,png,heic,heif|max:102400',
            'car_right'           => 'nullable|file|mimes:jpg,jpeg,png,heic,heif|max:102400',
            'car_left'            => 'nullable|file|mimes:jpg,jpeg,png,heic,heif|max:102400',
            'car_interior_front'  => 'nullable|file|mimes:jpg,jpeg,png,heic,heif|max:102400',
            'car_interior_back'   => 'nullable|file|mimes:jpg,jpeg,png,heic,heif|max:102400',
        ]);
    
        $data = [];
        
        // Получаем идентификатор водителя или генерируем, если его нет
        $driverIdentifier = $driver->personal_number;
        if (empty($driverIdentifier)) {
            $driverIdentifier = Str::random(17);
            $driver->personal_number = $driverIdentifier;
            $driver->save();
        }
        
        // Отладка: Выводим список файлов в запросе
        $debugInfo = [];
        $debugInfo['has_files'] = [
            'car_front' => $request->hasFile('car_front'),
            'car_back' => $request->hasFile('car_back'),
            'license_photo' => $request->hasFile('license_photo'),
            'car_right' => $request->hasFile('car_right'),
            'car_left' => $request->hasFile('car_left'),
            'car_interior_front' => $request->hasFile('car_interior_front'),
            'car_interior_back' => $request->hasFile('car_interior_back')
        ];
        
        // Маппинг полей формы на поля в БД
        $fileMapping = [
            'car_front' => 'car_front',
            'car_back' => 'car_back',
            'license_photo' => 'license_photo',
            'car_right' => 'car_right',
            'car_left' => 'car_left',
            'car_interior_front' => 'car_interior_front',
            'car_interior_back' => 'car_interior_back'
        ];
        
        // Собираем все файлы для обработки
        $files = [];
        foreach ($fileMapping as $requestField => $dbField) {
            if ($request->hasFile($requestField)) {
                $files[$dbField] = $request->file($requestField);
            }
        }
        
        $debugInfo['files_count'] = count($files);
        
        // Обрабатываем все файлы через сервис
        if (!empty($files)) {
            $imagePaths = $this->imageService->processImages($files, $driverIdentifier);
            $debugInfo['image_paths'] = $imagePaths;
            
            foreach ($imagePaths as $field => $path) {
                $data[$field] = $path;
            }
        }
        
        $debugInfo['data_to_save'] = $data;
        
        // Отладка: проверка, что все нужные поля разрешены для заполнения
        $fillable = $driver->getFillable();
        $debugInfo['fillable_fields'] = $fillable;
        $debugInfo['driver_fields'] = array_keys($driver->getAttributes());
        
        // Убедимся, что водитель имеет статус одобренной заявки
        if (!$driver->survey_status) {
            $data['survey_status'] = 'approved';
            $data['approved_at'] = now();
        }

        // Сохраняем данные в лог перед попыткой обновления
        \Log::info('Driver update attempt:', $debugInfo);
        
        if (!empty($data)) {
            try {
                // Пробуем прямой подход через установку значений
                foreach ($data as $field => $value) {
                    $driver->{$field} = $value;
                }
                $result = $driver->save();
                
                \Log::info('Driver direct save result: ' . ($result ? 'success' : 'failed'));
                
                if (!$result) {
                    // Попробуем запасной вариант через update
                    $updateResult = $driver->update($data);
                    \Log::info('Driver update result: ' . ($updateResult ? 'success' : 'failed'));
                }
                
                // Перезагружаем модель, чтобы проверить, что данные действительно сохранились
                $driver->refresh();
                $savedData = [];
                foreach ($fileMapping as $requestField => $dbField) {
                    $savedData[$dbField] = $driver->{$dbField};
                }
                \Log::info('Driver data after save:', $savedData);
                
            } catch (\Exception $e) {
                \Log::error('Error updating driver: ' . $e->getMessage());
                \Log::error($e->getTraceAsString());
                return redirect()->route('dispatcher.backend.drivers_control')
                    ->with('error', 'Ошибка при сохранении фото: ' . $e->getMessage());
            }
        }
    
        return redirect()->route('dispatcher.backend.drivers_control')
            ->with('success', 'Фото успешно сохранены! ' . json_encode($debugInfo));
    }
}