<?php
/**
 * Скрипт для инициализации базы данных такси-сервиса
 * Этот файл создает соединение с базой данных и выполняет SQL-скрипт для создания таблиц и добавления тестовых данных
 */

// Параметры подключения к базе данных
$host = '31.128.40.174';      // Хост базы данных
$dbname = 'wazir_backend';   // Имя базы данных (создайте её заранее)
$username = 'admin';       // Имя пользователя базы данных
$password = 'wYHpgtHTiwbW7S~d*e';           // Пароль пользователя базы данных (пустой для локальной разработки)

try {
    // Создаем соединение с базой данных
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    
    // Устанавливаем режим обработки ошибок
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Создаем базу данных, если она не существует
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$dbname`");
    
    echo "✅ База данных '$dbname' создана или уже существует.\n";
    
    // Читаем SQL-скрипт из файла
    $sql = file_get_contents('create_database.sql');
    
    // Выполняем SQL-скрипт
    $pdo->exec($sql);
    
    echo "✅ Структура базы данных успешно создана!\n";
    echo "✅ Тестовые данные успешно добавлены!\n";
    
    echo "\nСтатистика базы данных:\n";
    
    // Выводим количество записей в таблицах
    $tables = ['drivers', 'driver_vehicles', 'transactions', 'orders'];
    
    foreach ($tables as $table) {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        echo "- Таблица '$table': $count записей\n";
    }
    
    echo "\n✅ База данных готова к использованию!\n";
    
} catch (PDOException $e) {
    // Выводим ошибку, если что-то пошло не так
    echo "❌ Ошибка: " . $e->getMessage() . "\n";
    exit(1);
} 