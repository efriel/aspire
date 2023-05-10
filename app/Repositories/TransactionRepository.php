<?php

namespace App\Repositories;

use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Models\MasterLoan;
use App\Models\AccountLoan;
use App\Models\TransactionDebit;
use App\Models\TransactionDebitDetail;
use App\Models\TransactionCredit;
use App\Models\TransactionCreditDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

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

    public function getBalancesAmountTotal($customer_id, $account_number, $gl_code) 
    {
        $debit = $this->getDebitAmountTotal($customer_id, $account_number, $gl_code);
        $credit = $this->getCreditAmountTotal($customer_id, $account_number, $gl_code);
        return $debit-$credit;
    }

    public function getBalancesAmountTotalCredit($customer_id, $account_number, $gl_code) 
    {
        $debit = $this->getDebitAmountTotal($customer_id, $account_number, $gl_code);
        $credit = $this->getCreditAmountTotal($customer_id, $account_number, $gl_code);
        return $credit-$debit;
    }

    public function insertDebit($transactionInfo)
    {
        $transaction_id = time();
        // save to transaction
        $debit = new TransactionDebit;
        $debit->transaction_id = $transaction_id;
        $debit->account_number = $transactionInfo->account_number;
        $debit->customer_id = $transactionInfo->customer_id;
        $debit->total = $transactionInfo->total;
        $debit->staff_id = $transactionInfo->staff_id;
        $debit->code = $transactionInfo->code;
        $debit->save();

        // save to transaction
        $detail = new TransactionDebitDetail;
        $detail->transaction_id = $transaction_id;
        $detail->gl_code = $transactionInfo->gl_code;
        $detail->amount = $transactionInfo->amount;
        $detail->save();

        return $debit;
    }

    public function insertCredit($transactionInfo)
    {
        $transaction_id = time();
        // save to transaction
        $debit = new TransactionCredit;
        $debit->transaction_id = $transaction_id;
        $debit->account_number = $transactionInfo->account_number;
        $debit->customer_id = $transactionInfo->customer_id;
        $debit->total = $transactionInfo->total;
        $debit->staff_id = $transactionInfo->staff_id;
        $debit->code = $transactionInfo->code;
        $debit->save();

        // save to transaction
        $detail = new TransactionCreditDetail;
        $detail->transaction_id = $transaction_id;
        $detail->gl_code = $transactionInfo->gl_code;
        $detail->amount = $transactionInfo->amount;
        $detail->save();

        return $debit;
    }

    public function getMasterLoanByLimit($limit)
    {
        return MasterLoan::where('limit', '>=', $limit)->orderBy('limit', 'ASC')->first();
    }

    public function getLoanSimulation($request, $tenor)
    {
        $simulation = new Collection();
        $amount = $request->amount;
        $installment = round($amount/$tenor, 2);
        $dayterm = $request->dayterm;
        $date = strtotime(date("Ymd"));
        $newInstallment = 0;
        for ($i = 1; $i < $tenor; $i++){  
            $date = strtotime("+".$dayterm." day", $date);
            $duedate = date('jS F Y', $date);
            $simulation->push((object)[
                'date' => $duedate, 
                'amount' => number_format($installment, 2)
            ]);
            $newInstallment += $installment;
        }
        $date = strtotime("+".$dayterm." day", $date);
        $duedate = date('jS F Y', $date);
        $simulation->push((object)[
            'date' => $duedate, 
            'amount' => number_format($amount-$newInstallment, 2)
        ]);
        return $simulation;
    }

    public function getTransactionList() 
    { 
        return DB::table('transaction_debit as t')->join('transaction_debit_detail as d', 'd.transaction_id', '=', 't.transaction_id')
                ->join('master_code as c', 'c.code', '=', 't.code')
                ->whereNotIn('t.code', ['H', 'I', 'J'])
                ->union(
                    DB::table('transaction_credit as t')
                    ->join('transaction_credit_detail as d', 'd.transaction_id', '=', 't.transaction_id')
                    ->join('master_code as c', 'c.code', '=', 't.code')
                    ->whereNotIn('t.code', ['H', 'I', 'J'])
                )->get();
    }

    public function approveTransaction($transactionId)
    {
        TransactionDebit::where('transaction_id', $transactionId)
            ->update(['code' => 'H']);
        TransactionCredit::where('transaction_id', $transactionId)
            ->update(['code' => 'H']);

        $loanTransaction = TransactionCredit::where('transaction_id', $transactionId)->first();
        if ($loanTransaction){
            AccountLoan::where('account_number', $loanTransaction->account_number)
            ->update(['code' => 'J']);
        }
    }

    public function getTransaction($transactionId) 
    { 
        return DB::table('transaction_debit as t')->join('transaction_debit_detail as d', 'd.transaction_id', '=', 't.transaction_id')
                ->join('master_code as c', 'c.code', '=', 't.code')
                ->where('t.transaction_id', '=', $transactionId)
                ->union(
                    DB::table('transaction_credit as t')
                    ->join('transaction_credit_detail as d', 'd.transaction_id', '=', 't.transaction_id')
                    ->join('master_code as c', 'c.code', '=', 't.code')
                    ->where('t.transaction_id', '=', $transactionId)
                )->get();
    }

    public function getInstallmentInfo($request)
    {
        $amount = $request->amount;
        $day_term = $request->day_term;
        $date = strtotime($request->date);
        $tenor = $request->tenor;
        $installmentNo = $request->installment+1;
        $customer_id = $request->customer_id;
        $account_number = $request->account_number;
        $loan_balances = $request->loan_balances;

        $installmentPay = round($amount/$tenor, 2);
        $newInstallment = 0;
        $isFinalRepayment = true;
        for ($i = 1; $i < $tenor; $i++){  
            $date = strtotime("+".$day_term." day", $date);
            $duedate = date('jS F Y', $date);
            $newInstallment += $installmentPay;
            if ($installmentNo===$i){
                $installment_pay = number_format($installmentPay, 2);
                $installment_duedate = $duedate;
                $isFinalRepayment = false;
            }
        }
        if ($isFinalRepayment) {
            $date = strtotime("+".$day_term." day", $date);
            $duedate = date('jS F Y', $date);
            $installment_duedate = $duedate;
            $installment_pay = number_format($loan_balances, 2);
        }
        
        $response = (object) array(
            'installment_no' => $installmentNo,
            'installment_pay' => $installment_pay,
            'installment_duedate' =>  $installment_duedate,
        );
        return $response;
    }

    public function setLoanPaid($accountNumber)
    {
        AccountLoan::where('account_number', $accountNumber)
        ->update(['code' => 'I']);   
    }

    public function updateLoanAccountInstallment($accountNumber)
    {
        AccountLoan::where('account_number', $accountNumber)
        ->update([
            'installment' => DB::raw('installment + 1')
        ]);
    }

    
}