<?php

namespace Raykazi\Seat\SeatApplication;
use Raykazi\Seat\SeatApplication\Models\ApplicationModel;
use Raykazi\Seat\SeatApplication\Observers\ApplicationObserver;
use Seat\Services\AbstractSeatPlugin;

class ApplicationServiceProvider extends AbstractSeatPlugin
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->add_alerts();
        $this->addCommands();
        $this->add_routes();
        // $this->add_middleware($router);
        $this->add_views();
        $this->add_publications();
        $this->add_migrations();
        $this->add_translations();
        $this->apply_custom_configuration();
        $this->add_events();
    }
    private function add_alerts()
    {
//        $this->publishes([
//            __DIR__ . '/Config/application.alerts.php' => config_path('notifications.alerts.php'),
//        ], ['config', 'seat']);
        $this->mergeConfigFrom(__DIR__ . '/Config/application.alerts.php', 'notifications.alerts.php');
    }
    private function add_events()
    {
        ApplicationModel::observe(ApplicationObserver::class);

    }
    /**
     * Include the routes.
     */
    public function add_routes()
    {
        if (! $this->app->routesAreCached()) {
            include __DIR__ . '/Http/routes.php';
        }
    }

    public function add_translations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/lang', 'application');
    }

    /**
     * Set the path and namespace for the views.
     */
    public function add_views()
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'application');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/Config/application.config.php', 'application.config');

        $this->mergeConfigFrom(
            __DIR__ . '/Config/application.sidebar.php', 'package.sidebar');

        $this->registerPermissions(
            __DIR__ . '/Config/Permissions/application.permissions.php', 'application');
    }

    public function add_publications()
    {
        $this->publishes([
            __DIR__ . '/resources/assets'     => public_path('web'),
        ]);
    }

    private function add_migrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/');
    }

    private function addCommands()
    {
    }

    /**
     * Apply any configuration overrides to those config/
     * files published using php artisan vendor:publish.
     *
     * In the case of this service provider, this is mostly
     * configuration items for L5-Swagger.
     */
    public function apply_custom_configuration()
    {
        // Tell L5-swagger where to find annotations. These form
        // part of the controllers themselves.

        // ensure current annotations setting is an array of path or transform into it
        $current_annotations = config('l5-swagger.paths.annotations');
        if (! is_array($current_annotations))
            $current_annotations = [$current_annotations];

        // merge paths together and update config
        config([
            'l5-swagger.paths.annotations' => array_unique(array_merge($current_annotations, [
                __DIR__ . '/Http/Controllers',
            ])),
        ]);
    }

    /**
     * Return the plugin public name as it should be displayed into settings.
     *
     * @example SeAT Web
     *
     * @return string
     */
    public function getName(): string
    {
        return 'SeAT Apply';
    }


    /**
     * Return the plugin repository address.
     *
     * @example https://github.com/eveseat/web
     *
     * @return string
     */
    public function getPackageRepositoryUrl(): string
    {
        return 'https://github.com/raykazi/seat-apply';
    }

    /**
     * Return the plugin technical name as published on package manager.
     *
     * @example web
     *
     * @return string
     */
    public function getPackagistPackageName(): string
    {
        return 'seat-apply';
    }

    /**
     * Return the plugin vendor tag as published on package manager.
     *
     * @example eveseat
     *
     * @return string
     */
    public function getPackagistVendorName(): string
    {
        return 'raykazi';
    }
}
