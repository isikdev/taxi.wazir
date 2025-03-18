<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>404 - Страница не найдена | Ош Титан Парк</title>
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

        .error-container {
            position: relative;
            max-width: 550px;
            width: 90%;
            text-align: center;
            z-index: 10;
            padding: 30px;
            background-color: rgba(30, 30, 47, 0.8);
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .error-code {
            font-size: 120px;
            font-weight: 700;
            margin: 0;
            background: linear-gradient(45deg, #4a8cff, #8e54e9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1;
            text-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
        }

        .error-title {
            font-size: 28px;
            font-weight: 600;
            margin: 20px 0;
        }

        .error-message {
            font-size: 18px;
            color: #ccc;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .btn-back {
            display: inline-block;
            background: linear-gradient(45deg, #4a8cff, #3a7ce0);
            color: white;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(74, 140, 255, 0.4);
            border: none;
            cursor: pointer;
        }

        .btn-back:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(74, 140, 255, 0.6);
        }

        .btn-back:active {
            transform: translateY(0);
        }

        .btn-back i {
            margin-right: 8px;
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

        .logo {
            margin-bottom: 20px;
        }

        .logo img {
            max-width: 150px;
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

    <div class="error-container">
        <div class="logo">
            <img src="{{ asset('assets/img/driver/logo.png') }}" alt="Логотип">
        </div>
        <h1 class="error-code">404</h1>
        <h2 class="error-title">Страница не найдена</h2>
        <p class="error-message">
            {{ isset($message) ? $message : 'Запрашиваемая страница не существует или была перемещена.' }}
        </p>
        <a href="{{ route('dispatcher.login') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Вернуться на страницу входа
        </a>
    </div>
</body>

</html> 