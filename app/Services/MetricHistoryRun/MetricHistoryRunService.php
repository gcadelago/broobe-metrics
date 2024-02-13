<?php

namespace App\Services\MetricHistoryRun;

use App\Models\MetricHistoryRun;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Collection;

class MetricHistoryRunService
{
    /**
     * Lists metric history
     *
     * @return Collection $metrics - Collection containing all the metric history records.
     */
    public static function list()
    {
        try {
            $metrics = MetricHistoryRun::all();
            return $metrics;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Saves a metric run in a database
     *
     * @param array $params
     *  - (string) 'url'       - The URL for which the metrics run.
     *  - (int) 'strategyId'   - The ID of the strategy used for the metrics.
     *  - (array) 'categories' - Array containing metric categories and their corresponding values. ['Accessibility', 'PWA', 'Performance', 'SEO', 'Best Practices'].
     * @return void
     */
    public static function save(array $params)
    {
        // Extract array parameters
        $categories = $params['categories'];

        MetricHistoryRun::create([
            'url'                   => $params['url'],
            'strategy_id'           => $params['strategyId'],
            'accessibility_metric'  => $categories['Accessibility'] ?? null,
            'pwa_metric'            => $categories['PWA'] ?? null,
            'performance_metric'    => $categories['Performance'] ?? null,
            'seo_metric'            => $categories['SEO'] ?? null,
            'best_practices_metric' => $categories['Best Practices'] ?? null
        ]);
    }

    /**
     * URL assembly for use with google pagespeedonline
     *
     * @param array $params An array containing the following keys:
     *   - (string) 'url'       - The URL to analyze using PageSpeed.
     *   - (string) 'strategy'  - The strategy to use for the analysis. ['DESKTOP', 'MOBILE'].
     *   - (array) 'categories' - Array containing the categories of metrics to include in the analysis. ['Accessibility', 'PWA', 'Performance', 'SEO', 'Best Practices'].
     * @return string $finalUrl - The URL of the PageSpeed endpoint.
     * @throws \Exception       - Thrown with a corresponding error message.
     */
    public static function makeUrl(array $params)
    {
        try {
            ['url' => $url, 'strategy' => $strategy, 'categories' => $categories] = $params;

            $finalUrl = 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url='.$url.'&key='.env('PAGES_SPEED_ONLINE_KEY').'&strategy='.$strategy;
            foreach ($categories as $category) {
                $finalUrl .= '&category='.$category;
            }

            return $finalUrl;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Search for metrics on the pagesspeed google page
     *
     * @param string $url           - URL of the page to obtain your metrics
     * @return json $categoryScores - Scores category obtained from the PageSpeed.
     * @throws \Exception           - Thrown with a corresponding error message.
     */
    public static function findMetricsPageSpeed(string $url) {
        try {
            $categoryScores = [];
            $client         = new Client();
            $response       = $client->get($url);
            $data           = json_decode($response->getBody()->getContents(), true);

            if (isset($data['lighthouseResult']['categories'])) {
                foreach ($data['lighthouseResult']['categories'] as $category) {
                    $categoryScores[$category['title']] = $category['score'];
                }

                return response()->json($categoryScores, 200);
            } else {
                return response()->json(['error' => 'No categories were found in the response.'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
