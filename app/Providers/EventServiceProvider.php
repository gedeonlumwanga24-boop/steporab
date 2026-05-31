<?php

namespace App\Providers;

use App\Contracts\PaymentGatewayInterface;
use App\Events\OrderCancelled;
use App\Events\OrderPlaced;
use App\Events\ProductOutOfStock;
use App\Listeners\LogOrderCancellation;
use App\Listeners\NotifyLowStock;
use App\Listeners\SendOrderConfirmation;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        OrderPlaced::class => [
            SendOrderConfirmation::class,
        ],
        OrderCancelled::class => [
            LogOrderCancellation::class,
        ],
        ProductOutOfStock::class => [
            NotifyLowStock::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
