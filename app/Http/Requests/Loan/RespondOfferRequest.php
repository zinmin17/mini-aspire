<?php

namespace App\Http\Requests\Loan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

# Interface
use App\Repositories\LoanRepository\LoanInterface;

class RespondOfferRequest extends FormRequest
{
    protected $loanRepo;

    public function __construct(LoanInterface $loanRepo)
    {
         $this->loanRepo = $loanRepo;
         $this->user    = Auth::user() ? Auth::user()->id : null;
    }

    /**
     * User can ONLY accept the loan which was offered to the user!
     *
     * @return bool
     */
    public function authorize()
    { 
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'loan_id' => 'required|numeric', //loan id
            'user_respond' => 'required|in:accepted,rejected', //accepted or rejected only
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'loan_id.required' => 'loan id is required!',
            'loan_id.numeric' => 'loan id must be numeric!',
            'user_respond.required' => 'user respond is required',
            'user_respond.in' => 'accept only either "accepted" or "rejected"',
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
