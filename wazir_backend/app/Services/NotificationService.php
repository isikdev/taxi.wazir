<?php

namespace App\Services;

use App\Models\Notification;
use App\Events\NewNotification;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    /**
     * Создать новое уведомление для пользователя
     *
     * @param int|null $userId ID пользователя или null для общего уведомления
     * @param string $type Тип уведомления
     * @param string $title Заголовок уведомления
     * @param array $data Данные уведомления
     * @return \App\Models\Notification
     */
    public function create($userId, $type, $title, $data = [])
    {
        $notification = Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'data' => $data,
        ]);
        
        try {
            // Вызываем событие для WebSockets немедленно
            broadcast(new NewNotification($notification))->toOthers();
            
            // Для отладки
            \Log::info('Отправлено уведомление через broadcast (сервис)', [
                'notification_id' => $notification->id,
                'type' => $type,
                'title' => $title,
                'user_id' => $userId
            ]);
        } catch (\Exception $e) {
            \Log::error('Ошибка при отправке уведомления через broadcast (сервис)', [
                'error' => $e->getMessage(),
                'notification_id' => $notification->id
            ]);
        }
        
        return $notification;
    }
    
    /**
     * Создать уведомление для текущего пользователя
     *
     * @param string $type Тип уведомления
     * @param string $title Заголовок уведомления
     * @param array $data Данные уведомления
     * @return \App\Models\Notification|null
     */
    public function createForCurrentUser($type, $title, $data = [])
    {
        $user = Auth::user();
        
        if (!$user) {
            return null;
        }
        
        return $this->create($user->id, $type, $title, $data);
    }
    
    /**
     * Создать общее уведомление (не привязанное к пользователю)
     *
     * @param string $type Тип уведомления
     * @param string $title Заголовок уведомления
     * @param array $data Данные уведомления
     * @return \App\Models\Notification
     */
    public function createGlobal($type, $title, $data = [])
    {
        return $this->create(null, $type, $title, $data);
    }
    
    /**
     * Отметить уведомление как прочитанное
     *
     * @param int $id ID уведомления
     * @return bool
     */
    public function markAsRead($id)
    {
        $notification = Notification::find($id);
        
        if (!$notification) {
            return false;
        }
        
        $notification->read_at = now();
        return $notification->save();
    }
    
    /**
     * Отметить все уведомления пользователя как прочитанные
     *
     * @param int|null $userId ID пользователя или null для текущего пользователя
     * @return int Количество обновленных уведомлений
     */
    public function markAllAsRead($userId = null)
    {
        if ($userId === null) {
            $userId = Auth::id();
        }
        
        if (!$userId) {
            return 0;
        }
        
        return Notification::where('user_id', $userId)
                         ->whereNull('read_at')
                         ->update(['read_at' => now()]);
    }
} 