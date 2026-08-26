<?php

namespace App\Features\ShopOwner\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TimeAfter implements ValidationRule
{
    public function validate(
        string $attribute,
        mixed $value,
        Closure $fail
    ): void {
        $index = explode('.', $attribute)[1];

        $from = request()->input("working_hours.$index.from");

        if ($from !== null && $value <= $from) {
            $fail('وقت الانتهاء يجب أن يكون بعد وقت البداية.');
        }
    }
}