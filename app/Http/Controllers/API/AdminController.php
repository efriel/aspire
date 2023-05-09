<?php

namespace App\Http\Controllers\API;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Repositories\Interfaces\CustomerRepositoryInterface;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
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
