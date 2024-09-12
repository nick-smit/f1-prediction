<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Team;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Unique;

class TeamRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $team = $this->route('team');
        $teamUniqueRule = new Unique('teams', 'name');
        if ($team instanceof Team) {
            $teamUniqueRule->ignoreModel($team);
        }

        return [
            'name' => [
                'required',
                'string',
                $teamUniqueRule
            ],
        ];
    }
}
