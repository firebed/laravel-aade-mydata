<?php

return [
    'username' => env('MYDATA_USERNAME'),
    'password' => env('MYDATA_PASSWORD'),
    'environment' => env('MYDATA_ENVIRONMENT', 'dev'),
    'channel' => env('MYDATA_CHANNEL', 'erp'),
    'timeout' => env('MYDATA_TIMEOUT', 10),
];