<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Driver;

class DriverAuthController extends Controller
{
    public function __construct()
    {
        // Если пользователь уже зарегистрирован, перенаправляем на опрос
        if (session()->has('registered') && session('registered') === true) {
            redirect()->to('driver/survey/1.html')->send();
        }
    }

    public function showStep1()
    {
        return view('driver.auth.step1'); // resources/views/driver/auth/step1.blade.php
    }

    public function processStep1(Request $request)
    {
        $request->validate(['phone' => 'required']);
        $phone = $request->input('phone');
        session(['phone' => $phone]);

        // Отправка SMS через API i-digital
        //$apiKey = 'MTA5NDA6QjV0c3pTM1VMVXA0V09NUVYzYlFYSw==';
        //$gatewayId = 'cUfq9v';
        //$response = Http::withHeaders([
        //    'Authorization' => "Basic {$apiKey}",
         //   'Content-Type'  => 'application/json'
        //])->post('https://direct.i-dgtl.ru/api/v1/verifier/send', [
          ///  'channelType' => 'SMS',
        ///    'destination' => $phone,
         ///   'gatewayId'   => $gatewayId
        ///]);

        //if ($response->successful()) {
        //    $data = $response->json();
        //    session(['sms_uuid' => $data['uuid']]);
        //    $smsCount = session('sms_count', 0);
        //    session(['sms_count' => $smsCount + 1]);
        //    Log::info('SMS отправлено', ['phone' => $phone, 'uuid' => $data['uuid']]);
        //    return redirect()->route('driver.auth.step2');
        //} else {
        //    Log::error('Ошибка отправки SMS', ['phone' => $phone, 'response' => $response->body()]);
        //    return redirect()->back()->withErrors(['phone' => 'Ошибка отправки SMS. Попробуйте позже.']);
        //}
        return redirect()->route('driver.auth.step2');

    }

    public function showStep2()
    {
        if (!session()->has('phone')) {
            return redirect()->route('driver.auth.step1');
        }
        return view('driver.auth.step2');
    }

    public function processStep2(Request $request)
{
    $request->validate([
        'sms_code' => 'required|digits:4'
    ]);

    $smsCode = $request->input('sms_code');
    $phone = session('phone');

    if ($smsCode === '1111') {
        \Log::info('SMS код подтверждён (фиксированный код 1111)', ['phone' => $phone]);
        return redirect()->route('driver.auth.step3');
    } else {
        \Log::error('Неверный SMS код', ['phone' => $phone, 'code' => $smsCode]);
        return redirect()->back()->withErrors(['sms_code' => 'Неверный код']);
    }
}



    public function showStep3()
    {
        if (!session()->has('phone')) {
            return redirect()->route('driver.auth.step1');
        }
        return view('driver.auth.step3');
    }

    public function processStep3(Request $request)
{
    $request->validate([
        'first_name' => 'required|string',
        'last_name'  => 'required|string'
    ]);
    
    $phone = session('phone');
    $fullName = $request->input('first_name') . ' ' . $request->input('last_name');
    
    $driver = new Driver();
    $driver->full_name = $fullName;
    $driver->phone = $phone;
    // Передаем пустые значения для остальных полей
    $driver->license_number = '';
    $driver->license_issue_date = null;
    $driver->license_expiry_date = null;
    // Можно также не передавать поля city, если они тоже не обязательны
    
    $driver->save();
    
    session(['registered' => true]);
    \Log::info('Водитель успешно зарегистрирован', ['driver_id' => $driver->id, 'phone' => $phone]);
    return redirect()->to('driver/survey/1.html');
}

}
