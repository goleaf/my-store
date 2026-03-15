<?php

namespace App\Stripe;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Store\Facades\Payments;
use App\Store\Models\Cart;
use App\Store\Models\Contracts\Cart as CartContract;
use App\Stripe\Actions\ConstructWebhookEvent;
use App\Stripe\Actions\ProcessEventParameters;
use App\Stripe\Components\PaymentForm;
use App\Stripe\Concerns\ConstructsWebhookEvent;
use App\Stripe\Concerns\ProcessesEventParameters;
use App\Stripe\Managers\StripeManager;
use App\Stripe\Models\StripePaymentIntent;

class StripePaymentsServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Register our payment type.
        Payments::extend('stripe', function ($app) {
            return $app->make(StripePaymentType::class);
        });

        Cart::resolveRelationUsing('paymentIntents', function (CartContract $cart) {
            return $cart->hasMany(StripePaymentIntent::class);
        });

        $this->app->bind(ProcessesEventParameters::class, function ($app) {
            return $app->make(ProcessEventParameters::class);
        });

        $this->app->bind(ConstructsWebhookEvent::class, function ($app) {
            return $app->make(ConstructWebhookEvent::class);
        });

        $this->app->singleton('store:stripe', function ($app) {
            return $app->make(StripeManager::class);
        });

        Blade::directive('stripeScripts', function () {
            return <<<'EOT'
                <script src="https://js.stripe.com/v3/"></script>
            EOT;
        });

        $this->loadViewsFrom(resource_path('views/vendor/store'), 'store');
        $this->loadRoutesFrom(base_path('routes/stripe-webhooks.php'));

        if (! config('store.database.disable_migrations', false)) {
            $this->loadMigrationsFrom(database_path('migrations'));
        }

        $this->mergeConfigFrom(config_path('store/stripe.php'), 'store.stripe');

        $this->publishes([
            config_path('store/stripe.php') => config_path('store/stripe.php'),
        ], 'store.stripe.config');

        $this->publishes([
            resource_path('views/vendor/store') => resource_path('views/vendor/store'),
        ], 'store.stripe.components');

        if (class_exists(Livewire::class)) {
            // Register the stripe payment component.
            Livewire::component('stripe.payment', PaymentForm::class);
        }
    }
}
