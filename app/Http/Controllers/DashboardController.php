<?php

namespace App\Http\Controllers;

use App\Models\Teras;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Define the statuses in one place
        $statuses = ['Belum Mula', 'Dalam Pelaksanaan', 'Projek Sakit', 'Ditangguh', 'Digugurkan', 'Tercapai'];
        $filterTeras = request('filter_teras');

        // Get all teras with nested relationships
        $terasQuery = Teras::with(['langkah.programs.programRows']);
        if ($filterTeras) {
            $terasQuery->where('name', $filterTeras);
        }
        
        $teras = $terasQuery->get();

        $allTerasNames = Teras::pluck('name')->unique()->sort()->values();

        // Aggregate status counts per teras
        $tableData = [];
        foreach ($teras as $t) {
            $totalRows = $t->langkah->flatMap->programs->flatMap->programRows->count();
            $statusCounts = $t->langkah->flatMap->programs->flatMap->programRows->groupBy('status')->map->count();
            
            $row = ['name' => $t->name, 'total' => $totalRows];

            foreach ($statuses as $status) {
                $count = $statusCounts[$status] ?? 0;
                $row[$status] = $count;
                $row[$status . '_percent'] = $totalRows > 0 ? round(($count / $totalRows) * 100, 2) : 0;
            }

            $tableData[] = $row;
        }

        // Calculate overall status counts
        $allProgramRows = $teras->flatMap->langkah->flatMap->programs->flatMap->programRows;
        $overallCounts = $allProgramRows->groupBy('status')->map->count();
        $overallTotal = $allProgramRows->count();
        $overallPercentages = collect($statuses)->mapWithKeys(function ($status) use ($overallCounts, $overallTotal) {
            $count = $overallCounts[$status] ?? 0;
            return [$status => $overallTotal > 0 ? round(($count / $overallTotal) * 100, 2) : 0];
        });

        // Prepare chart data
        $terasChartData = collect($tableData)->mapWithKeys(function ($row) use ($statuses) {
            return [
                $row['name'] => [
                    'labels' => $statuses,
                    'data' => array_map(fn($status) => $row[$status], $statuses),
                ],
            ];
        });

        $overallChartData = [
            'labels' => $statuses,
            'data' => array_map(fn($status) => $overallCounts[$status] ?? 0, $statuses),
        ];

        // Prepare bar chart data (status counts per teras)
        $barChartData = [];
        foreach (Teras::with(['langkah.programs.programRows'])->get() as $t) {
            $statusCounts = $t->langkah->flatMap->programs->flatMap->programRows->groupBy('status')->map->count();
            $barChartData[$t->name] = collect($statuses)->mapWithKeys(function ($status) use ($statusCounts) {
                return [$status => $statusCounts[$status] ?? 0];
            })->all();
        }

        $barChartLabels = array_keys($barChartData); // Teras names
        $barChartDatasets = [];

        foreach ($statuses as $index => $status) {
            $barChartDatasets[] = [
                'label' => $status,
                'data' => array_map(fn($terasName) => $barChartData[$terasName][$status] ?? 0, $barChartLabels),
                'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'][$index % 6],
            ];
        }

        return view('dashboard.index', compact(
            'tableData',
            'terasChartData',
            'overallChartData',
            'overallPercentages',
            'statuses',
            'allTerasNames',
            'barChartLabels',
            'barChartDatasets'
        ));
    }
}

