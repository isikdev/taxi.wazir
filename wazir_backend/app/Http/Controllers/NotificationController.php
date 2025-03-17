<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Events\NewNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Получить непрочитанные уведомления
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnread()
    {
        $notifications = Notification::where('read', false)
                                    ->orderBy('created_at', 'desc')
                                    ->get();
                                    
        return response()->json([
            'count' => $notifications->count(),
            'notifications' => $notifications
        ]);
    }
    
    /**
     * Получить все уведомления
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $notifications = Notification::orderBy('created_at', 'desc')
                                    ->take(50)
                                    ->get();
                                    
        return response()->json([
            'notifications' => $notifications
        ]);
    }
    
    /**
     * Отметить одно уведомление как прочитанное
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->read = true;
        $notification->save();
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Отметить все уведомления как прочитанные
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllAsRead()
    {
        Notification::where('read', false)
                  ->update(['read' => true]);
                  
        return response()->json(['success' => true]);
    }
    
    /**
     * Создать новое уведомление
     *
     * @param string $type
     * @param string $title
     * @param string $message
     * @param array $data
     * @return \App\Models\Notification
     */
    public function create($type, $title, $message, $data = [])
    {
        $notification = Notification::create([
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'read' => false
        ]);
        
        // Вызываем событие для WebSockets
        event(new NewNotification($notification));
        
        return $notification;
    }
}
