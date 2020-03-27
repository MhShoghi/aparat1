<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ChannelNameRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
       return preg_match('~^[a-z-A-Z_][a-z-A-Z0-9\-_]{3,200}$~',$value,$matches);

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Channel name must be contain only this characters a-z, A-Z, 0-9, _ or -';
    }
}
