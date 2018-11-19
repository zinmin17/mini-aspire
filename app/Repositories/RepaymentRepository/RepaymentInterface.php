<?php
namespace App\Repositories\RepaymentRepository;


interface RepaymentInterface
{
    public function createRepaymentPlan($loan_id);

    public function makePayment($loan_id, $data);

    public function checkRepayableRecord($loan_id);

    public function checkAmount($loan_id, $amount);

    public function getOutstandingByLoanId($loan_id);

    public function getOutstandingByUserId($user_id);

    public function overduePayments();
}