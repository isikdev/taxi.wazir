// notifications.js - файл для обработки системы уведомлений

// Инициализация количества уведомлений и звука
document.addEventListener('DOMContentLoaded', function () {
    // Инициализация переменных
    const notificationsIcon = document.getElementById('notifications-icon');
    const notificationsDropdown = document.getElementById('notifications-dropdown');
    const notificationsBadge = document.getElementById('notifications-badge');
    const notificationsList = document.getElementById('notifications-list');
    const markAllReadBtn = document.getElementById('mark-all-read');

    // Загружаем звук уведомления
    let notificationSound = new Audio();
    notificationSound.src = 'https://zvukogram.com/mp3/cats/906/new_message_notice.mp3';
    notificationSound.load();

    // Звук не будет работать если не добавить обработку ошибок
    notificationSound.addEventListener('error', (e) => {
        console.warn('Ошибка загрузки звука, используем резервный URL:', e);
        // Пробуем использовать резервный звук
        notificationSound.src = 'https://cdn.pixabay.com/download/audio/2021/08/04/audio_c8a41cd428.mp3';
        notificationSound.load();
    });

    // Проверка доступности WebSockets
    if (typeof Pusher !== 'undefined') {
        try {
            // Инициализация Pusher с предоставленным ключом
            const pusher = new Pusher('4ts3aIX8F1orXMIidsijQtTiYR9u262FNLOMoma_JE8', {
                cluster: 'eu',
                forceTLS: true
            });

            const channel = pusher.subscribe('notifications');

            // Обработка новых уведомлений
            channel.bind('App\\Events\\NewNotification', function (data) {
                // Увеличиваем счетчик уведомлений
                updateNotificationCount();

                // Проигрываем звук
                notificationSound.play().catch(error => {
                    console.warn('Не удалось воспроизвести звук уведомления:', error);
                });

                // Показываем тост
                showToast(data.notification);

                // Добавляем уведомление в выпадающий список
                addNotificationToList(data.notification);

                // Добавляем анимацию к бейджу
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
});

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
    if (typeof Toastify === 'undefined') {
        console.warn('Toastify не загружен. Всплывающие уведомления будут недоступны.');
        return;
    }

    // Определяем класс в зависимости от типа уведомления
    let toastClass = '';
    switch (notification.type) {
        case 'driver_application':
            toastClass = 'info';
            break;
        case 'balance':
            toastClass = 'success';
            break;
        case 'deletion':
            toastClass = 'warning';
            break;
        default:
            toastClass = 'info';
    }

    // Получаем текст сообщения - он может быть в разных местах в зависимости от структуры
    let messageText = '';
    if (notification.data && notification.data.message) {
        messageText = notification.data.message;
    } else if (notification.message) {
        messageText = notification.message;
    } else {
        messageText = notification.title || 'Новое уведомление';
    }

    Toastify({
        text: messageText,
        duration: 5000,
        close: true,
        gravity: "top",
        position: "right",
        className: toastClass,
        onClick: function () {
            markNotificationAsRead(notification.id);
        }
    }).showToast();
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