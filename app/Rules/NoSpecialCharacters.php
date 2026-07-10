<?php
namespace App\Rules;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoSpecialCharacters implements ValidationRule {
    public function validate(string $attribute, mixed $value, Closure $fail): void {
        $pattern = '/[' . preg_quote('~!@#$%^&*()_+}{|":?><', '/') . ']/';
        if (preg_match($pattern, $value)) {
            $fail('Pesan tidak boleh mengandung karakter spesial ( simbol ).');
        }
    }
}
