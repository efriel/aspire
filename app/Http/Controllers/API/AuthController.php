<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Customer;
use App\Models\AccountSavings;

// repositories
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Repositories\CustomerRepository;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Repositories\TransactionRepository;

class AuthController extends Controller
{
    private $customerRepository;
    private $transactionRepository;
    public function __construct(CustomerRepositoryInterface $customerRepository, TransactionRepositoryInterface $transactionRepository)
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->customerRepository = $customerRepository;
        $this->transactionRepository = $transactionRepository;
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('username', 'password');
        $token = Auth::attempt($credentials);
        
        if (!$token) {
            return response()->json([
                "success" => false,
                "data" => [],
                "message" => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'email' => 'required|string|email|max:255|unique:users',
            'address' => 'required|string|max:255',
        ]);

        // create user id 
        $user_id = mt_rand(100,999);
        // create new user record
        $user = $this->customerRepository->userCreate($request, $user_id);
        // create new customer profile record
        $customer = $this->customerRepository->customerCreate($request, $user_id); 
        // create savings account record
        $savingsCreate = $this->customerRepository->accountSavingsCreate($user_id);
        // get savings info
        $savingsInfo = $this->customerRepository->getSavingsInfo($user_id);
        // initial savings deposit

        $savingsData = (object) array(
            'account_number' => $savingsInfo->account_number,
            'customer_id' => $user_id,
            'total' => $savingsInfo->balance_minimum,
            'staff_id' => 1,
            'code' => 'H',
            'gl_code' => $savingsInfo->gl_code,
            'amount' => $savingsInfo->balance_minimum
        );

        $insertTransaction = $this->transactionRepository->insertTransaction($savingsData);


        return response()->json([
            "success" => false,
            "data" => [
                "user" => $user,
                "profile" => $customer,
                "savings" => $savingsInfo,
            ],
            "message" => 'User created successfully',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            "success" => true,
            "data" => [],
            "message" => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
