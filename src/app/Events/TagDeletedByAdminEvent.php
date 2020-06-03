<?php

namespace VCComponent\Laravel\Tag\Events;

// use Illuminate\Broadcasting\Channel;
// use Illuminate\Broadcasting\InteractsWithSockets;
// use Illuminate\Broadcasting\PresenceChannel;
// use Illuminate\Broadcasting\PrivateChannel;
// use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
// use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TagDeletedByAdminEvent
{
    use SerializesModels;

    public function __construct()
    {

    }

    // public function broadcastOn()
    // {
    //     return new PrivateChannel('channel-name');
    // }
}
