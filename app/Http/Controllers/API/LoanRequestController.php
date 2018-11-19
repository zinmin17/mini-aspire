<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

#Validation Requests
use App\Http\Requests\Loan\RequestLoanRequest;

# Interface
use App\Repositories\LoanRepository\LoanInterface;
use App\Repositories\RepaymentRepository\RepaymentInterface;

class LoanRequestController extends Controller
{
    protected $loanRepo; 
    protected $repayRepo;

    public function __construct(LoanInterface $loanRepo,
                                RepaymentInterface $repayRepo)
    {
        $this->loanRepo = $loanRepo;
        $this->repayRepo = $repayRepo;
    }


    /**
     * request new loan by user
     * 
     * @param int $amount 
     * @param vchr $duration
     * 
     * @return loan (Json)
     */
    public function create(RequestLoanRequest $request)
    {  
        
        $outstanding = $this->repayRepo->getOutstandingByUserId(Auth::id());

        if($outstanding){
            return response()->json(['error'=>"there are outstanding payments. Reloan can be made after clearing the current repayments"]);
        }

        $request_loan = $this->loanRepo->createLoanRequest($request->all(), Auth::id());

        return $request_loan;
    }


    /**
     * get all requested loans by this authorized user
     *
     * @return loans (Json)
     */
    public function all()
    { 
        $get_requested_loans = $this->loanRepo->getRequestedLoans(Auth::id());

        return $get_requested_loans;
    }
}
