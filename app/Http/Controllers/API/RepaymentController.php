<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

# Validation Requests
use App\Http\Requests\Loan\MakePaymentRequest;

# Interface
use App\Repositories\RepaymentRepository\RepaymentInterface;
use App\Repositories\LoanRepository\LoanInterface;

class RepaymentController extends Controller
{
    protected $repayRepo;
    protected $loanRepo;

    public function __construct(RepaymentInterface $repayRepo, 
                                LoanInterface $loanRepo
                                )
    {
        $this->repayRepo = $repayRepo;
        $this->loanRepo = $loanRepo;
    }

    
    /**
     * make a payment by user/client
     * 
     * @param int $loan_id 
     * @param int $amount 
     * @param string $payment_method 
     * 
     * * @return repayment (Json)
     */
    public function makePayment(MakePaymentRequest $request)
    {
        /* validation for authorized loan payment */
        $check_authorized_loan = $this->loanRepo->isOfferedPerson(Auth::user()->id, $request->loan_id);
        if(isset($check_authorized_loan['error'])){
            return response()->json($check_authorized_loan);
        }
       
        /* validation for any repayable records */
        $check_repayable_record = $this->repayRepo->checkRepayableRecord($request->loan_id);
        if(isset($check_repayable_record['error'])){
            return response()->json($check_repayable_record);
        }

        /* validation for payment amount & repayable amount are same */
        $check_amount = $this->repayRepo->checkAmount($request->loan_id, $request->amount);
        if(isset($check_amount['error'])){
            return response()->json($check_amount);
        }
        
        $data = 
            [
                'paid_amount'=>$request->amount,
                'paid_at' => Carbon::now()->format('Y-m-d H::i:s'),
                'payment_method' => $request->payment_method,
                'payment_status' => "paid"
            ];

        return $this->repayRepo->makePayment($request->loan_id, $data);
    }

   

   
}
