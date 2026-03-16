<?php

use Illuminate\Support\Facades\Route;
use App\Stripe\Http\Controllers\WebhookController;
use App\Stripe\Http\Middleware\StripeWebhookMiddleware;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

Route::post(config('store.stripe.webhook_path', 'stripe/webhook'), WebhookController::class)
    ->middleware([StripeWebhookMiddleware::class, 'api'])
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->name('store.stripe.webhook');
