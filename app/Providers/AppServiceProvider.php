<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <-- 1. Added this import

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 2. Force HTTPS on Railway (Production) but not on Localhost
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }

        // Register @hasAccess directive for checking boolean settings
        Blade::directive('hasAccess', function ($expression) {
            return "<?php if(app('settings')->get($expression, false)): ?>";
        });

        Blade::directive('endhasAccess', function () {
            return '<?php endif; ?>';
        });

        // Share settings globally
        $this->app->singleton('settings', function () {
            try {
                if (! \Schema::hasTable('settings')) {
                    return collect([]);
                }
                $settings = Setting::pluck('value', 'key')->toArray();

                return collect($settings)->map(function ($value) {
                    if ($value === '1') {
                        return true;
                    }
                    if ($value === '0') {
                        return false;
                    }

                    return $value;
                });
            } catch (\Exception $e) {
                return collect([]);
            }
        });

        // Share settings with all views
        view()->composer('*', function ($view) {
            $settings = app('settings');
            $view->with([
                'formSettings' => $settings->all(),
                'site_name' => trim((string) $settings->get('site_name')) !== '' ? $settings->get('site_name') : config('app.name'),
            ]);
        });
    }
}