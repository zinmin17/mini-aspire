<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

# Validation Requests
use App\Http\Requests\Loan\OfferLoanRequest;
use App\Http\Requests\Loan\RespondOfferRequest;
use App\Http\Requests\Loan\LoanDetailsRequest;

use App\Http\Controllers\API\RepaymentController;

# Interface
use App\Repositories\LoanRepository\LoanInterface;
use App\Repositories\RepaymentRepository\RepaymentInterface;
class LoanController extends Controller
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
     * create new loan offer by admin (only 'admin' can create)
     * 
     * @param int $user_id 
     * @param float $amount
     * @param float $interest_rate
     * @param float $arrangement_fee
     * @param int $duration 
     * @param int $repayment_frequency
     * @param integer $admin_id
     * 
     * @return loan (Json)
     */
    public function offerLoan(OfferLoanRequest $request)
    { 
        $request->request->add(['admin_id'=> Auth::id()]);

        $new_loan = $this->loanRepo->createLoan($request->all());

        if($new_loan){
            $new_repayment_plan = $this->repayRepo->createRepaymentPlan($new_loan->id);
        }
        
        $details_loans = $this->loanRepo->details($new_loan->id);
        return $details_loans;
    }


    /**
     * view loan details & its re-payment information (by admin)
     * 
     * @param int $loan_id 
     * 
     * * @return loan (Json)
     */
    public function details(LoanDetailsRequest $request)
    { 
        $loan_details = $this->loanRepo->details($request->loan_id);

        return $loan_details;
    }


     /**
     * view all loans offered to the authorized user
     *
     * * @return loan (Json)
     */
    public function viewOfferedLoans()
    {
        $loans = $this->loanRepo->getLoansByUserId(Auth::user()->id);

        return $loans;
    }

    /**
     * respond the loan offer which was offered to the user 
     * 
     * @param int $id (loan id)
     * @param string $user_respond ("accepted" or "rejected")
     * 
     * @return loan (Json)
     */
    public function respondOffer(RespondOfferRequest $request)
    {
        /* validation for authorized loan access */
        $isOffered = $this->loanRepo->isOfferedPerson(Auth::user()->id, $request->loan_id);

        if(isset($isOffered['success'])){
            $respond_loan = $this->loanRepo->respondOffer($request->loan_id, $request->user_respond);

            return $respond_loan;
        }
        return response()->json($isOffered);
    }

    /**
     * get all loans that has overdue payment date
     *
     * @return loan (Json)
     */
    public function overdue()
    {
        $over_due_loans = $this->repayRepo->overduePayments();
        return $over_due_loans;
    }
}

