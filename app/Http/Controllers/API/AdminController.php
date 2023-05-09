<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Repositories\CustomerRepository;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Repositories\TransactionRepository;

class AdminController extends Controller
{
    private $customerRepository;
    private $transactionRepository;
    public function __construct(CustomerRepositoryInterface $customerRepository, TransactionRepositoryInterface $transactionRepository)
    {
        $this->middleware('auth:api');
        $this->customerRepository = $customerRepository;
        $this->transactionRepository = $transactionRepository;
    }

    public function transactionList()
    {
        // $transactionList = $this->transactionRepository->getTransactionList();

        return response()->json([
            "success" => true,
            "data" => [],
            "message" => 'Success'
        ]);
    }
}
