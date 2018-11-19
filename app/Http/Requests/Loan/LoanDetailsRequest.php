<?php

namespace App\Http\Requests\Loan;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

# Interface
use App\Repositories\LoanRepository\LoanInterface;

class LoanDetailsRequest extends FormRequest
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
            'loan_id'=> 'required|numeric',
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
            'loan_id.required' => 'loan_id is required!',
            'loan_id.numeric' => 'loan_id must be numeric',
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
