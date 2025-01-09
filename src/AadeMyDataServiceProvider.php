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
                $config->get('mydata.username'),
                $config->get('mydata.password'),
                $config->get('mydata.environment'),
                $config->get('mydata.channel')
            );
        });
    }
}