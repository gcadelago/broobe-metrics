<?php

namespace App\Http\Controllers;

use App\Models\MetricHistoryRun;
use App\Models\Strategy;
use App\Services\MetricHistoryRun\MetricHistoryRunService;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class MetricHistoryRunController extends Controller
{
    public function metricHistory()
    {
        $metrics = MetricHistoryRun::all();

        return $metrics;
    }

    public function getMetrics(Request $request)
    {
        $urlMetric = $request->url;
        $url = MetricHistoryRunService::makeUrl([
            'url'        => $urlMetric,
            'categories' => $request->categories,
            'strategy'   => $request->strategy
        ]);
        $strategyId = Strategy::where('name', $request->strategy)->first()->id;

        try {
            $client = new Client();
            $response = $client->get($url);
            $data = json_decode($response->getBody()->getContents(), true);
            if (isset($data['lighthouseResult']['categories'])) {
                $categoryScores = [];
                foreach ($data['lighthouseResult']['categories'] as $category) {
                    $categoryScores[$category['title']] = $category['score'];
                }

                return response()->json($categoryScores, 200);
            } else {
                return response()->json(['error' => 'No se encontraron categorías en la respuesta.'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function saveMetrics(Request $request)
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
