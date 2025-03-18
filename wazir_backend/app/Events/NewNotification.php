<?php

namespace App\Events;

use App\Models\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NewNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Notification  $notification
     * @return void
     */
    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
        
        Log::info('Создан объект события NewNotification', [
            'notification_id' => $notification->id,
            'type' => $notification->type,
            'title' => $notification->title
        ]);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // Используем канал my-channel вместо notifications
        Log::info('Трансляция уведомления в канал my-channel', [
            'notification_id' => $this->notification->id
        ]);
        return new Channel('my-channel');
    }
    
    /**
     * Получить данные для трансляции.
     *
     * @return array
     */
    public function broadcastWith()
    {
        $data = [
            'notification' => [
                'id' => $this->notification->id,
                'type' => $this->notification->type,
                'title' => $this->notification->title,
                'data' => $this->notification->data,
                'created_at' => $this->notification->created_at
            ]
        ];
        
        Log::info('Подготовлены данные для трансляции уведомления', [
            'notification_id' => $this->notification->id
        ]);
        
        return $data;
    }
    
    /**
     * Название события для трансляции.
     * 
     * @return string
     */
    public function broadcastAs()
    {
        return 'my-event';
    }
}