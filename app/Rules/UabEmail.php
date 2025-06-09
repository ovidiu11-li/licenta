<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UabEmail implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^[a-z]+\.[a-z]+\.info\d+@uab\.ro$/', $value)) {
            $fail('Adresa de e-mail trebuie să fie de forma nume.complet.infoXX@uab.ro (unde XX este un număr)');
        }
    }
} 