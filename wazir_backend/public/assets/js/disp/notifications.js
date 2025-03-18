// notifications.js - файл для обработки системы уведомлений

// Инициализация количества уведомлений и звука
document.addEventListener('DOMContentLoaded', function () {
    console.log('Initializing notifications system...');

    // Инициализация переменных
    const notificationsIcon = document.getElementById('notifications-icon');
    const notificationsDropdown = document.getElementById('notifications-dropdown');
    const notificationsBadge = document.getElementById('notifications-badge');
    const notificationsList = document.getElementById('notifications-list');
    const markAllReadBtn = document.getElementById('mark-all-read');

    // Загружаем звук уведомления
    let notificationSound = new Audio();
    notificationSound.src = '/assets/sounds/audio_c8a41cd428.mp3';
    notificationSound.preload = 'auto';

    console.log('Loading sound from:', notificationSound.src);

    // Звук не будет работать если не добавить обработку ошибок
    notificationSound.addEventListener('error', (e) => {
        console.warn('Ошибка загрузки звука, используем резервный URL:', e);
        // Пробуем использовать резервный звук
        notificationSound.src = 'https://cdn.pixabay.com/download/audio/2021/08/04/audio_c8a41cd428.mp3';
        notificationSound.load();
    });

    // Проверка готовности звука
    notificationSound.addEventListener('canplaythrough', () => {
        console.log('Звук уведомления успешно загружен и готов к воспроизведению');
    });

    // Проверка и настройка Pusher WebSockets
    if (typeof Pusher !== 'undefined') {
        try {
            console.log('Initializing Pusher WebSockets...');

            // Инициализация Pusher с новым ключом
            const pusher = new Pusher('f63d33c94e33b1f539e8', {
                cluster: 'ap2',
                forceTLS: true,
                enabledTransports: ['ws', 'wss']
            });

            pusher.connection.bind('connected', function () {
                console.log('%c Успешно подключились к Pusher!', 'background: #4CAF50; color: white; padding: 5px;');
            });

            pusher.connection.bind('error', function (err) {
                console.error('%c Ошибка подключения Pusher:', 'background: #F44336; color: white; padding: 5px;', err);
            });

            const channel = pusher.subscribe('my-channel');

            channel.bind('pusher:subscription_succeeded', function () {
                console.log('%c Успешно подписались на канал уведомлений', 'background: #2196F3; color: white; padding: 5px;');
            });

            channel.bind('pusher:subscription_error', function (error) {
                console.error('%c Ошибка подписки на канал:', 'background: #F44336; color: white; padding: 5px;', error);
            });

            // Обработка новых уведомлений - подписываемся на канал my-channel и событие my-event
            channel.bind('my-event', function (data) {
                console.log('%c Получено уведомление через WebSocket:', 'background: #2196F3; color: white; padding: 5px;', data);

                // Увеличиваем счетчик уведомлений
                updateNotificationCount();

                // Проигрываем звук
                playNotificationSound();

                // Показываем тост
                if (data && data.notification) {
                    showToast(data.notification);

                    // Добавляем уведомление в выпадающий список
                    addNotificationToList(data.notification);
                } else {
                    console.warn('Получены неполные данные уведомления:', data);
                }

                // Добавляем анимацию к бейджу
                if (notificationsBadge) {
                    notificationsBadge.classList.add('animate');
                    setTimeout(() => {
                        notificationsBadge.classList.remove('animate');
                    }, 500);
                }
            });

            // Также слушаем прямое имя события (на случай, если оно передается без пространства имен)
            channel.bind('NewNotification', function (data) {
                console.log('%c Получено уведомление через прямое имя события:', 'background: #673AB7; color: white; padding: 5px;', data);

                // Такая же обработка, как и выше
                updateNotificationCount();
                playNotificationSound();

                if (data && data.notification) {
                    showToast(data.notification);
                    addNotificationToList(data.notification);
                }

                if (notificationsBadge) {
                    notificationsBadge.classList.add('animate');
                    setTimeout(() => {
                        notificationsBadge.classList.remove('animate');
                    }, 500);
                }
            });
        } catch (error) {
            console.error('Ошибка при инициализации Pusher:', error);
        }
    } else {
        console.warn('Pusher не загружен. WebSocket уведомления будут недоступны.');
    }

    // Получаем непрочитанные уведомления при загрузке
    fetchUnreadNotifications();

    // Показать/скрыть выпадающий список при клике на иконку
    if (notificationsIcon && notificationsDropdown) {
        notificationsIcon.addEventListener('click', function (e) {
            e.preventDefault();
            notificationsDropdown.classList.toggle('show');
        });

        // Закрыть выпадающий список при клике вне его
        document.addEventListener('click', function (e) {
            if (!notificationsDropdown.contains(e.target) && !notificationsIcon.contains(e.target)) {
                notificationsDropdown.classList.remove('show');
            }
        });
    }

    // Отметить все как прочитанные
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', function (e) {
            e.preventDefault();
            markAllNotificationsAsRead();
        });
    }

    // Добавим функцию для ручного тестирования уведомлений
    window.testNotification = function () {
        console.log('Testing notification...');

        // Тестовое уведомление
        const testNotification = {
            id: Date.now(),
            type: 'balance',
            title: 'Тестовое уведомление',
            data: { message: 'Это тестовое уведомление для проверки функциональности.' },
            created_at: new Date().toISOString()
        };

        // Проигрываем звук
        playNotificationSound();

        // Показываем тост
        showToast(testNotification);

        // Добавляем в список
        addNotificationToList(testNotification);

        // Обновляем счетчик
        updateNotificationCount();
    };

    // Периодическая проверка новых уведомлений (резервный механизм)
    setInterval(fetchUnreadNotifications, 30000); // каждые 30 секунд

    console.log('Notification system initialized!');
});

