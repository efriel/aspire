<?php

namespace App\Repositories;

use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Models\TransactionDebit;
use App\Models\TransactionDebitDetail;
use App\Models\TransactionCredit;
use App\Models\TransactionCreditDetail;
use Illuminate\Support\Facades\DB;

class TransactionRepository implements TransactionRepositoryInterface 
{
    public function getDebitAmountTotal($customer_id, $account_number, $gl_code) 
    {
        return DB::table('transaction_debit as t')
            ->join('transaction_debit_detail as d', 'd.transaction_id', '=', 't.transaction_id')
            ->where('d.gl_code', $gl_code)
            ->where('t.account_number', $account_number)
            ->where('t.customer_id', $customer_id)
            ->whereIn('t.code', ['H', 'I'])
            ->sum('d.amount');
    }

    public function getCreditAmountTotal($customer_id, $account_number, $gl_code) 
    {
        return DB::table('transaction_credit as t')
            ->join('transaction_credit_detail as d', 'd.transaction_id', '=', 't.transaction_id')
            ->where('d.gl_code', $gl_code)
            ->where('t.account_number', $account_number)
            ->where('t.customer_id', $customer_id)
            ->whereIn('t.code', ['H', 'I'])
            ->sum('d.amount');
    }

    public function getBalacesAmountTotal($customer_id, $account_number, $gl_code) 
    {
        $debit = $this->getDebitAmountTotal($customer_id, $account_number, $gl_code);
        $credit = $this->getCreditAmountTotal($customer_id, $account_number, $gl_code);
        return $debit-$credit;
    }

    public function insertTransaction($savingsInfo)
    {
        $transaction_id = time();
        // save to transaction
        $debit = new TransactionDebit;
        $debit->transaction_id = $transaction_id;
        $debit->account_number = $savingsInfo->account_number;
        $debit->customer_id = $savingsInfo->customer_id;
        $debit->total = $savingsInfo->total;
        $debit->staff_id = $savingsInfo->staff_id;
        $debit->code = $savingsInfo->code;
        $debit->save();

        // save to transaction
        $detail = new TransactionDebitDetail;
        $detail->transaction_id = $transaction_id;
        $detail->gl_code = $savingsInfo->gl_code;
        $detail->amount = $savingsInfo->amount;
        $detail->save();

        return $debit;
    }

}