<?php

namespace Firebed\LaravelAadeMyData;

use Illuminate\Support\ServiceProvider;

class AadeMyDataServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/mydata.php', 'mydata');
        
        $this->app->singleton(MyData::class, function() {
            $config = $this->app->make('config');
            
            return new MyData(
                username: $config->get('mydata.username'),
                password: $config->get('mydata.password'),
                environment: $config->get('mydata.environment'),
                channel: $config->get('mydata.channel'),
                connectionTimeout: $config->get('mydata.timeout')
            );
        });
    }
}