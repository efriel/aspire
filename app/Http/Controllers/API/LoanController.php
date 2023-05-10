<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Repositories\CustomerRepository;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Repositories\TransactionRepository;

class LoanController extends Controller
{
    private $customerRepository;
    private $transactionRepository;
    public function __construct(CustomerRepositoryInterface $customerRepository, TransactionRepositoryInterface $transactionRepository)
    {
        $this->middleware('auth:api');
        $this->customerRepository = $customerRepository;
        $this->transactionRepository = $transactionRepository;
    }

    public function requestLoan(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'dayterm' => 'required|numeric',
        ]);

        // get master loan info
        $masterLoanInfo = $this->transactionRepository->getMasterLoanByLimit($request->amount);
        if ($masterLoanInfo) {
            if ($request->dayterm < 7 && $request->dayterm > 30){
                return response()->json([
                    "success" => false,
                    "data" => [],
                    "message" => 'beyond day term'
                ]);
            }
            // create new loan account
            $accountTypeId = $masterLoanInfo->id;
            $tenor = $masterLoanInfo->tenor;
            $glCode = $masterLoanInfo->gl_code;

            $user = Auth::user();
            $user_id = $user->id;

            // create loan account record
            $accountInfo = (object) array(
                'account_type_id' => $masterLoanInfo->id,
                'customer_id' => $user_id,
                'dayterm' => $request->dayterm,
                'installment' => 0,
                'code' => 'C',
            );
            $loanCreate = $this->customerRepository->accountLoanCreate($accountInfo);

            // get loan info
            $loanInfo = $this->customerRepository->getLoanInfo($user_id);

            // initial savings deposit
            $loanData = (object) array(
                'account_number' => $loanInfo->account_number,
                'customer_id' => $user_id,
                'total' => $request->amount,
                'staff_id' => 1,
                'code' => 'C',
                'gl_code' => $loanInfo->gl_code,
                'amount' => $request->amount
            );
    
            $insertCredit = $this->transactionRepository->insertCredit($loanData);

            // reformat the request data
            $loanRequest = (object) array(
                'amount' => number_format($request->amount, 2),
                'dayterm' => $request->dayterm,
                'date' => date('jS F Y'),
            );
            
            return response()->json([
                "success" => true,
                "data" => [
                    "request" => $loanRequest,
                    "loan" => $loanInfo,
                ],
                "message" => 'Success'
            ]);
        }
        return response()->json([
            "success" => false,
            "data" => [],
            "message" => 'beyond credit limit'
        ]);
    }

    public function simulationLoan(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'dayterm' => 'required|numeric',
        ]);
        // get loan offer by amount requested
        $loanOffer = $this->transactionRepository->getMasterLoanByLimit($request->amount);
        if ($loanOffer) {
            if ($request->dayterm < 7 && $request->dayterm > 30){
                return response()->json([
                    "success" => false,
                    "data" => [],
                    "message" => 'beyond day term'
                ]);
            }

            // get loan si9mulation by amount requested and tenor
            $loanOfferSimulation = $this->transactionRepository->getLoanSimulation($request, $loanOffer->tenor);
            
            // reformat the request data
            $loanRequest = (object) array(
                'amount' => number_format($request->amount, 2),
                'dayterm' => $request->dayterm,
                'date' => date('jS F Y'),
            );

            return response()->json([
                "success" => true,
                "data" => [
                    "request" => $loanRequest,
                    "loan" => $loanOffer,
                    "simulation" => $loanOfferSimulation,
                ],
                "message" => 'Success'
            ]);
        }
        return response()->json([
            "success" => false,
            "data" => [],
            "message" => 'beyond credit limit'
        ]);
    }

    public function checkLoanInfo()
    {
        $user = Auth::user();
        $user_id = $user->id;
        $loanBalances = 0;
        $installment = 0;
        $loanInfo = $this->customerRepository->getLoanInfo($user_id);
        if ($loanInfo) {
            $initialBalances = $this->transactionRepository->getCreditAmountTotal($user_id, $loanInfo->account_number, $loanInfo->gl_code);
            $loanBalances = $this->transactionRepository->getBalancesAmountTotalCredit($user_id, $loanInfo->account_number, $loanInfo->gl_code);

            $request = (object) array(
                'amount' => $initialBalances,
                'day_term' => $loanInfo->day_term,
                'date' =>  $loanInfo->created_at,
                'tenor' => $loanInfo->tenor,
                'installment' => $loanInfo->installment,
                'customer_id' =>  $loanInfo->customer_id,
                'account_number' => $loanInfo->account_number,
                'loan_balances' => $loanBalances,
            );
            $installmentInfo = $this->transactionRepository->getInstallmentInfo($request);
        } else {
            $installmentInfo = [];
            $loanInfo = [];
        }
        
        return response()->json([
            "success" => true,
            "data" => [
                "user" => $user,
                "loan" => $loanInfo,
                "balances" => $loanBalances,
                "installment" => $installmentInfo,
            ],
            "message" => 'Success'
        ]);
    }

    public function repaymentLoan(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
        ]);
        $user = Auth::user();
        $user_id = $user->id;

        // get loan info
        $loanInfo = $this->customerRepository->getLoanInfo($user_id);

        // initial savings deposit
        $loanData = (object) array(
            'account_number' => $loanInfo->account_number,
            'customer_id' => $user_id,
            'total' => $request->amount,
            'staff_id' => 1,
            'code' => 'I',
            'gl_code' => $loanInfo->gl_code,
            'amount' => $request->amount
        );

        $insertDebit = $this->transactionRepository->insertDebit($loanData);
        $updateInstallment = $this->transactionRepository->updateLoanAccountInstallment($loanInfo->account_number);

        $loanBalances = $this->transactionRepository->getBalancesAmountTotal($user_id, $loanInfo->account_number, $loanInfo->gl_code);
        if ($loanBalances >= 0){
            $isPaid = $this->transactionRepository->setLoanPaid($loanInfo->account_number); 
        }
        return response()->json([
            "success" => true,
            "data" => [
                "user" => $user,
                "loan" => $loanInfo,
            ],
            "message" => 'Success'
        ]);
        

    }
}
