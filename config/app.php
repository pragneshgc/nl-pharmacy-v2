<?php

use Illuminate\Support\Facades\Facade;

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'HRH Pharmacist'),

    /*
    |--------------------------------------------------------------------------
    | Application Version
    |--------------------------------------------------------------------------
    |
    | This value is the version of the application
    |
    */

    'version' => env('APP_VERSION', '0.9.0.6'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),
    'shipping' => env('APP_SHIPPING', 'http://localhost'),
    'inventory' => env('APP_INVENTORY', 'http://localhost'),
    'esa' => env('APP_ESA', 'http://localhost'),

    'asset_url' => env('ASSET_URL'),


    /**
     * General configuration
     */

    'currency' => env('APP_CURRENCY', '£'), //the currency displayed in the app (app only supports 1 currency so this is just a display value)
    'clientid_exception' => env('APP_CLIENTID_EXCEPTION', 0), //which client is exempt from shipping charges
    'clientid_exception_bims' => env('APP_CLIENTID_EXCEPTION_BIMS', 9), //BIMS client is exempt from shipping charges 
    'countryid_exception' => env('APP_COUNTRYID_EXCEPTION', 0), //which country is exempt from shipping charges
    'delivery_companies' => explode(',', env('APP_DELIVERY_COMPANIES', 'dpd,dhl,rml,ups')), //delivery companies which will be available and visible in the system
    'office_ip' => env('APP_OFFICE_IP', '81.134.242.4'), //the ip address of the office network
    'show_logo' => env('APP_SHOW_LOGO', true), //whether to show the logo in the prescription pdf
    'registration_style' => env('APP_REGISTRATION_STYLE', 'GB'), //what registration details to we show on the Prescription PDF

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => env('APP_TIMEZONE', 'Europe/Paris'),

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Faker Locale
    |--------------------------------------------------------------------------
    |
    | This locale will be used by the Faker PHP library when generating fake
    | data for your database seeds. For example, this will be used to get
    | localized telephone numbers, street address information and more.
    |
    */

    'faker_locale' => env('FAKER_LOCALE', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | These configuration options determine the driver used to determine and
    | manage Laravel's "maintenance mode" status. The "cache" driver will
    | allow maintenance mode to be controlled across multiple machines.
    |
    | Supported drivers: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => 'file',
        // 'store'  => 'redis',
    ],

    'chrome' => env('CHROME_PATH', ''),

    'wk_pdf_binary' => env('WKHTML_PDF_BINARY'),
    'wk_img_binary' => env('WKHTML_IMG_BINARY'),

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
             * Laravel Framework Service Providers...
             */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
             * Package Service Providers...
             */
        Maatwebsite\Excel\ExcelServiceProvider::class,
        Milon\Barcode\BarcodeServiceProvider::class,
        Barryvdh\DomPDF\ServiceProvider::class,
        PragmaRX\Google2FALaravel\ServiceProvider::class,
        Sichikawa\LaravelSendgridDriver\SendgridTransportServiceProvider::class,
        App\Providers\AzureStorageServiceProvider::class,
        /*
             * Application Service Providers...
             */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => Facade::defaultAliases()->merge([
        // 'ExampleClass' => App\Example\ExampleClass::class,
        //'DomPDF' => Barryvdh\DomPDF\Facade::class,
        'Excel' => Maatwebsite\Excel\Facades\Excel::class,
        'DNS1D' => Milon\Barcode\Facades\DNS1DFacade::class,
        'DNS2D' => Milon\Barcode\Facades\DNS2DFacade::class,
        'Google2FA' => PragmaRX\Google2FALaravel\Facade::class,
    ])->toArray(),

];
