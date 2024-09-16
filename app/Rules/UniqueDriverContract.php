<?php

declare(strict_types=1);

namespace App\Rules;

use App\Models\DriverContract;
use Carbon\Carbon;
use Closure;
use DateTime;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Translation\PotentiallyTranslatedString;
use Override;

class UniqueDriverContract implements DataAwareRule, ValidationRule, ValidatorAwareRule
{
    private array $data;

    private Validator $validator;

    public function __construct(private readonly ?DriverContract $except)
    {

    }

    /**
     * Run the validation rule.
     *
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     */
    #[Override]
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->validator->valid()) {
            return;
        }

        $startDate = new DateTime($this->data['start_date']);
        $endDate = $this->data['end_date'] ? new DateTime($this->data['end_date']) : null;

        $hasActiveContractQuery = DriverContract::query()
            ->whereDriverId($this->data['driver']);

        if ($this->except instanceof DriverContract) {
            $hasActiveContractQuery->whereNot('id', $this->except->id);
        }

        $endDate ??= Carbon::now();
        $hasActiveContract = $hasActiveContractQuery
            ->whereRaw('COALESCE(end_date, NOW()) >= ?', [$startDate])
            ->where('start_date', '<=', $endDate)
            ->exists();

        if ($hasActiveContract) {
            $fail('validation.unique-driver-contract')->translate();
        }
    }

    #[Override]
    public function setData(array $data): UniqueDriverContract|static
    {
        $this->data = $data;

        return $this;
    }

    #[Override]
    public function setValidator(Validator $validator): UniqueDriverContract|static
    {
        $this->validator = $validator;

        return $this;
    }
}
