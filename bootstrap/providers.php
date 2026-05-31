<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\RepositoryServiceProvider::class,
    ...(env('APP_ENV') === 'local' ? [App\Providers\TelescopeServiceProvider::class] : []),
];
