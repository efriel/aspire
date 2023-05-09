<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function simulation()
    {
        return response()->json([
            "success" => true,
            "data" => [],
            "message" => 'Success'
        ]);
    }
}
