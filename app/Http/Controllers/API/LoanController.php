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
}
