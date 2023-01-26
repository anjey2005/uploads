<?php

namespace App\Events;

use App\Models\Upload;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UploadEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $upload;
    private $event;
    public $msg;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Upload $upload, $event, $msg)
    {
        $this->upload = $upload;
        $this->event = $event;
        $this->msg = $msg;
    }

    // имя события
    public function broadcastAs()
    {
        return $this->event;
    }

    // отправляем только если сообщение не пустое
    public function broadcastWhen()
    {
        return !empty($this->msg);
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // отправка в приватный канал
        return new PrivateChannel('upload.' . $this->upload->user_id);
    }
}
