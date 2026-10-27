<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use App\Models\Setting;
class AppServiceProvider extends ServiceProvider {
 public function register(): void { $this->app->scoped('site.settings', fn () => Setting::current()); }
 public function boot(): void {
  View::composer('*', fn ($view) => $view->with('site', app('site.settings')));
  Paginator::defaultView('public.pagination');
 }
}