// Функция для воспроизведения звука уведомления
function playNotificationSound() {
    console.log('Воспроизводим звук уведомления...');

    try {
        // Создаем новый экземпляр Audio каждый раз
        const sound = new Audio();
        sound.volume = 1.0;

        // Сначала пробуем локальный файл
        sound.src = '/assets/sounds/audio_c8a41cd428.mp3';

        // Обработчик ошибок
        sound.addEventListener('error', (e) => {
            console.warn('Ошибка при воспроизведении локального звука:', e);
            console.log('Пробуем резервный источник...');

            sound.src = 'https://cdn.pixabay.com/download/audio/2021/08/04/audio_c8a41cd428.mp3';
            sound.play().catch(err => {
                console.error('Не удалось воспроизвести резервный звук, пробуем третий вариант:', err);

                sound.src = 'https://zvukogram.com/mp3/cats/906/new_message_notice.mp3';
                sound.play().catch(lastErr => {
                    console.error('Все попытки воспроизведения звука не удались:', lastErr);
                });
            });
        });

        // Попытка воспроизведения
        const playPromise = sound.play();

        if (playPromise !== undefined) {
            playPromise.then(() => {
                console.log('Звук успешно воспроизводится');
            }).catch(error => {
                console.warn('Ошибка воспроизведения звука:', error);

                // Повторная попытка после взаимодействия с пользователем
                document.addEventListener('click', function playAudioOnUserInteraction() {
                    sound.play();
                    document.removeEventListener('click', playAudioOnUserInteraction);
                }, { once: true });
            });
        }
    } catch (error) {
        console.error('Критическая ошибка при воспроизведении звука:', error);
    }
}

