<?php

namespace App\Providers;

use App\Page;
use App\Options;
use App\Category;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller as Ctrl;

class AppServiceProvider extends ServiceProvider
{
    public $categories;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // remove too long key error
        \Schema::defaultStringLength(191);
        // \URL::forceScheme('https');

        // get categories
        $categories = Category::orderBy('category')->get();

        // set categories
        $this->categories = $categories;

        // stripe related
        putenv('STRIPE_KEY=' . opt('STRIPE_PUBLIC_KEY'));
        putenv('STRIPE_SECRET=' . opt('STRIPE_SECRET_KEY'));

        // put the env into actions
        putenv('APP_KEY=' . APP_KEY);
        putenv('APP_URL=' . FULL_SITE_URL);
        putenv('FULL_SITE_URL=' . FULL_SITE_URL);
        putenv('SENDING_EMAIL=' . MAIL_FROM_ADDRESS);
        putenv('APP_NAME=' . APP_NAME);
        putenv('APP_DEBUG=' . APP_DEBUG);
        putenv('GLIDE_KEY=123');

        // db env
        putenv('DB_CONNECTION=mysql');
        putenv('DB_PORT=3306');
        putenv('DB_HOST=' . DATABASE_HOST);
        putenv('DB_DATABASE=' . DATABASE_NAME);
        putenv('DB_USERNAME=' . DATABASE_USER);
        putenv('DB_PASSWORD=' . DATABASE_PASS);

        // set default storage
        putenv('DEFAULT_STORAGE=' . opt('default_storage', 'public'));

        /******
         WASABI
        ******/
        // wassabi related
        $currentDisks = $this->app->config['filesystems']['disks'];

        // set wasabi settings from db
        $currentDisks['wasabi']['key'] = opt('WAS_ACCESS_KEY_ID');
        $currentDisks['wasabi']['secret'] = opt('WAS_SECRET_ACCESS_KEY');
        $currentDisks['wasabi']['region'] = opt('WAS_DEFAULT_REGION');
        $currentDisks['wasabi']['bucket'] = opt('WAS_BUCKET');
        $currentDisks['wasabi']['endpoint'] = 'https://s3.'.opt('WAS_DEFAULT_REGION').'.wasabisys.com';

        // append this disk
        $wasabi = $this->app->config->set('filesystems.disks', $currentDisks);

        /******
         DigitalOcean SPACES
        ******/
        // get current disks
        $currentDisks = $this->app->config['filesystems']['disks'];

        // set digitalocean settings from db
        $currentDisks['digitalocean']['key'] = opt('DOS_ACCESS_KEY_ID');
        $currentDisks['digitalocean']['secret'] = opt('DOS_SECRET_ACCESS_KEY');
        $currentDisks['digitalocean']['region'] = opt('DOS_DEFAULT_REGION');
        $currentDisks['digitalocean']['bucket'] = opt('DOS_BUCKET');
        $currentDisks['digitalocean']['endpoint'] = 'https://'.opt('DOS_DEFAULT_REGION').'.digitaloceanspaces.com';
        
        /******
         BackBlaze B2
        ******/
        // append this disk
        $backblaze = $this->app->config->set('filesystems.disks', $currentDisks);

        // get current disks
        $currentDisks = $this->app->config['filesystems']['disks'];

        // set backblaze b2 settings from db
        $currentDisks['backblaze']['accountId'] = opt('BACKBLAZE_ACCOUNT_ID');
        $currentDisks['backblaze']['applicationKey'] = opt('BACKBLAZE_APP_KEY');
        $currentDisks['backblaze']['bucketName'] = opt('BACKBLAZE_BUCKET');
        
        // append this disk
        $backblaze = $this->app->config->set('filesystems.disks', $currentDisks);

        /******
         Vultr Object Storage
        ******/
        // get current disks
        $currentDisks = $this->app->config['filesystems']['disks'];

        // set digitalocean settings from db
        $currentDisks['vultr']['key'] = opt('VULTR_ACCESS_KEY_ID');
        $currentDisks['vultr']['secret'] = opt('VULTR_SECRET_ACCESS_KEY');
        $currentDisks['vultr']['region'] = opt('VULTR_DEFAULT_REGION');
        $currentDisks['vultr']['bucket'] = opt('VULTR_BUCKET');
        $currentDisks['vultr']['endpoint'] = 'https://'.opt('VULTR_DEFAULT_REGION').'.vultrobjects.com';

        $vultr = $this->app->config->set('filesystems.disks', $currentDisks);


        /******
         Amazon AWS S3 Object Storage
        ******/
        // get current disks
        $currentDisks = $this->app->config['filesystems']['disks'];

        // set digitalocean settings from db
        $currentDisks['s3']['key'] = opt('AWS_ACCESS_KEY_ID');
        $currentDisks['s3']['secret'] = opt('AWS_SECRET_ACCESS_KEY');
        $currentDisks['s3']['region'] = opt('AWS_DEFAULT_REGION');
        $currentDisks['s3']['bucket'] = opt('AWS_BUCKET');

        $s3 = $this->app->config->set('filesystems.disks', $currentDisks);

        // get current url
        $url = request()->url();

        // extensions validator
        Validator::extend('extensions', function ($attribute, $value, $parameters) {
            return in_array($value->getClientOriginalExtension(), $parameters);
        });
        
        Validator::replacer('extensions', function($message, $attribute, $rule, $parameters) {
            return str_replace([':attribute', ':values'], [$attribute, implode(',', $parameters)], $message);
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        // pass categories to all views categories
        view()->composer('*', function ($view) {

            // get categories
            $categories = $this->categories;

            // get pages
            $pages = Page::orderBy('page_title')->get();

            // pass to all views
            $view->with('all_categories', $categories);
            $view->with('all_pages', $pages);
        });
    }

}
