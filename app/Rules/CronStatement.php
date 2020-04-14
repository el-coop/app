<?php

namespace App\Rules;

use Cron\CronExpression;
use Illuminate\Contracts\Validation\Rule;

class CronStatement implements Rule {

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value) {
        return CronExpression::isValidExpression($value);

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message() {
        return __('validation.cron');
    }
}
