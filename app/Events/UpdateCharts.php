<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateCharts implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $command;
    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($command, $data)
    {
        $this->command = $command;
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('command.'.$this->command->id);
    }
}
