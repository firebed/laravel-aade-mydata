```dotenv
MYDATA_ENV=dev
#MYDATA_ENV=prod
MYDATA_USERNAME=
MYDATA_PASSWORD=
MYDATA_TIMEOUT=10
```

```php
use Firebed\LaravelAadeMyData\MyData;
use Firebed\AadeMyData\Models\Invoice;

$myDATA = resolve(MyData::class);
$myDATA->sendInvoices(new Invoice());

```