<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

const MINUTES_IN_HOUR = 60;

readonly class TimeMultipleOf implements ValidationRule
{
    public function __construct(
        private int $multiple
    )
    {
    }

    /**
     * Run the validation rule.
     *
     * @param Closure(string, ?string=): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $time = \DateTime::createFromFormat('H:i', $value);
        if (!$time) {
            $fail("O campo {$attribute} não é um horário válido.");
            return;
        }

        $hours = (int)$time->format('H');
        $minutes = (int)$time->format('i');
        $totalMinutes = ($hours * MINUTES_IN_HOUR) + $minutes;

        if ($totalMinutes % $this->multiple !== 0) {
            $fail("O campo {$attribute} precisa ser múltiplo de {$this->multiple}");
        }
    }
}
