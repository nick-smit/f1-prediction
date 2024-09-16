<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Driver;
use App\Models\Team;
use App\Rules\UniqueDriverContract;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Exists;

class ContractRequest extends FormRequest
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
        return [
            'driver' => ['required', 'integer', new Exists(Driver::class, 'id'), new UniqueDriverContract($this->route('contract'))],
            'team' => ['required', 'integer', new Exists(Team::class, 'id')],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
        ];
    }
}