// Получение количества непрочитанных уведомлений
function fetchUnreadNotifications() {
    fetch('/backend/disp/api/notifications/unread')
        .then(response => {
            if (!response.ok) {
                throw new Error('Ошибка API: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            updateNotificationCount(data.count);

            // Заполняем список уведомлений
            const notificationsList = document.getElementById('notifications-list');
            if (!notificationsList) return;

            // Очищаем текущий список
            notificationsList.innerHTML = '';

            if (data.notifications.length === 0) {
                notificationsList.innerHTML = '<div class="notification-empty">У вас нет новых уведомлений</div>';
            } else {
                data.notifications.forEach(notification => {
                    addNotificationToList(notification);
                });
            }
        })
        .catch(error => {
            console.error('Ошибка при получении уведомлений:', error);
            // Показываем сообщение об ошибке
            const notificationsList = document.getElementById('notifications-list');
            if (notificationsList) {
                notificationsList.innerHTML = '<div class="notification-empty">Не удалось загрузить уведомления</div>';
            }
        });
}

// Обновление счетчика уведомлений
function updateNotificationCount(count) {
    const notificationsBadge = document.getElementById('notifications-badge');
    if (!notificationsBadge) return;

    if (count === undefined) {
        // Если count не передан, увеличиваем текущее значение на 1
        const currentCount = parseInt(notificationsBadge.textContent) || 0;
        count = currentCount + 1;
    }

    notificationsBadge.textContent = count;

    if (count > 0) {
        notificationsBadge.style.display = 'block';
    } else {
        notificationsBadge.style.display = 'none';
    }
}

// Показать уведомление в виде тоста
function showToast(notification) {
    console.log('Показываем всплывающее уведомление:', notification);

    if (typeof Toastify === 'undefined') {
        console.error('Toastify не загружен! Проверьте подключение библиотеки в layout.blade.php');
        return;
    }

    // Определяем класс и иконку в зависимости от типа уведомления
    let toastClass = '';
    let iconHtml = '';
    let bgColor = '';

    switch (notification.type) {
        case 'driver_application':
            toastClass = 'info';
            iconHtml = '<i class="fas fa-user-plus"></i>';
            bgColor = 'linear-gradient(135deg, #2b5876 0%, #4e4376 100%)';
            break;
        case 'balance':
            toastClass = 'success';
            iconHtml = '<i class="fas fa-money-bill-wave"></i>';
            bgColor = 'linear-gradient(135deg, #1d976c 0%, #93f9b9 100%)';
            break;
        case 'deletion':
            toastClass = 'warning';
            iconHtml = '<i class="fas fa-exclamation-triangle"></i>';
            bgColor = 'linear-gradient(135deg, #f2994a 0%, #f2c94c 100%)';
            break;
        default:
            toastClass = 'info';
            iconHtml = '<i class="fas fa-bell"></i>';
            bgColor = 'linear-gradient(135deg, #2b5876 0%, #4e4376 100%)';
    }

    // Получаем текст сообщения
    let messageText = '';
    if (notification.data && notification.data.message) {
        messageText = notification.data.message;
    } else if (notification.message) {
        messageText = notification.message;
    } else {
        messageText = notification.title || 'Новое уведомление';
    }

    // Создаем заголовок уведомления
    let titleText = notification.title || 'Уведомление';

    // Создаем HTML-структуру сообщения
    const toastHtml = `
    <div class="toast-wrapper">
        <div class="toast-icon">${iconHtml}</div>
        <div class="toast-content">
            <div class="toast-title">${titleText}</div>
            <div class="toast-message">${messageText}</div>
        </div>
    </div>`;

    // Проигрываем звук уведомления 
    playNotificationSound();

    // Показываем тост с выводом отладочной информации
    try {
        Toastify({
            node: (() => {
                const div = document.createElement('div');
                div.innerHTML = toastHtml;
                return div.firstChild;
            })(),
            duration: 7000,
            close: true,
            gravity: "top",
            position: "right",
            className: `custom-toast ${toastClass}`,
            stopOnFocus: true,
            style: {
                background: bgColor,
                boxShadow: '0 3px 10px rgba(0, 0, 0, 0.3)',
                borderRadius: '8px',
                padding: '12px',
            },
            onClick: function () {
                markNotificationAsRead(notification.id);
            }
        }).showToast();

        console.log('Toast отображен успешно');
    } catch (error) {
        console.error('Ошибка при отображении Toast:', error);
    }
}

// Добавление уведомления в выпадающий список
function addNotificationToList(notification) {
    const notificationsList = document.getElementById('notifications-list');
    if (!notificationsList) return;

    // Удаляем сообщение "Нет новых уведомлений", если оно есть
    const emptyNotification = notificationsList.querySelector('.notification-empty');
    if (emptyNotification) {
        notificationsList.removeChild(emptyNotification);
    }

    const timeAgo = formatTimeAgo(notification.created_at);

    // Получаем текст сообщения - он может быть в разных местах в зависимости от структуры
    let messageText = '';
    if (notification.data && notification.data.message) {
        messageText = notification.data.message;
    } else if (notification.message) {
        messageText = notification.message;
    } else {
        messageText = notification.title || 'Новое уведомление';
    }

    const notificationElement = document.createElement('div');
    notificationElement.className = 'notification-item' + (notification.read_at ? '' : ' unread');
    notificationElement.dataset.id = notification.id;
    notificationElement.innerHTML = `
        <div class="notification-content">${messageText}</div>
        <div class="notification-time">${timeAgo}</div>
    `;

    notificationElement.addEventListener('click', function () {
        markNotificationAsRead(notification.id);
    });

    // Вставляем элемент в начало списка
    if (notificationsList.firstChild) {
        notificationsList.insertBefore(notificationElement, notificationsList.firstChild);
    } else {
        notificationsList.appendChild(notificationElement);
    }
}

// Форматирование времени (например, "5 минут назад")
function formatTimeAgo(datetime) {
    if (!datetime) return 'только что';

    const now = new Date();
    const date = new Date(datetime);
    const diffMs = now - date;

    const seconds = Math.floor(diffMs / 1000);
    const minutes = Math.floor(seconds / 60);
    const hours = Math.floor(minutes / 60);
    const days = Math.floor(hours / 24);

    if (days > 0) {
        return days + ' д. назад';
    } else if (hours > 0) {
        return hours + ' ч. назад';
    } else if (minutes > 0) {
        return minutes + ' мин. назад';
    } else {
        return 'только что';
    }
}

// Отметить одно уведомление как прочитанное
function markNotificationAsRead(id) {
    fetch(`/backend/disp/api/notifications/${id}/read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Ошибка API: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            // Обновляем стиль уведомления (убираем класс unread)
            const notificationElement = document.querySelector(`.notification-item[data-id="${id}"]`);
            if (notificationElement) {
                notificationElement.classList.remove('unread');
            }

            // Обновляем счетчик
            fetchUnreadNotifications();
        })
        .catch(error => {
            console.error('Ошибка при отметке уведомления как прочитанного:', error);
        });
}

// Отметить все уведомления как прочитанные
function markAllNotificationsAsRead() {
    fetch('/backend/disp/api/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Ошибка API: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            // Обновляем стиль всех уведомлений
            const unreadNotifications = document.querySelectorAll('.notification-item.unread');
            unreadNotifications.forEach(element => {
                element.classList.remove('unread');
            });

            // Обновляем счетчик (сбрасываем в 0)
            updateNotificationCount(0);
        })
        .catch(error => {
            console.error('Ошибка при отметке всех уведомлений как прочитанных:', error);
        });
}

// Добавьте этот код в notifications.js
window.sendTestNotification = function () {
    fetch('/test-notification')
        .then(response => response.text())
        .then(data => {
            console.log('Результат отправки тестового уведомления:', data);
        })
        .catch(error => {
            console.error('Ошибка при отправке тестового уведомления:', error);
        });
}; 