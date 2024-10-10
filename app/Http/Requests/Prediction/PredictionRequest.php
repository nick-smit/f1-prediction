<?php

declare(strict_types=1);

namespace App\Http\Requests\Prediction;

use App\Models\Driver;
use App\Models\RaceSession;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Override;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PredictionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() instanceof User;
    }

    #[Override]
    protected function prepareForValidation(): void
    {
        $raceSession = $this->getRaceSession();

        if (!$raceSession->guessable) {
            throw new NotFoundHttpException('The session was not found');
        }

        if ($raceSession->session_start->isPast()) {
            throw ValidationException::withMessages([
                'prediction' => "It's not possible to make a prediciton anymore"
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'prediction' => [
                'bail',
                'required',
                'array',
                'size:10',
                function ($attribute, $value, $fail): void {
                    foreach ($value as $item) {
                        if (!is_int($item)) {
                            return;
                        }
                    }

                    $driversCount = Driver::query()
                        ->whereIn('id', $value)
                        ->whereHas('contracts', function ($query): void {
                            $query->active($this->getRaceSession()->session_start);
                        })->count();

                    if ($driversCount !== 10) {
                        $fail('Not all drivers exists or have active contracts.');
                    }
                }
            ],
            'prediction.*' => [
                'integer'
            ]
        ];
    }

    private function getRaceSession(): RaceSession
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->route('raceSession');
    }

}
