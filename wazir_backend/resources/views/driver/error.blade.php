<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ошибка - Wazir.kg</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/css/driver/main.css') }}">
    <style>
        .error-container {
            padding: 50px 20px;
            text-align: center;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .error-icon {
            font-size: 60px;
            color: #f44336;
            margin-bottom: 20px;
        }
        
        .error-title {
            font-size: 24px;
            margin-bottom: 15px;
            color: #333;
        }
        
        .error-message {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
            padding: 15px;
            background: #f8f8f8;
            border-radius: 5px;
            border-left: 3px solid #f44336;
        }
        
        .button-container {
            margin-top: 20px;
        }
        
        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        
        .back-button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="driver-header">
        <a href="{{ route('driver.index') }}" class="logo">
            <img src="{{ asset('assets/img/driver/logo.png') }}" alt="Wazir.kg">
        </a>
    </div>
    
    <div class="error-container">
        <div class="error-icon">⚠️</div>
        <h1 class="error-title">Произошла ошибка</h1>
        
        <div class="error-message">
            {{ $error ?? 'Произошла непредвиденная ошибка. Пожалуйста, попробуйте позже или обратитесь в службу поддержки.' }}
        </div>
        
        <div class="button-container">
            <a href="{{ url()->previous() }}" class="back-button">Вернуться назад</a>
        </div>
    </div>
    
    <script src="{{ asset('assets/js/driver/main.js') }}"></script>
</body>

</html> 