<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use App\Services\DriverImageService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class SurveyController extends Controller
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
     * Проверяет наличие авторизованного водителя
     */
    private function checkAuth()
    {
        if (!session()->has('registered') || !session()->has('driver_id')) {
            return false;
        }
        return true;
    }

    /**
     * Проверяет, была ли анкета уже отправлена
     * Если анкета отправлена, перенаправляет на страницу статуса
     */
    private function checkApplicationSubmitted()
    {
        // Получаем ID водителя из сессии
        $driverId = session('driver_id');
        $driver = Driver::find($driverId);
        
        if (!$driver) {
            return false;
        }
        
        // Если анкета уже отправлена, перенаправляем на страницу статуса
        if (in_array($driver->survey_status, ['submitted', 'approved', 'rejected'])) {
            return true;
        }
        
        return false;
    }

    /**
     * Сохраняет текущий шаг заполнения анкеты
     * и обновляет максимальный достигнутый шаг
     */
    private function saveCurrentStep($step)
    {
        session(['survey_step' => $step]);
        
        // Обновляем максимальный достигнутый шаг
        $maxStep = session('max_survey_step', 1);
        if ($step > $maxStep) {
            session(['max_survey_step' => $step]);
        }
    }

    /**
     * Возвращает последний сохраненный шаг анкеты
     */
    private function getCurrentStep()
    {
        return session('survey_step', 1);
    }
    
    /**
     * Проверяет, может ли пользователь перейти на указанный шаг
     */
    private function canAccessStep($step)
    {
        $maxStep = session('max_survey_step', 1);
        return $step <= $maxStep;
    }

    /**
     * Перенаправляет на последний сохраненный шаг
     */
    public function redirectToLastStep()
    {
        if (!$this->checkAuth()) {
            return redirect()->route('driver.auth.step1');
        }
        
        // Проверяем, была ли анкета уже отправлена
        if ($this->checkApplicationSubmitted()) {
            return redirect()->route('driver.survey.applicationStatus');
        }
        
        $step = $this->getCurrentStep();
        return redirect()->route('driver.survey.step' . $step);
    }

    /**
     * Показывает вводную страницу анкеты (шаг 1)
     */
    public function showStep1()
    {
        if (!$this->checkAuth()) {
            return redirect()->route('driver.auth.step1');
        }
        
        // Проверяем, была ли анкета уже отправлена
        if ($this->checkApplicationSubmitted()) {
            return redirect()->route('driver.survey.applicationStatus');
        }
        
        // Шаг 1 всегда доступен
        $this->saveCurrentStep(1);
        return view('driver.survey.step1');
    }

    /**
     * Обработка шага 1 - переход к шагу 2
     */
    public function processStep1(Request $request)
    {
        if (!$this->checkAuth()) {
            return redirect()->route('driver.auth.step1');
        }
        
        // Проверяем, была ли анкета уже отправлена
        if ($this->checkApplicationSubmitted()) {
            return redirect()->route('driver.survey.applicationStatus');
        }
        
        // Разрешаем переход на шаг 2
        session(['max_survey_step' => max(session('max_survey_step', 1), 2)]);
        
        return redirect()->route('driver.survey.step2');
    }

    /**
     * Переход к шагу 2 - выбор города
     */
    public function showStep2()
    {
        if (!$this->checkAuth()) {
            return redirect()->route('driver.auth.step1');
        }
        
        // Проверяем, была ли анкета уже отправлена
        if ($this->checkApplicationSubmitted()) {
            return redirect()->route('driver.survey.applicationStatus');
        }
        
        // Проверяем, может ли пользователь перейти на этот шаг
        if (!$this->canAccessStep(2)) {
            return redirect()->route('driver.survey.step' . session('max_survey_step', 1));
        }
        
        // Загружаем список городов из JSON-файла
        $carSelectsData = json_decode(file_get_contents(public_path('js/car_selects.json')), true);
        $cities = $carSelectsData['cities'] ?? [];
        
        $this->saveCurrentStep(2);
        return view('driver.survey.step2', compact('cities'));
    }

    /**
     * Обработка шага 2 - сохранение выбранного города
     */
    public function processStep2(Request $request)
    {
        if (!$this->checkAuth()) {
            return redirect()->route('driver.auth.step1');
        }
        
        // Проверяем, была ли анкета уже отправлена
        if ($this->checkApplicationSubmitted()) {
            return redirect()->route('driver.survey.applicationStatus');
        }
        
        // Проверяем, может ли пользователь обрабатывать этот шаг
        if (!$this->canAccessStep(2)) {
            return redirect()->route('driver.survey.step' . session('max_survey_step', 1));
        }

        $request->validate([
            'city' => 'required|string',
        ]);

        // Сохраняем данные в сессию
        session(['city' => $request->input('city')]);
        
        // Разрешаем переход на шаг 3
        session(['max_survey_step' => max(session('max_survey_step', 2), 3)]);
        
        return redirect()->route('driver.survey.step3');
    }

    /**
     * Переход к шагу 3 - выбор тарифа
     */
    public function showStep3()
    {
        if (!$this->checkAuth()) {
            return redirect()->route('driver.auth.step1');
        }
        
        // Проверяем, была ли анкета уже отправлена
        if ($this->checkApplicationSubmitted()) {
            return redirect()->route('driver.survey.applicationStatus');
        }
        
        // Проверяем, может ли пользователь перейти на этот шаг
        if (!$this->canAccessStep(3)) {
            return redirect()->route('driver.survey.step' . session('max_survey_step', 1));
        }
        
        $this->saveCurrentStep(3);
        return view('driver.survey.step3');
    }

    /**
     * Обработка шага 3 - сохранение данных водительского удостоверения
     */
    public function processStep3(Request $request)
    {
        if (!$this->checkAuth()) {
            return redirect()->route('driver.auth.step1');
        }
        
        // Проверяем, была ли анкета уже отправлена
        if ($this->checkApplicationSubmitted()) {
            return redirect()->route('driver.survey.applicationStatus');
        }
        
        // Проверяем, может ли пользователь обрабатывать этот шаг
        if (!$this->canAccessStep(3)) {
            return redirect()->route('driver.survey.step' . session('max_survey_step', 1));
        }

        $request->validate([
            'country' => 'required',
            'fullname' => 'required|string|max:100',
            'license_number' => 'required|string|max:8',
            'issue_date' => 'required|string',
            // 'expiry_date' не обязательно
        ]);

        // Сохраняем данные в сессию
        session([
            'country' => $request->input('country'),
            'fullname' => $request->input('fullname'),
            'license_number' => $request->input('license_number'),
            'issue_date' => $request->input('issue_date'),
            'expiry_date' => $request->input('expiry_date'),
            'invitation_code' => $request->input('invitation_code'),
        ]);
        
        // Разрешаем переход на шаг 4
        session(['max_survey_step' => max(session('max_survey_step', 3), 4)]);
        
        // Если редактирование происходит со страницы проверки, возвращаем обратно на нее
        if ($request->has('redirect_to_complete')) {
            return redirect()->route('driver.survey.complete');
        }
        
        return redirect()->route('driver.survey.step4');
    }

    /**
     * Переход к шагу 4 - данные об автомобиле
     */
    public function showStep4()
    {
        if (!$this->checkAuth()) {
            return redirect()->route('driver.auth.step1');
        }
        
        // Проверяем, была ли анкета уже отправлена
        if ($this->checkApplicationSubmitted()) {
            return redirect()->route('driver.survey.applicationStatus');
        }
        
        // Проверяем, может ли пользователь перейти на этот шаг
        if (!$this->canAccessStep(4)) {
            return redirect()->route('driver.survey.step' . session('max_survey_step', 1));
        }
        
        // Загружаем данные из JSON-файла
        $carSelectsData = json_decode(file_get_contents(public_path('js/car_selects.json')), true);
        $brands = $carSelectsData['brands'] ?? [];
        $models = $carSelectsData['models'] ?? [];
        $colors = $carSelectsData['colors'] ?? [];
        $years = $carSelectsData['years'] ?? [];
        
        $this->saveCurrentStep(4);
        return view('driver.survey.step4', compact('brands', 'models', 'colors', 'years'));
    }

    /**
     * Обработка шага 4 - сохранение данных об автомобиле
     */
    public function processStep4(Request $request)
    {
        if (!$this->checkAuth()) {
            return redirect()->route('driver.auth.step1');
        }
        
        // Проверяем, была ли анкета уже отправлена
        if ($this->checkApplicationSubmitted()) {
            return redirect()->route('driver.survey.applicationStatus');
        }
        
        // Проверяем, может ли пользователь обрабатывать этот шаг
        if (!$this->canAccessStep(4)) {
            return redirect()->route('driver.survey.step' . session('max_survey_step', 1));
        }

        $request->validate([
            'car_brand' => 'required|string',
            'car_model' => 'required|string',
            'car_color' => 'required|string',
            'car_year' => 'required|string',
            'license_plate' => 'required|string|max:15',
            'vin' => 'required|string|max:17',
            'body_number' => 'required|string',
            'sts' => 'required|string',
            'transmission' => 'required|string',
            'boosters' => 'required|string',
            'tariff' => 'required|string',
            'service_type' => 'required|string',
            'category' => 'required|string',
            'child_seat' => 'nullable|string',
            'parking_car' => 'nullable|string',
            'has_nakleyka' => 'nullable|string',
            'has_lightbox' => 'nullable|string',
        ]);

        // Сохраняем данные в сессию
        session([
            'car_brand' => $request->input('car_brand'),
            'car_model' => $request->input('car_model'),
            'car_color' => $request->input('car_color'),
            'car_year' => $request->input('car_year'),
            'license_plate' => $request->input('license_plate'),
            'vin' => $request->input('vin'),
            'body_number' => $request->input('body_number'),
            'sts' => $request->input('sts'),
            'transmission' => $request->input('transmission'),
            'boosters' => $request->input('boosters'),
            'tariff' => $request->input('tariff'),
            'service_type' => $request->input('service_type'),
            'category' => $request->input('category'),
            'child_seat' => $request->input('child_seat'),
            'parking_car' => $request->input('parking_car'),
            'has_nakleyka' => $request->input('has_nakleyka'),
            'has_lightbox' => $request->input('has_lightbox'),
        ]);
        
        // Разрешаем переход на шаг 5
        session(['max_survey_step' => max(session('max_survey_step', 4), 5)]);
        
        // Если редактирование происходит со страницы проверки, возвращаем обратно на нее
        if ($request->has('redirect_to_complete')) {
            return redirect()->route('driver.survey.complete');
        }
        
        return redirect()->route('driver.survey.step5');
    }

    /**
     * Переход к шагу 5 - условия работы в парке
     */
    public function showStep5()
    {
        if (!$this->checkAuth()) {
            return redirect()->route('driver.auth.step1');
        }
        
        // Проверяем, была ли анкета уже отправлена
        if ($this->checkApplicationSubmitted()) {
            return redirect()->route('driver.survey.applicationStatus');
        }
        
        // Проверяем, может ли пользователь перейти на этот шаг
        if (!$this->canAccessStep(5)) {
            return redirect()->route('driver.survey.step' . session('max_survey_step', 1));
        }
        
        $this->saveCurrentStep(5);
        return view('driver.survey.step5');
    }

    /**
     * Обработка шага 5 - соглашение с условиями работы в парке
     */
    public function processStep5(Request $request)
    {
        if (!$this->checkAuth()) {
            return redirect()->route('driver.auth.step1');
        }
        
        // Проверяем, была ли анкета уже отправлена
        if ($this->checkApplicationSubmitted()) {
            return redirect()->route('driver.survey.applicationStatus');
        }
        
        // Проверяем, может ли пользователь обрабатывать этот шаг
        if (!$this->canAccessStep(5)) {
            return redirect()->route('driver.survey.step' . session('max_survey_step', 1));
        }

        // Сохраняем в сессию согласие с условиями парка
        session(['park_conditions_accepted' => true]);
        
        // Разрешаем переход на шаг 6
        session(['max_survey_step' => max(session('max_survey_step', 5), 6)]);
        
        return redirect()->route('driver.survey.step6');
    }

    /**
     * Переход к шагу 6 - выбор таксопарка
     */
    public function showStep6()
    {
        if (!$this->checkAuth()) {
            return redirect()->route('driver.auth.step1');
        }
        
        // Проверяем, была ли анкета уже отправлена
        if ($this->checkApplicationSubmitted()) {
            return redirect()->route('driver.survey.applicationStatus');
        }
        
        // Проверяем, может ли пользователь перейти на этот шаг
        if (!$this->canAccessStep(6)) {
            return redirect()->route('driver.survey.step' . session('max_survey_step', 1));
        }
        
        // Загружаем список таксопарков из car_selects.json
        $carSelectsData = json_decode(file_get_contents(public_path('js/car_selects.json')), true);
        $taxiParks = $carSelectsData['parks'] ?? [];
        
        $this->saveCurrentStep(6);
        return view('driver.survey.step6', compact('taxiParks'));
    }

    /**
     * Обработка шага 6 - сохранение выбранного таксопарка
     */
    public function processStep6($park_id, Request $request)
    {
        if (!$this->checkAuth()) {
            return redirect()->route('driver.auth.step1');
        }
        
        // Проверяем, была ли анкета уже отправлена
        if ($this->checkApplicationSubmitted()) {
            return redirect()->route('driver.survey.applicationStatus');
        }
        
        // Проверяем, может ли пользователь обрабатывать этот шаг
        if (!$this->canAccessStep(6)) {
            return redirect()->route('driver.survey.step' . session('max_survey_step', 1));
        }

        // Валидация park_id
        if (!is_numeric($park_id)) {
            return back()->withErrors(['park_id' => 'Некорректный ID парка']);
        }

        // Загружаем список таксопарков из car_selects.json
        $carSelectsData = json_decode(file_get_contents(public_path('js/car_selects.json')), true);
        $taxiParks = $carSelectsData['parks'] ?? [];
        
        // Ищем выбранный парк в списке
        $selectedPark = null;
        foreach ($taxiParks as $park) {
            if ($park['id'] == $park_id) {
                $selectedPark = $park;
                break;
            }
        }
        
        if (!$selectedPark) {
            return back()->withErrors(['park_id' => 'Выбранный парк не найден']);
        }

        // Сохраняем выбранный парк в сессию
        session([
            'park_id' => $park_id,
            'park_name' => $selectedPark['name']
        ]);
        
        // Разрешаем переход на шаг 7
        session(['max_survey_step' => max(session('max_survey_step', 6), 7)]);
        
        // Если редактирование происходит со страницы проверки, возвращаем обратно на нее
        if ($request->has('redirect_to_complete')) {
            return redirect()->route('driver.survey.complete');
        }
        
        return redirect()->route('driver.survey.step7');
    }

    /**
     * Переход к шагу 7 - информация о таксопарке
     */
    public function showStep7(Request $request)
    {
        if (!$this->checkAuth()) {
            return redirect()->route('driver.auth.step1');
        }
        
        // Проверяем, была ли анкета уже отправлена
        if ($this->checkApplicationSubmitted()) {
            return redirect()->route('driver.survey.applicationStatus');
        }
        
        // Проверяем, может ли пользователь перейти на этот шаг
        if (!$this->canAccessStep(7)) {
            return redirect()->route('driver.survey.step' . session('max_survey_step', 1));
        }
        
        // Получаем ID выбранного парка из сессии
        $parkId = session('park_id');
        
        if (!$parkId) {
            return redirect()->route('driver.survey.step6')->with('error', 'Сначала выберите таксопарк');
        }
        
        // Загружаем данные о парке
        $carSelectsData = json_decode(file_get_contents(public_path('js/car_selects.json')), true);
        $taxiParks = $carSelectsData['parks'] ?? [];
        
        // Ищем выбранный парк в списке
        $selectedPark = null;
        foreach ($taxiParks as $park) {
            if ($park['id'] == $parkId) {
                $selectedPark = $park;
                break;
            }
        }
        
        if (!$selectedPark) {
            return redirect()->route('driver.survey.step6')->with('error', 'Выбранный парк не найден');
        }
        
        // Получаем данные пользователя
        $driverId = session('driver_id');
        $driver = Driver::findOrFail($driverId);
        
        // Обрабатываем телефонный номер для правильного отображения
        $phone = $driver->phone ?? session('phone');
        
        // Нормализуем номер телефона, добавляя код страны если необходимо
        $phone = preg_replace('/[^0-9+]/', '', $phone);
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
            else if (!empty($phone) && !str_starts_with($phone, '+')) {
                $phone = '+996' . $phone;
            }
        }
        
        // Подготавливаем данные для отображения
        $data = [
            'parkName' => $selectedPark['name'],
            'parkPhone' => '+996550123456', // Киргизский номер телефона
            'workHours' => 'Пн-Сб 10:00-18:00',
            'weekend' => 'Вс-выходной',
            'email' => 'Example@gmail.com',
            'address' => 'Кыргыстан г. Ок мкр Анар 1, (орентир Автомойка Нурзаман, кафе Нирвана)',
            'phone' => $phone
        ];
        
        // Проверяем, запрошена ли страница с условиями
        if ($request->has('page') && $request->page === 'terms') {
            return view('driver.survey.step7_terms', $data);
        }
        
        $this->saveCurrentStep(7);
        return view('driver.survey.step7', $data);
    }

    /**
     * Обработка шага 7 - сохранение фотографий
     */
    public function processStep7(Request $request)
    {
        if (!$this->checkAuth()) {
            return redirect()->route('driver.auth.step1');
        }
        
        // Проверяем, была ли анкета уже отправлена
        if ($this->checkApplicationSubmitted()) {
            return redirect()->route('driver.survey.applicationStatus');
        }
        
        // Проверяем, может ли пользователь обрабатывать этот шаг
        if (!$this->canAccessStep(7)) {
            return redirect()->route('driver.survey.step' . session('max_survey_step', 1));
        }

        $request->validate([
            'confirm_park' => 'required',
        ]);

        // Разрешаем переход на шаг 8
        session(['max_survey_step' => max(session('max_survey_step', 7), 8)]);
        
        return redirect()->route('driver.survey.step8');
    }

    /**
     * Обработка шага 8 - загрузка документов
     */
    public function processStep8(Request $request)
    {
        if (!$this->checkAuth()) {
            return redirect()->route('driver.auth.step1');
        }
        
        // Проверяем, была ли анкета уже отправлена
        if ($this->checkApplicationSubmitted()) {
            return redirect()->route('driver.survey.applicationStatus');
        }
        
        // Валидация файлов
        $request->validate([
            'passport_front' => 'nullable|file|mimes:jpg,jpeg,png,heic,heif|max:102400',
            'passport_back' => 'nullable|file|mimes:jpg,jpeg,png,heic,heif|max:102400',
            'driving_license_front' => 'nullable|file|mimes:jpg,jpeg,png,heic,heif|max:102400',
            'driving_license_back' => 'nullable|file|mimes:jpg,jpeg,png,heic,heif|max:102400',
            'car_front' => 'nullable|file|mimes:jpg,jpeg,png,heic,heif|max:102400',
            'car_back' => 'nullable|file|mimes:jpg,jpeg,png,heic,heif|max:102400',
            'car_left' => 'nullable|file|mimes:jpg,jpeg,png,heic,heif|max:102400',
            'car_right' => 'nullable|file|mimes:jpg,jpeg,png,heic,heif|max:102400',
            'interior_front' => 'nullable|file|mimes:jpg,jpeg,png,heic,heif|max:102400',
            'interior_back' => 'nullable|file|mimes:jpg,jpeg,png,heic,heif|max:102400',
        ]);
        
        // Временное хранилище для файлов
        $driverId = session('driver_id');
        $driver = Driver::find($driverId);
        
        if (!$driver) {
            return redirect()->route('driver.auth.step1')->withErrors(['error' => 'Водитель не найден']);
        }
        
        // Получаем персональный номер или генерируем временный для структуры хранения
        $driverIdentifier = $driver->personal_number ?? 'temp_' . $driverId;
        
        $documentPaths = [];
        
        // Обрабатываем каждый файл через сервис изображений
        $files = [
            'passport_front' => 'passport_front', 
            'passport_back' => 'passport_back', 
            'driving_license_front' => 'license_front', 
            'driving_license_back' => 'license_back',
            'car_front' => 'car_front',
            'car_back' => 'car_back',
            'car_left' => 'car_left',
            'car_right' => 'car_right',
            'interior_front' => 'interior_front',
            'interior_back' => 'interior_back'
        ];
        
        \Log::info('Начинаем обработку файлов на шаге 8');
        
        try {
            foreach ($files as $fileKey => $dbField) {
                if ($request->hasFile($fileKey)) {
                    $file = $request->file($fileKey);
                    
                    // Логирование информации о файле для отладки
                    \Log::info('Загрузка файла', [
                        'field' => $fileKey,
                        'original_name' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                        'mime' => $file->getMimeType(),
                        'extension' => $file->getClientOriginalExtension()
                    ]);
                    
                    // Временно сохраняем в temp директорию
                    $tempPath = $this->imageService->saveImage($file, "temp_{$driverId}", $dbField);
                    $documentPaths[$dbField] = $tempPath;
                }
            }
            
            \Log::info('Все файлы на шаге 8 успешно обработаны и сохранены во временное хранилище');
        } catch (\Exception $e) {
            \Log::error('Ошибка при обработке файлов на шаге 8: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Произошла ошибка при загрузке файлов: ' . $e->getMessage()])->withInput();
        }
        
        try {
            // Сохраняем пути к документам в сессии
            session(['document_paths' => $documentPaths]);
            
            // Разрешаем переход на страницу завершения
            session(['max_survey_step' => max(session('max_survey_step', 8), 9)]);
        } catch (\Exception $e) {
            \Log::error('Ошибка при сохранении данных в сессии: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Произошла ошибка при сохранении данных: ' . $e->getMessage()])->withInput();
        }
        
        \Log::info('Завершение обработки шага 8, перенаправление на страницу complete');
        
        // Перенаправляем на страницу проверки
        return redirect()->route('driver.survey.complete');
    }

    /**
     * Показывает страницу завершения заполнения анкеты
     */
    public function complete()
    {
        if (!$this->checkAuth()) {
            return redirect()->route('driver.auth.step1');
        }
        
        // Проверяем, была ли анкета уже отправлена
        if ($this->checkApplicationSubmitted()) {
            return redirect()->route('driver.survey.applicationStatus');
        }
        
        // Проверяем, что все необходимые данные заполнены
        $requiredSessionData = [
            'fullname',
            'license_number',
            'issue_date',
            'car_brand',
            'car_model',
            'car_color',
            'car_year',
            'license_plate',
            'category',
            'service_type',
            'tariff'
        ];
        
        $missingData = [];
        foreach ($requiredSessionData as $field) {
            if (!session()->has($field) || empty(session($field))) {
                $missingData[] = $field;
            }
        }
        
        // Если есть отсутствующие данные, показываем сообщение об ошибке
        if (!empty($missingData)) {
            $errorMsg = 'Пожалуйста, заполните все необходимые поля перед отправкой анкеты';
            return redirect()->route('driver.survey.step1')->withErrors(['error' => $errorMsg]);
        }
        
        // Сохраняем информацию о текущем шаге
        $this->saveCurrentStep(8); // Завершающий шаг
        
        // Получаем ID водителя из сессии
        $driverId = session('driver_id');
        try {
            $driver = Driver::find($driverId);
            
            if (!$driver) {
                \Log::error('Водитель не найден при загрузке страницы complete. ID: ' . $driverId);
                return redirect()->route('driver.auth.step1')->withErrors(['error' => 'Водитель не найден. Пожалуйста, авторизуйтесь снова.']);
            }
            
            // Получаем пути к документам из сессии
            $documentPaths = session('document_paths', []);
            
            // Установим значение телефона по умолчанию, если оно отсутствует
            $phone = $driver->phone ?? session('phone', 'Не указан');
            
            // Подготавливаем данные для передачи в представление
            $data = [
                // Личная информация
                'fullname' => session('fullname'),
                'phone' => $phone,
                'city' => session('city'),
                
                // Данные водительского удостоверения
                'license_number' => session('license_number'),
                'country' => session('country'),
                'issue_date' => session('issue_date'),
                'expiry_date' => session('expiry_date'),
                'callsign' => session('callsign'),
                
                // Данные автомобиля
                'car_brand' => session('car_brand'),
                'car_model' => session('car_model'),
                'car_color' => session('car_color'),
                'car_year' => session('car_year'),
                'license_plate' => session('license_plate'),
                'vin' => session('vin'),
                'body_number' => session('body_number'),
                'sts' => session('sts'),
                
                // Сервисные параметры
                'transmission' => session('transmission'),
                'boosters' => session('boosters'),
                'tariff' => session('tariff'),
                'service_type' => session('service_type'),
                'category' => session('category'),
                'child_seat' => session('child_seat'),
                'parking_car' => session('parking_car'),
                'has_nakleyka' => session('has_nakleyka'),
                'has_lightbox' => session('has_lightbox'),
                
                // Данные таксопарка
                'park_name' => session('park_name'),
                
                // Документы - используем пути из сессии, а не из БД
                'documents' => $documentPaths
            ];
            
            // Логируем успешную загрузку страницы complete
            \Log::info('Страница complete успешно загружена для водителя ID: ' . $driverId);
            
            // Передаем driver и data в представление
            return view('driver.survey.complete', ['driver' => $driver, 'data' => $data]);
        } catch (\Exception $e) {
            // Логируем ошибку
            \Log::error('Ошибка при загрузке страницы complete: ' . $e->getMessage(), [
                'driver_id' => $driverId,
                'trace' => $e->getTraceAsString()
            ]);
            
            // Возвращаем пользователя на страницу с сообщением об ошибке
            return back()->withErrors(['error' => 'Произошла ошибка при загрузке страницы: ' . $e->getMessage()]);
        }
    }

    /**
     * Отображает страницу со статусом заявки
     */
    public function applicationStatus()
    {
        if (!$this->checkAuth()) {
            return redirect()->route('driver.auth.step1');
        }
        
        // Получаем ID водителя из сессии
        $driverId = session('driver_id');
        $driver = Driver::find($driverId);
        
        if (!$driver) {
            return redirect()->route('driver.auth.step1')->withErrors(['error' => 'Водитель не найден']);
        }
        
        // Если анкета еще не отправлена, перенаправляем на шаг заполнения
        if (!in_array($driver->survey_status, ['submitted', 'approved', 'rejected'])) {
            $maxStep = session('max_survey_step', 1);
            return redirect()->route('driver.survey.step' . $maxStep);
        }
        
        return view('driver.survey.application_status', ['driver' => $driver]);
    }

    /**
     * Отправка заявки
     */
    public function submitApplication()
    {
        if (!$this->checkAuth()) {
            return redirect()->route('driver.auth.step1');
        }
        
        // Получаем ID водителя из сессии
        $driverId = session('driver_id');
        $driver = Driver::find($driverId);
        
        if (!$driver) {
            return redirect()->route('driver.auth.step1')->withErrors(['error' => 'Водитель не найден']);
        }

        // Генерируем персональный номер, если его нет
        $personalNumber = $driver->personal_number ?? Str::random(17);
        
        // Конвертируем даты из формата DD.MM.YYYY в YYYY-MM-DD
        $issueDate = null;
        $expiryDate = null;
        $dateOfBirth = null;
        
        if (session('issue_date')) {
            try {
                $issueDate = \Carbon\Carbon::createFromFormat('d.m.Y', session('issue_date'))->format('Y-m-d');
            } catch (\Exception $e) {
                // Если формат даты неверный, оставляем null
            }
        }
        
        if (session('expiry_date')) {
            try {
                $expiryDate = \Carbon\Carbon::createFromFormat('d.m.Y', session('expiry_date'))->format('Y-m-d');
            } catch (\Exception $e) {
                // Если формат даты неверный, оставляем null
            }
        }
        
        if (session('date_of_birth')) {
            try {
                $dateOfBirth = \Carbon\Carbon::createFromFormat('d.m.Y', session('date_of_birth'))->format('Y-m-d');
            } catch (\Exception $e) {
                // Если дата рождения не задана или неверный формат, берем текущую дату
                $dateOfBirth = date('Y-m-d');
            }
        } else {
            // Если дата рождения не задана, берем текущую дату
            $dateOfBirth = date('Y-m-d');
        }
        
        // Получаем пути к документам из сессии
        $documentPaths = session('document_paths', []);
        
        // Перемещаем файлы из временного хранилища в постоянное
        if (!empty($documentPaths)) {
            $documentPaths = $this->imageService->moveTemporaryFilesToStorage(
                $documentPaths, 
                $personalNumber
            );
        }
        
        try {
            // Массив для хранения обновлений с только существующими полями
            $driverUpdates = [
                'survey_status' => 'submitted', // Анкета отправлена
                'is_confirmed' => false, // Заявка НЕ подтверждена 
                'full_name' => session('fullname'),
                'date_of_birth' => $dateOfBirth,
                'license_number' => session('license_number'),
                'license_issue_date' => $issueDate,
                'license_expiry_date' => $expiryDate,
                'personal_number' => $personalNumber
            ];
            
            // Добавляем только те поля, которые точно существуют в таблице и не требуют 
            // преобразования boolean значений
            $optionalFields = [
                'service_type', 'category', 'tariff', 'transmission', 
                'car_brand', 'car_model', 'car_color', 'car_year', 
                'license_plate', 'body_number', 'vin', 'sts'
            ];
            
            foreach ($optionalFields as $field) {
                if (session()->has($field)) {
                    $driverUpdates[$field] = session($field);
                }
            }
            
            // Обрабатываем булевы поля и поля да/нет
            $booleanFields = [
                'has_nakleyka', 'has_lightbox', 'boosters', 
                'child_seat', 'parking_car'
            ];
            
            foreach ($booleanFields as $field) {
                if (session()->has($field)) {
                    $value = session($field);
                    // Преобразуем различные варианты "да" в булевы значения
                    if (is_string($value)) {
                        $value = (
                            $value === 'on' || 
                            $value === '1' || 
                            $value === 'true' || 
                            strtolower($value) === 'да' || 
                            strtolower($value) === 'yes'
                        );
                    }
                    $driverUpdates[$field] = $value;
                    
                    // Логируем процесс преобразования для отладки
                    \Log::info("Преобразование поля {$field}:", [
                        'исходное_значение' => session($field),
                        'преобразованное_значение' => $value
                    ]);
                }
            }
            
            // Только при отправке анкеты сохраняем пути к документам в БД
            if (!empty($documentPaths)) {
                foreach ($documentPaths as $field => $path) {
                    $driverUpdates[$field] = $path;
                }
            }
            
            // Логируем запрос перед отправкой для отладки
            \Log::info('Обновление данных водителя:', [
                'driver_id' => $driverId,
                'updates' => $driverUpdates
            ]);
            
            // Обновляем данные водителя включая пути к документам
            $driver->update($driverUpdates);
            
            // Явно устанавливаем статус 'submitted' и is_confirmed = false сохраняем еще раз для обеспечения сохранения
            $driver->survey_status = 'submitted';
            $driver->is_confirmed = false;
            $driver->save();
            
            // Перенаправляем на страницу статуса заявки
            return redirect()->route('driver.survey.applicationStatus');
        } catch (\Exception $e) {
            // Логируем ошибку
            \Log::error('Ошибка при обновлении данных водителя: ' . $e->getMessage(), [
                'driver_id' => $driverId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Возвращаем пользователя на страницу формы с сообщением об ошибке
            return back()->withErrors(['error' => 'Произошла ошибка при сохранении данных: ' . $e->getMessage()]);
        }
    }
} 