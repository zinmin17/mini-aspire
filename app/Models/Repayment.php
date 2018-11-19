<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repayment extends Model
{
    protected $table = 'repayment';
    protected $primaryKey = 'id';
    protected $fillable = ['loan_id','payment_no', 'repayable_amount', 'paid_amount', 'paid_at', 'payment_method', 'due_date', 'monthly_interest_rate', 'payment_status'];

    public function loan()
    {
        return $this->belongsTo('App\Models\Loan', 'loan_id', 'id');
    }
}