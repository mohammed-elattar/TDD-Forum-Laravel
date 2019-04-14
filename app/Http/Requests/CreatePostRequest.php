<?php

namespace App\Http\Requests;
use App\Exceptions\ThrottleException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Gate;

class CreatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $lastReply= auth()->user()->fresh()->lastReply();
        if(!$lastReply) return true;
        return !$lastReply->wasJustPublished();
//        Gate::allows('create', Reply::class);
    }

    protected function failedAuthorization()
    {
        throw new ThrottleException("You are replying too frequently, please take a break");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body' => 'required|spamfree',
        ];
    }

    public function persist()
    {

    }
}
