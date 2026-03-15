<?php

use Illuminate\Support\Facades\Route;

Route::post(config('store.stripe.webhook_path', 'stripe/webhook'), \App\Stripe\Http\Controllers\WebhookController::class)
    ->middleware([\App\Stripe\Http\Middleware\StripeWebhookMiddleware::class, 'api'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->name('store.stripe.webhook');
