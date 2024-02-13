<?php

namespace App\Enums;

class MetricCategoryEnum
{
    const Accessibility = 'ACCESSIBILITY';
    const PWA           = 'PWA';
    const Performance   = 'PERFORMANCE';
    const SEO           = 'SEO';
    const BestPractices = 'BEST_PRACTICES';

    public static function isValidCategory($category)
    {
        $reflection = new \ReflectionClass(__CLASS__);
        return in_array($category, array_values($reflection->getConstants()));
    }
}
