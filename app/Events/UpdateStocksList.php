<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Stock;

class UpdateStocksList implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $command;
    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($command)
    {
        $this->command = $command;
        $this->data =  Stock::where('on_the_exchange', true)->get();
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
