<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Repositories\CustomerRepository;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Repositories\TransactionRepository;

class SavingsController extends Controller
{
    private $customerRepository;
    private $transactionRepository;
    public function __construct(CustomerRepositoryInterface $customerRepository, TransactionRepositoryInterface $transactionRepository)
    {
        $this->middleware('auth:api');
        $this->customerRepository = $customerRepository;
        $this->transactionRepository = $transactionRepository;
    }
    public function checkSavingInfo()
    {
        $user = Auth::user();
        $user_id = $user->id;
        $savings = $this->customerRepository->getSavingsInfo($user_id);
        $savingsBalances = $this->transactionRepository->getBalacesAmountTotal($user_id, $savings->account_number, $savings->gl_code);
        
        return response()->json([
            "success" => true,
            "data" => [
                "user" => $user,
                "savings" => $savings,
                "balances" => $savingsBalances,
            ],
            "message" => 'Success'
        ]);
    }
}
