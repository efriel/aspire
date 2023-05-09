<?php

namespace App\Repositories\Interfaces;

interface TransactionRepositoryInterface 
{
    public function getDebitAmountTotal($customer_id, $account_number, $gl_code);
    public function getCreditAmountTotal($customer_id, $account_number, $gl_code);
    public function getBalacesAmountTotal($customer_id, $account_number, $gl_code);
}