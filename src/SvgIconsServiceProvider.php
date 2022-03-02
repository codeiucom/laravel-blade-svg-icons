<?php

namespace CodeIU\LaravelBladeSvgIcons;

use CodeIU\LaravelBladeSvgIcons\SvgIconsCompiler;
use Illuminate\Support\ServiceProvider;

class SvgIconsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/codeiu-laravel-blade-svg-icon.php', 'codeiu-laravel-blade-svg-icon'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/codeiu-laravel-blade-svg-icon.php' => config_path('codeiu-laravel-blade-svg-icon.php'),
            ], 'codeiu-laravel-blade-svg-icon-config');
        }

        if (method_exists($this->app['blade.compiler'], 'precompiler')) {
            $this->app['blade.compiler']->precompiler(function ($string) {
                return app(SvgIconsCompiler::class)->compile($string);
            });
        }
    }
}
