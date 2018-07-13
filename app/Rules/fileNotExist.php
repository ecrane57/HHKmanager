<?php

namespace App\Rules;
use Storage;

use Illuminate\Contracts\Validation\Rule;

class fileNotExist implements Rule
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
	        return false;
        }else{
	        return true;
        }

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'A site already exists at this location';
    }
}
