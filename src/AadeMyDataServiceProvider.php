<?php

namespace Firebed\LaravelAadeMyData;

use Firebed\AadeMyData\Http\MyDataRequest;
use Illuminate\Support\ServiceProvider;

class AadeMyDataServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/mydata.php', 'mydata');

        $config = $this->app->make('config');
        MyDataRequest::setCredentials($config->get('mydata.username'), $config->get('mydata.password'));
        MyDataRequest::setEnvironment($config->get('mydata.environment'), $config->get('mydata.channel') === 'provider');
        MyDataRequest::setConnectionTimeout($config->get('mydata.timeout'));
    }
}