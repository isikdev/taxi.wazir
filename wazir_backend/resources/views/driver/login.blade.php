<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в систему - Wazir.kg</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/css/driver/main.css') }}">
    <style>
    .login-container {
        max-width: 400px;
        margin: 50px auto;
        padding: 30px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .login-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .login-header h1 {
        font-size: 24px;
        font-weight: 600;
        color: #333;
    }

    .login-form label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #555;
    }

    .login-form input {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        margin-bottom: 20px;
        font-size: 16px;
    }

    .login-form input:focus {
        border-color: #0066cc;
        outline: none;
        box-shadow: 0 0 0 2px rgba(0, 102, 204, 0.2);
    }

    .login-form button {
        width: 100%;
        padding: 12px;
        background: #0066cc;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.2s;
    }

    .login-form button:hover {
        background: #0055aa;
    }

    .login-footer {
        text-align: center;
        margin-top: 20px;
    }

    .login-footer a {
        color: #0066cc;
        text-decoration: none;
    }

    .error-message {
        color: #d9534f;
        margin-bottom: 15px;
        font-size: 14px;
    }
    </style>
</head>

<body>
    <header>
        <div class="brand">
            <div class="container">
                <div class="brand__content">
                    <div class="logo">
                        <img src="{{ asset('assets/img/driver/logo.png') }}" alt="logo">
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="login-container">
                <div class="login-header">
                    <h1>Вход для водителей</h1>
                </div>
                
                <form class="login-form" action="{{ route('driver.login') }}" method="POST">
                    @csrf
                    
                    @if($errors->any())
                        <div class="error-message">
                            {{ $errors->first() }}
                        </div>
                    @endif
                    
                    <div>
                        <label for="phone">Номер телефона</label>
                        <input type="text" id="phone" name="phone" placeholder="+996 XXX XXX XXX" value="{{ old('phone') }}" required>
                    </div>
                    
                    <div>
                        <label for="password">Пароль</label>
                        <input type="password" id="password" name="password" placeholder="Введите пароль" required>
                    </div>
                    
                    <button type="submit">Войти</button>
                </form>
                
                <div class="login-footer">
                    <p>Еще не зарегистрированы? <a href="{{ route('driver.survey.start') }}">Зарегистрироваться</a></p>
                </div>
            </div>
        </div>
    </main>
</body>

</html> 