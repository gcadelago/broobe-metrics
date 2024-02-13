<?php

namespace App\Services\MetricHistoryRun;

use App\Models\MetricHistoryRun;
use Illuminate\Database\Eloquent\Collection;

class MetricHistoryRunService
{
    /**
     * Lists metric history
     *
     * @param bool $paginate    Flag to indicate if the results should be paginated or not
     * @param int  $rowsPerPage Number of records per page
     *
     * @return Collection
     */
    public function list(
        bool $paginate = true,
        int $rowsPerPage = 25,
    )
    {
        $metrics = MetricHistoryRun::latest()->get();
        return $paginate
            ? $metrics->paginate($rowsPerPage)
            : $metrics->get();
    }

    public static function save(array $params)
    {
        // Extract array parameters
        $urlMetric  = $params['url'];
        $strategyId = $params['strategyId'];
        $categories = $params['categories'];

        MetricHistoryRun::create([
            'url'                   => $urlMetric,
            'strategy_id'           => $strategyId,
            'accessibility_metric'  => $categories['Accessibility'] ?? null,
            'pwa_metric'            => $categories['PWA'] ?? null,
            'performance_metric'    => $categories['Performance'] ?? null,
            'seo_metric'            => $categories['SEO'] ?? null,
            'best_practices_metric' => $categories['Best Practices'] ?? null
        ]);
    }

    public static function makeUrl(array $params)
    {
        try {
            ['url' => $url, 'strategy' => $strategy, 'categories' => $categories] = $params;

            $baseUrl = 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url='.$url.'&key='.env('PAGES_SPEED_ONLINE_KEY').'&strategy='.$strategy;
            foreach ($categories as $category) {
                $baseUrl .= '&category='.$category;
            }

            return $baseUrl;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
