<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware;
class VerifyCsrfToken extends Middleware\VerifyCsrfToken
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
}
