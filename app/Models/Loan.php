<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $table = 'loan';
    protected $primaryKey = 'id';
    protected $fillable = ['requested_id', 'user_id', 'amount', 'interest_rate', 'arrangement_fee', 'duration', 'start_date', 'status', 'user_respond', 'admin_id'];

    public function repayment_plan()
    {
        return $this->hasMany('App\Models\Repayment', 'loan_id' , 'id');
    }

}
