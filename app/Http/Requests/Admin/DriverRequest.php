<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Driver;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Unique;

class DriverRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->is_admin ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $driver = $this->route('driver');
        $driverUniqueRule = new Unique('drivers', 'name');
        if ($driver instanceof Driver) {
            $driverUniqueRule->ignoreModel($driver);
        }

        return [
            'number' => ['required', 'integer', 'between:1,99'],
            'name' => [
                'required',
                'string',
                $driverUniqueRule
            ],
        ];
    }
}
