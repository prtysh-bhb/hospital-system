<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

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
            // $view->with('formSettings', app('settings')->all(),);
            $settings = app('settings');
            $view->with([
                'formSettings' => $settings->all(),
                'site_name' => trim((string) $settings->get('site_name')) !== '' ? $settings->get('site_name') : config('app.name'),
            ]);
        });
    }
}
