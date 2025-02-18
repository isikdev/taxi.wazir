<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DriverCreationController extends Controller
{
    /**
     * Шаг 1: Персональные данные + загрузка ID/ВУ
     */
    public function showStep1()
    {
        return view('disp.drivers_control_edit');
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

        // Конвертируем даты в формат Y-m-d
        $validated['date_of_birth']       = Carbon::createFromFormat('d.m.Y', $validated['date_of_birth'])->format('Y-m-d');
        $validated['license_issue_date']  = Carbon::createFromFormat('d.m.Y', $validated['license_issue_date'])->format('Y-m-d');
        $validated['license_expiry_date'] = Carbon::createFromFormat('d.m.Y', $validated['license_expiry_date'])->format('Y-m-d');
        
        $validated['is_confirmed'] = true;
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

        return view('disp.drivers_num_edit', compact('driver', 'carSelects'));

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

        $driver->update($data);

        return redirect()->route('dispatcher.backend.drivers_car_edit', ['driver' => $driver->id]);
    }

    /**
     * Шаг 3: Фото автомобиля
     */
    public function showStep3($driverId)
    {
        $driver = Driver::findOrFail($driverId);
        return view('disp.drivers_car_edit', compact('driver'));
    }

    public function processStep3(Request $request, $driverId)
    {
        $driver = Driver::findOrFail($driverId);
    
        $request->validate([
            'car_front'           => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'car_back'            => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'license_photo'       => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'car_right'           => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'car_left'            => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'car_interior_front'  => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'car_interior_back'   => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);
    
        $data = [];
    
        if ($request->hasFile('car_front')) {
            $data['car_front'] = $request->file('car_front')->store("drivers/{$driver->personal_number}/car", 'public');
        }
        if ($request->hasFile('car_back')) {
            $data['car_back'] = $request->file('car_back')->store("drivers/{$driver->personal_number}/car", 'public');
        }
        if ($request->hasFile('license_photo')) {
            $data['license_photo'] = $request->file('license_photo')->store("drivers/{$driver->personal_number}/car", 'public');
        }
        if ($request->hasFile('car_right')) {
            $data['car_right'] = $request->file('car_right')->store("drivers/{$driver->personal_number}/car", 'public');
        }
        if ($request->hasFile('car_left')) {
            $data['car_left'] = $request->file('car_left')->store("drivers/{$driver->personal_number}/car", 'public');
        }
        if ($request->hasFile('car_interior_front')) {
            $data['car_interior_front'] = $request->file('car_interior_front')->store("drivers/{$driver->personal_number}/car", 'public');
        }
        if ($request->hasFile('car_interior_back')) {
            $data['car_interior_back'] = $request->file('car_interior_back')->store("drivers/{$driver->personal_number}/car", 'public');
        }
    
        if (!empty($data)) {
            $driver->update($data);
        }
    
        return redirect()->route('dispatcher.backend.drivers_control')->with('success', 'Фото успешно сохранены!');
    }
}