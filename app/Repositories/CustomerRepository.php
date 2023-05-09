<?php

namespace App\Repositories;

use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Models\User;
use App\Models\Customer;
use App\Models\AccountSavings;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CustomerRepository implements CustomerRepositoryInterface 
{
    public function userCreate($request, $user_id) 
    {
        return User::create([
            "id" => $user_id,
            "username" => $request->username,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "role_id" => 1,
        ]);
    }

    public function customerCreate($request, $user_id) 
    {
        return Customer::create([
            "customer_id" => $user_id,
            "user_id" => $user_id,
            "name" => $request->name,
            "address" => $request->address,
        ]);
    }

    public function accountSavingsCreate($customer_id) 
    {
        $account_number = mt_rand(1000,9999);
        return AccountSavings::create([
            "account_number" => $account_number,
            "account_type_id" => 1,
            "customer_id" => $customer_id,
            "day_term" => 0,
            "code" => "J", 
        ]);
    }

    public function getSavingsInfo($user_id)
    {
        return DB::table('account_savings as a')
            ->join('master_savings as m', 'm.id', '=', 'a.account_type_id')
            ->where('a.customer_id', $user_id)
            ->where('a.code', 'J')
            ->first();
    }
}