-- Создание базы данных такси-сервиса на основе файлов проекта

-- Создание таблицы водителей (drivers)
CREATE TABLE drivers (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    city VARCHAR(100),
    license_number VARCHAR(100) NOT NULL,
    license_issue_date DATE,
    license_expiry_date DATE,
    personal_number VARCHAR(17),
    date_of_birth DATE,
    
    -- Пути к документам
    passport_front VARCHAR(255),
    passport_back VARCHAR(255),
    license_front VARCHAR(255),
    license_back VARCHAR(255),
    
    -- Информация об автомобиле
    car_brand VARCHAR(100),
    car_model VARCHAR(100),
    car_color VARCHAR(50),
    car_year YEAR,
    service_type VARCHAR(50),
    category VARCHAR(50),
    tariff VARCHAR(50),
    license_plate VARCHAR(50),
    
    -- Булевы поля (true/false)
    has_nakleyka BOOLEAN DEFAULT FALSE,
    has_lightbox BOOLEAN DEFAULT FALSE,
    has_child_seat BOOLEAN DEFAULT FALSE,
    is_confirmed BOOLEAN DEFAULT FALSE,
    
    -- Дополнительная информация об автомобиле
    vin VARCHAR(50),
    body_number VARCHAR(50),
    sts VARCHAR(50),
    callsign VARCHAR(50),
    transmission VARCHAR(50),
    boosters VARCHAR(50),
    child_seat VARCHAR(50),
    parking_car VARCHAR(50),
    tariff_extra VARCHAR(50),
    
    -- Пути к фотографиям автомобиля
    car_front VARCHAR(255),
    car_back VARCHAR(255),
    car_left VARCHAR(255),
    car_right VARCHAR(255),
    interior_front VARCHAR(255),
    interior_back VARCHAR(255),
    
    -- Статус анкеты
    survey_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    rejection_reason TEXT,
    approved_at TIMESTAMP,
    
    -- Финансовая информация
    balance DECIMAL(10, 2) DEFAULT 0,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Создание таблицы автомобилей водителей (driver_vehicles)
CREATE TABLE driver_vehicles (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    driver_id BIGINT NOT NULL,
    vehicle_brand VARCHAR(100),
    vehicle_model VARCHAR(100),
    vehicle_color VARCHAR(50),
    vehicle_year YEAR,
    vehicle_state_number VARCHAR(50),
    chassis_number VARCHAR(100),
    sts VARCHAR(100),
    stir VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (driver_id) REFERENCES drivers(id) ON DELETE CASCADE
);

-- Создание таблицы транзакций (transactions)
CREATE TABLE transactions (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    driver_id BIGINT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    description TEXT,
    transaction_type ENUM('deposit', 'withdrawal', 'payment', 'refund') DEFAULT 'deposit',
    status ENUM('completed', 'pending', 'failed') DEFAULT 'completed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (driver_id) REFERENCES drivers(id) ON DELETE CASCADE
);

-- Создание таблицы заказов (orders)
CREATE TABLE orders (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    driver_id BIGINT,
    client_name VARCHAR(255),
    client_phone VARCHAR(50),
    pickup_address VARCHAR(255),
    destination_address VARCHAR(255),
    price DECIMAL(10, 2),
    status ENUM('pending', 'accepted', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (driver_id) REFERENCES drivers(id) ON DELETE SET NULL
);

-- Добавление тестовых данных для демонстрации
INSERT INTO drivers (full_name, phone, license_number, car_brand, car_model, car_color, has_nakleyka, has_lightbox, has_child_seat, survey_status, balance)
VALUES 
('Иванов Иван Иванович', '+7 (900) 123-45-67', 'AB123456', 'Toyota', 'Camry', 'Черный', TRUE, FALSE, TRUE, 'approved', 5000.00),
('Петров Петр Петрович', '+7 (900) 987-65-43', 'CD789012', 'Hyundai', 'Solaris', 'Белый', FALSE, TRUE, FALSE, 'approved', 3500.50),
('Сидоров Алексей Павлович', '+7 (900) 555-11-22', 'EF345678', 'Kia', 'Rio', 'Серый', TRUE, TRUE, FALSE, 'pending', 0.00);

-- Добавление тестовых транзакций
INSERT INTO transactions (driver_id, amount, description, transaction_type, status)
VALUES
(1, 5000.00, 'Начальное пополнение баланса', 'deposit', 'completed'),
(2, 4000.00, 'Начальное пополнение баланса', 'deposit', 'completed'),
(2, -500.50, 'Оплата комиссии', 'withdrawal', 'completed');

-- Добавление тестовых заказов
INSERT INTO orders (driver_id, client_name, client_phone, pickup_address, destination_address, price, status)
VALUES
(1, 'Александр', '+7 (900) 111-22-33', 'ул. Ленина, 10', 'ул. Пушкина, 15', 350.00, 'completed'),
(2, 'Марина', '+7 (900) 444-55-66', 'пр. Мира, 25', 'ул. Гагарина, 7', 420.00, 'in_progress'),
(NULL, 'Дмитрий', '+7 (900) 777-88-99', 'ул. Советская, 3', 'пл. Победы, 1', 280.00, 'pending');
