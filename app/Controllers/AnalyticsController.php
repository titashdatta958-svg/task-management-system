<?php namespace App\Controllers;

use App\Models\AnalyticsModel;

class AnalyticsController extends BaseController
{
    public function index()
    {
        $analyticsModel = new AnalyticsModel();

        // Read filters
        $year  = $this->request->getGet('year') ?? date('Y');
        $month = $this->request->getGet('month') ?? 'all';

        // Get performance data
        $performance = $analyticsModel->getPerformance($year, $month);

        return view('analytics/index', [
            'performance' => $performance,
            'year'        => $year,
            'month'       => $month
        ]);
    }
}
