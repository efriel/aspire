<?php

namespace App\Repositories\Interfaces;

interface CustomerRepositoryInterface 
{
    public function userCreate($request, $user_id);
    public function customerCreate($request, $user_id);
    public function accountSavingsCreate($customer_id);
}