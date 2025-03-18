<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Вход в систему | Ош Титан Парк</title>
    <link rel="icon" href="{{ asset('assets/img/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/css/disp/main.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    body {
        background-color: #1e1e2f;
        font-family: 'Roboto', sans-serif;
        color: white;
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .login-container {
        position: relative;
        max-width: 450px;
        width: 90%;
        z-index: 10;
        padding: 30px;
        background-color: rgba(30, 30, 47, 0.8);
        border-radius: 12px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .login-logo {
        text-align: center;
        margin-bottom: 25px;
    }

    .login-logo img {
        max-width: 150px;
        filter: drop-shadow(0 5px 15px rgba(255, 255, 255, 0.2));
    }

    .login-title {
        font-size: 26px;
        font-weight: 600;
        text-align: center;
        margin-bottom: 30px;
        background: linear-gradient(45deg, #4a8cff, #8e54e9);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
    }

    .input-group {
        margin-bottom: 25px;
        position: relative;
    }

    .input-group i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #8e54e9;
        font-size: 18px;
    }

    .form-input {
        width: 100%;
        padding: 15px 15px 15px 45px;
        background-color: rgba(24, 24, 36, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 6px;
        font-size: 16px;
        color: white;
        transition: all 0.3s;
        box-sizing: border-box;
    }

    .form-input:focus {
        border-color: #4a8cff;
        outline: none;
        box-shadow: 0 0 0 3px rgba(74, 140, 255, 0.25);
        background-color: rgba(24, 24, 36, 0.9);
    }

    .form-input::placeholder {
        color: #a0a0a0;
    }

    .form-button {
        display: block;
        width: 100%;
        background: linear-gradient(45deg, #4a8cff, #3a7ce0);
        color: white;
        border: none;
        border-radius: 6px;
        padding: 15px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(74, 140, 255, 0.4);
        margin-top: 10px;
    }

    .form-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(74, 140, 255, 0.6);
    }

    .form-button:active {
        transform: translateY(0);
    }

    .error-message {
        color: #ff5b5b;
        font-size: 14px;
        margin-top: 5px;
        text-align: center;
        padding: 10px;
        background-color: rgba(255, 91, 91, 0.1);
        border-radius: 6px;
        margin-bottom: 15px;
        border-left: 3px solid #ff5b5b;
    }

    .bg-animation {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        z-index: -1;
        overflow: hidden;
    }

    .bg-animation span {
        position: absolute;
        display: block;
        width: 20px;
        height: 20px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: animate 25s linear infinite;
        bottom: -150px;
    }

    .bg-animation span:nth-child(1) {
        left: 10%;
        width: 80px;
        height: 80px;
        animation-delay: 0s;
        animation-duration: 15s;
        background: rgba(74, 140, 255, 0.1);
        box-shadow: 0 0 15px rgba(74, 140, 255, 0.2);
    }

    .bg-animation span:nth-child(2) {
        left: 25%;
        width: 30px;
        height: 30px;
        animation-delay: 2s;
        animation-duration: 20s;
        background: rgba(142, 84, 233, 0.1);
        box-shadow: 0 0 10px rgba(142, 84, 233, 0.2);
    }

    .bg-animation span:nth-child(3) {
        left: 50%;
        width: 60px;
        height: 60px;
        animation-delay: 0s;
        animation-duration: 23s;
        background: rgba(74, 140, 255, 0.1);
        box-shadow: 0 0 12px rgba(74, 140, 255, 0.2);
    }

    .bg-animation span:nth-child(4) {
        left: 75%;
        width: 40px;
        height: 40px;
        animation-delay: 5s;
        animation-duration: 18s;
        background: rgba(142, 84, 233, 0.1);
        box-shadow: 0 0 15px rgba(142, 84, 233, 0.2);
    }

    .bg-animation span:nth-child(5) {
        left: 85%;
        width: 70px;
        height: 70px;
        animation-delay: 0s;
        animation-duration: 25s;
        background: rgba(74, 140, 255, 0.1);
        box-shadow: 0 0 15px rgba(74, 140, 255, 0.2);
    }

    @keyframes animate {
        0% {
            transform: translateY(0) rotate(0deg);
            opacity: 1;
        }

        100% {
            transform: translateY(-1000px) rotate(720deg);
            opacity: 0;
        }
    }

    .company-name {
        position: absolute;
        bottom: 20px;
        left: 0;
        right: 0;
        text-align: center;
        color: rgba(255, 255, 255, 0.5);
        font-size: 14px;
    }
    </style>
</head>

<body>
    <div class="bg-animation">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>

    <div class="login-container">
        <div class="login-logo">
            <img src="{{ asset('assets/img/driver/logo.png') }}" alt="Логотип">
        </div>
        <h1 class="login-title">Вход в систему диспетчера</h1>

        @if(session('error'))
        <div class="error-message">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
        @endif

        <form action="{{ route('dispatcher.login.process') }}" method="POST">
            @csrf
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="username" class="form-input" placeholder="Имя пользователя" required
                    value="{{ old('username') }}">
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" class="form-input" placeholder="Пароль" required>
            </div>
            <button type="submit" class="form-button">
                <i class="fas fa-sign-in-alt"></i> Войти
            </button>
        </form>
    </div>

    <div class="company-name">© {{ date('Y') }} Ош Титан Парк</div>
</body>

</html>