<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetMetricsRequest;
use App\Http\Requests\SaveMetricsRequest;
use App\Models\Category;
use App\Models\MetricHistoryRun;
use App\Models\Strategy;
use App\Services\MetricHistoryRun\MetricHistoryRunService;
use Illuminate\Http\Request;
class MetricHistoryRunController extends Controller
{
    /**
     * Index view to search and list metrics
     *
     * @return View - Index view for searching and listing metrics.
     */
    public function index()
    {
        $categories = Category::all();
        $strategies = Strategy::all();
        return view('home', compact('categories', 'strategies'));
    }

    /**
     * @OA\Get(
     *     path="/api/list-history-metrics",
     *     tags={"History Metric Run"},
     *     summary="List metric history",
     *     @OA\Response(
     *       response=200,
     *       description="Returns the list of metrics",
     *       @OA\JsonContent(
     *         type="object",
     *         example={"expired": false}
     *       )
     *     ),
     *     @OA\Response(
     *       response=500,
     *       description="Error listing metric history",
     *       @OA\JsonContent(
     *         type="object",
     *         example={"message":"Error listing metric history"}
     *       )
     *     ),
     * )
     *
     * List metric history
     *
     * @return MetricHistoryRun[] $metrics - Collection of metric history data.
     */
    public function metricHistory()
    {
        $metrics = MetricHistoryRunService::list();

        return $metrics;
    }

    /**
     * @OA\Get(
     *     path="/api/get-metrics",
     *     tags={"History Metric Run"},
     *     summary="Get the metrics according to url",
     *     @OA\Parameter(
     *       name="url",
     *       in="query",
     *       description="The URL of the page to obtain metrics",
     *       required=true,
     *       @OA\Schema(
     *         type="string"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="strategy",
     *       in="query",
     *       description="The strategy to use for obtaining metrics",
     *       required=true,
     *       @OA\Schema(
     *         type="string"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="categories",
     *       in="query",
     *       description="The categories of metrics to obtain",
     *       required=true,
     *       @OA\Schema(
     *         type="array",
     *         @OA\Items(
     *           type="string",
     *           enum={"ACCESSIBILITY", "PWA", "PERFORMANCE", "SEO", "BEST PRACTICES"}
     *         )
     *       )
     *     ),
     *     @OA\Response(
     *       response=200,
     *       description="Returns the metrics for the specified URL",
     *       @OA\JsonContent(
     *         type="object",
     *         example={"Performance": 0.75, "Accessibility": 0.8, "SEO": 0.9}
     *       )
     *     ),
     *     @OA\Response(
     *       response=500,
     *       description="Internal server error",
     *       @OA\JsonContent(
     *         type="object",
     *         example={"error": "Internal server error"}
     *       )
     *     )
     * )
     * Get the metrics according to url
     *
     * @param Request $request - Request containing the metrics data.
     * @return void
     */
    public function getMetrics(GetMetricsRequest $request)
    {
        $urlMetric = MetricHistoryRunService::makeUrl([
            'url'        => $request->url,
            'categories' => $request->categories,
            'strategy'   => $request->strategy
        ]);

        try {
            return MetricHistoryRunService::findMetricsPageSpeed($urlMetric);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/save-metrics",
     *     tags={"History Metric Run"},
     *     summary="Saves metrics in database",
     *     description="Endpoint to save metrics in the database.",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Metrics data to be saved",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"url", "strategy", "categories"},
     *                 @OA\Property(
     *                     property="url",
     *                     type="string",
     *                     description="The URL for which metrics are being saved"
     *                 ),
     *                 @OA\Property(
     *                     property="strategy",
     *                     type="string",
     *                     description="The strategy associated with the metrics"
     *                 ),
     *                 @OA\Property(
     *                     property="categories",
     *                     type="object",
     *                     description="Object containing metric categories with scores",
     *                     @OA\Property(
     *                         property="Accessibility",
     *                         type="number",
     *                         format="float",
     *                         description="Accessibility score value"
     *                     ),
     *                     @OA\Property(
     *                         property="PWA",
     *                         type="number",
     *                         format="float",
     *                         description="PWA score value"
     *                     ),
     *                     @OA\Property(
     *                         property="Performance",
     *                         type="number",
     *                         format="float",
     *                         description="Performance score value"
     *                     ),
     *                     @OA\Property(
     *                         property="SEO",
     *                         type="number",
     *                         format="float",
     *                         description="SEO score value"
     *                     ),
     *                     @OA\Property(
     *                         property="Best Practices",
     *                         type="number",
     *                         format="float",
     *                         description="Best Practices score value"
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success response",
     *         @OA\JsonContent(
     *             type="object",
     *             example={"message": "Metrics saved successfully"}
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             example={"error": "Error message"}
     *         )
     *     )
     * )
     * Saves metrics in database
     *
     * @param Request $request - Request containing the metrics data.
     * @return json            - JSON response indicating the status of the save operation.
     * @throws \Exception      - Thrown with a corresponding error message.
     */
    public function saveMetrics(SaveMetricsRequest $request)
    {
        try {
            $strategyId = Strategy::where('name', $request->strategy)->first()->id;

            MetricHistoryRunService::save([
                'url'        => $request->url,
                'strategyId' => $strategyId,
                'categories' => $request->categories
            ]);

            return response()->json('Save successfuly', 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
