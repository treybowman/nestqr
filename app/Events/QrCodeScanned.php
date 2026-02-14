<?php

namespace App\Events;

use App\Models\QrSlot;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QrCodeScanned
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  QrSlot  $qrSlot  The QR slot that was scanned.
     * @param  array  $requestData  Data from the scan request (ip, user_agent, referrer, etc.).
     */
    public function __construct(
        public readonly QrSlot $qrSlot,
        public readonly array $requestData,
    ) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->qrSlot->user_id),
        ];
    }
}
