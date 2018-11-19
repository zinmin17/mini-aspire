<?php
namespace App\Repositories\RepaymentRepository;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

# Traits 
use App\Repositories\RepaymentRepository\CalculationTraits;

# Model
use App\Models\Repayment;

# Interface
use App\Repositories\RepaymentRepository\RepaymentInterface;
use App\Repositories\LoanRepository\LoanInterface;

class RepaymentRepo implements RepaymentInterface
{
    use CalculationTraits;
    
    protected $loanRepo;

    public function __construct(LoanInterface $loanRepo)
    {
        $this->loanRepo = $loanRepo;
        $this->now = Carbon::now();
    }
    

    /* 
   |---------------------------------------
   | create new repayment plan (by admin)
   |---------------------------------------
   */
    public function createRepaymentPlan($loan_id)
    {
         // avoid overwriting
         if(!$this->hasRepaymentPlan($loan_id)){
            
            $get_new_plan = $this->getNewRepaymentPlan($loan_id);

            foreach($get_new_plan as $row)
            {
               $this->saveNewRepaymentPlan($loan_id, $row);
            }
            return $get_new_plan;
         }
         return "already have repayment plan";
    }

    /* check the loan have created repayment pan */
    protected function hasRepaymentPlan($loan_id)
    {
         return Repayment::where('loan_id', $loan_id)->exists();
    }

    /* based on loans attributes, the new repayment plan is calculated */
    protected function getNewRepaymentPlan($loan_id)
    {
         $loan_details = $this->loanRepo->details($loan_id);
         if($loan_details){
            return $this->getRepaymentPlan($loan_details->amount, $loan_details->interest_rate, $loan_details->duration, $loan_details->start_date);
         }
    }
    
    /* save new repayment paln in database */
    protected function saveNewRepaymentPlan($loan_id, $data)
    {
         $new_data = array_merge($data, [ 'loan_id'  => $loan_id]);
         $create = Repayment::create($new_data);
         return $create;
    }

   
   /* 
   |---------------------------------------
   | making payment for repayment (by user)
   |---------------------------------------
   */
    public function makePayment($loan_id, $data)
    {
         $upcoming_repayment = $this->getUpcomingPayment($loan_id);
         if($upcoming_repayment){
            $upcoming_repayment->update($data);
            return $upcoming_repayment;
         }
    }

    /* check user have any repayable records */
    public function checkRepayableRecord($loan_id)
    {
         $upcoming_repayment = $this->getUpcomingPayment($loan_id);
         if($upcoming_repayment){
            return ['success' => $upcoming_repayment];
         }else{
            return ['error' => "no repayable records"];
         }
    }

    /* check user make correct payment amount */
    public function checkAmount($loan_id, $amount)
    {
         $upcoming_repayment = $this->getUpcomingPayment($loan_id);

         if($amount == $upcoming_repayment->repayable_amount){
            return ['success' => $amount];

         }elseif($amount < $upcoming_repayment->repayable_amount){
            return ['error' => [
                              'msg'=>'underpaid', 
                              'repayable amount'=> $upcoming_repayment->repayable_amount, 
                              'paid amount'=>$amount
                              ]
                  ];
            
         }elseif($amount > $upcoming_repayment->repayable_amount){
            return ['error' => [
                              'msg'=>'overpaid', 
                              'repayable amount'=> $upcoming_repayment->repayable_amount, 
                              'paid amount'=>$amount
                              ]
                  ];
         }
    }

    /* get latest up coming repayment records based on loan_id */
    public function getUpcomingPayment($loan_id)
    {
         return Repayment::where('loan_id', $loan_id)->where('payment_status', 'unpaid')->orderBy('payment_no')->first();
    }

    /* get all repayments records that have NOT been paid yet by the loan */
     public function getOutstandingByLoanId($loan_id)
     {
         return Repayment::where('loan_id', $loan_id)->where('payment_status', 'unpaid')->get();
     }

     /* get all repayments records that have NOT been paid yet by the user */
     public function getOutstandingByUserId($user_id)
     { 
         $outstanding = [];
         $user_loans = $this->loanRepo->getLoansByUserId($user_id);
         foreach($user_loans as $loan){
            $outstanding []  = $this->getOutstandingByLoanId($loan->id);
         }
         return array_flatten($outstanding);
     }

     /* get all overdued payments */
     public function overduePayments()
     {
        $over_due = Repayment::with('loan')->where('due_date', '<', $this->now)->where('payment_status', 'unpaid')->get();
        return $over_due ;
     }


   

    
    
}
