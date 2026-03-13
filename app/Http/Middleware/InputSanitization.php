<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class InputSanitization
{
    public function handle(Request $request, Closure $next): Response
    {
        // Sanitize input data
        $sanitized = $this->sanitizeInput($request->all());
        
        // Replace the request input with sanitized data
        $request->merge($sanitized);

        return $next($request);
    }

    private function sanitizeInput(array $input): array
    {
        foreach ($input as $key => $value) {
            if (is_string($value)) {
                // Remove potentially harmful characters
                $value = trim($value);
                
                // Strip HTML tags for non-rich text fields
                if ($this->shouldStripHtml($key)) {
                    $value = strip_tags($value);
                }
                
                // Convert special characters to HTML entities
                $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                
                $input[$key] = $value;
            } elseif (is_array($value)) {
                $input[$key] = $this->sanitizeInput($value);
            }
        }

        return $input;
    }

    private function shouldStripHtml(string $key): bool
    {
        // Fields that should not allow HTML
        $noHtmlFields = [
            'name', 'first_name', 'last_name', 'middle_name',
            'email', 'contact_number', 'address',
            'category', 'status', 'role',
            'search', 'min_price', 'max_price',
            'rating', 'comment' // Allow basic text only
        ];

        return in_array($key, $noHtmlFields) || !str_contains($key, 'description');
    }
}
