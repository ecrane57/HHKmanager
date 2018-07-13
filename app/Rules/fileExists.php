<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Storage;

class fileExists implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($disk)
    {
        $this->disk = $disk;
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
        if(Storage::disk($this->disk)->exists($value)){
	        return true;
        }else{
	        return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute does not exist.';
    }
}
