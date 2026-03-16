<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware;
class TrustHosts extends Middleware\TrustHosts
{
    /**
     * Get the host patterns that should be trusted.
     *
     * @return array<int, string|null>
     */
    public function hosts(): array
    {
        return [
            $this->allSubdomainsOfApplicationUrl(),
        ];
    }
}
