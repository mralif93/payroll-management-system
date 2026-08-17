<?php

namespace App\Services\Payroll;

use App\Models\StatutoryParameter;
use Illuminate\Support\Facades\Cache;

class StatutoryParameterResolver
{
    /**
     * Resolve effective statutory parameter payload for a given payroll date.
     */
    public function getParameter(string $category, string $parameterKey, ?string $payrollDate = null): array
    {
        $date = $payrollDate ?? now()->toDateString();
        $cacheKey = "statutory_{$category}_{$parameterKey}_{$date}";

        return Cache::remember($cacheKey, 3600, function () use ($category, $parameterKey, $date) {
            $parameter = StatutoryParameter::where('category', $category)
                ->where('parameter_key', $parameterKey)
                ->where('effective_from', '<=', $date)
                ->where('effective_to', '>=', $date)
                ->where('is_active', true)
                ->latest('effective_from')
                ->first();

            return $parameter ? ($parameter->value_payload ?? []) : [];
        });
    }

    /**
     * Clear cached parameters on updates.
     */
    public function flushCache(): void
    {
        Cache::flush();
    }
}
