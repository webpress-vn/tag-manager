<?php

namespace VCComponent\Laravel\Tag\Events;

// use Illuminate\Broadcasting\Channel;
// use Illuminate\Broadcasting\InteractsWithSockets;
// use Illuminate\Broadcasting\PresenceChannel;
// use Illuminate\Broadcasting\PrivateChannel;
// use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
// use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TagUpdatedByAdminEvent
{
    use SerializesModels;

    public $tag;
    public function __construct($tag)
    {
        $this->tag = $tag;
    }

    // public function broadcastOn()
    // {
    //     return new PrivateChannel('channel-name');
    // }
}
