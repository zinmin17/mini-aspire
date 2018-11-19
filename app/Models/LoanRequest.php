<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanRequest extends Model
{
    protected $table = 'loan_request';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'amount', 'duration'];
}
