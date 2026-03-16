<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing;

class Controller extends Routing\Controller
{
    use AuthorizesRequests, ValidatesRequests;
}
