<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;

class TimeValidation implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Validar que el valor sea una hora en formato HH:MM
        return preg_match('/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El campo :attribute no es una hora válida.';
    }
}