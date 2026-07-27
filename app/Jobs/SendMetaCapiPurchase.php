<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\Tracking\MetaCapiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMetaCapiPurchase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 10; // seconds between retries

    public function __construct(
        protected int $orderId,
        protected ?string $clientIp,
        protected ?string $userAgent,
        protected ?string $fbp,
        protected ?string $fbc,
    ) {
    }

    public function handle(): void
    {
        $order = Order::find($this->orderId);

        if (!$order) {
            return;
        }

        MetaCapiService::sendPurchase($order, $this->clientIp, $this->userAgent, $this->fbp, $this->fbc);
    }
}