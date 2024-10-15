<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\InvokableRule;

class TimeValidation implements InvokableRule
{
    /**
     * Determina si la regla de validación pasa.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  Closure $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        // Validar que el valor sea una hora en formato HH:MM
        if (!preg_match('/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/', $value)) {
            $fail('El campo :attribute no es una hora válida.');
        }
    }
}