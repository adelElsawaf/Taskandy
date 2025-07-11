<?php

namespace App\Http\Requests;

use App\Enums\ProjectMembershipType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class AddMembershipRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => ['required', 'exists:users,id'], // Split rules into an array
            'project_id' => ['required', 'exists:projects,id'], // Split rules into an array
            'membership_type' => ['required', new Enum(ProjectMembershipType::class)],

        ];
    }
}
