<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountingController extends Controller
{
    public function transactions()
    {
        return response()->json([
            "success" => true,
            "data" => [],
            "message" => 'Success'
        ]);
    }
}
