<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::macro('search', function ($field, $string) {
            if (!$string) {
                return $this;
            }
            return $this->where($field, 'like', '%' . $string . '%');
        });

        Builder::macro('toCsv', function () {
            $results = $this->get();
            if ($results->count() < 1) {
                return;
            }

            $titles = implode(',', array_keys((array)$results->first()->getAttributes()));
            $values = $results->map(function ($result) {
                return implode(',', collect($result->getAttributes())->map(function ($thing) {
                    return '"' . $thing . '"';
                })->toArray());
            });

            $values->prepend($titles);
            return $values->implode("\n");
        });

        Paginator::useBootstrap();
    }
}
