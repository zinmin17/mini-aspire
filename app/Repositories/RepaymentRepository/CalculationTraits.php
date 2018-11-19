<?php 
namespace App\Repositories\RepaymentRepository;

use Carbon\Carbon;

trait CalculationTraits
{
    public function getTotalInterest($amount, $rate)
    {
         return $amount * ($rate/100);
    }

    public function getMonthlyInterestRate($rate, $duration)
    {
         return $rate/$duration;
    }

    public function getToalRepayable($amount, $total_interest)
    {
         return $amount + $total_interest;
    }

    public function getMonthlyRepayable($total_repayable, $duration)
    {
         return $total_repayable/$duration;
    }

     protected function getRepaymentPlan($amount, $interest_rate, $duration, $start_date)
    {
         $repaymentPlan = [];
         $date = Carbon::parse($start_date);

         //total interest
         $total_interest = $this->getTotalInterest($amount, $interest_rate);

         //monthly interest rate
         $monthly_interest_rate = $this->getMonthlyInterestRate($interest_rate, $duration);

         //total amount repayable
         $total_repayable = $this->getToalRepayable($amount, $total_interest);

         //monthly total repaymant
         $monthly_repayment = $this->getMonthlyRepayable($total_repayable, $duration); 

         for ($n = 1; $n <= $duration; $n++) {
            
            $repaymentPlan[$n] = 
                        [  
                           'payment_no'=> $n, 
                           'repayable_amount'=> number_format($monthly_repayment, 2, '.', '') ,
                           'monthly_interest_rate' => number_format($monthly_interest_rate, 2, '.', ''),
                           'due_date' => $date->addDays(30)->format('Y-m-d'),
                        ];
         }
         return $repaymentPlan;
    }
}


// https://help.aspire-cap.com/singapore-faqs/before-application/interest-rates
// As an example, if you borrow 10,000 SGD for a 3-month period at 6% term rate (2% per month):
// The total interest is 10,000 x 6% = S$600
// The total amount repayable is 10,000 + 600 = S$10,600
// The monthly total repayment is 10,600 SGD / 3 months = S$3,533.33

// Origination fee: one-time ranging from 1% to 6% fee, deducted from the loan amount
// Indicative interest rates: Between 1.5% to 4% per month
