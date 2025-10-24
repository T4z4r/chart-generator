<?php

namespace App\Http\Controllers;

use App\Models\Chart;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChartController extends Controller
{
    public function index()
    {
        $charts = Chart::latest()->paginate(9);
        return view('charts.index', compact('charts'));
    }

    public function downloadTemplate($type)
    {
        $path = storage_path('app/templates/' . $type . '_template.xlsx');
        if (!file_exists($path)) abort(404, 'Template not found.');
        return response()->download($path, $type . '_template.xlsx');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'chart_type' => 'required|in:pie,bar,line',
            'title' => 'nullable|string|max:255',
        ]);

        if ($request->has('table_data')) {
            $request->validate(['table_data' => 'required|json']);
            $rows = json_decode($request->table_data, true);
        } else {
            $request->validate(['file' => 'required|file|mimes:xlsx,xls,csv']);
            $file = $request->file('file');
            $filename = Str::random(10) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads', $filename);

            $rows = [];
            if (in_array($file->getClientOriginalExtension(), ['xlsx', 'xls'])) {
                $rows = Excel::toArray([], $file)[0] ?? [];
            } else {
                $rows = array_map('str_getcsv', file($file->getRealPath()));
            }
        }

        $rows = array_filter($rows, fn($r) => array_filter($r, fn($v) => $v !== null && $v !== ''));

        $parsed = $this->parseRows($request->chart_type, $rows);

        $chart = Chart::create([
            'user_id' => auth()->id(),
            'title' => $request->title ?: ucfirst($request->chart_type) . ' Chart',
            'chart_type' => $request->chart_type,
            'data' => $parsed,
            'file_path' => isset($path) ? $path : null,
        ]);

        return redirect()->route('charts.show', $chart->id)
            ->with('success', 'Chart generated successfully!');
    }

    protected function parseRows($type, $rows)
    {
        $rows = array_values($rows);
        if (empty($rows)) return ['labels' => [], 'datasets' => []];

        if ($type === 'pie') {
            $labels = [];
            $values = [];
            foreach ($rows as $r) {
                $labels[] = (string)($r[0] ?? '');
                $values[] = floatval($r[1] ?? 0);
            }
            return ['labels' => $labels, 'datasets' => [['data' => $values]]];
        }

        // For bar/line charts
        $header = array_map('strval', $rows[0]);
        $labels = [];
        $series = [];
        for ($i = 1; $i < count($header); $i++) {
            $series[$i] = ['label' => $header[$i], 'data' => []];
        }

        for ($r = 1; $r < count($rows); $r++) {
            $labels[] = (string)($rows[$r][0] ?? '');
            for ($c = 1; $c < count($header); $c++) {
                $series[$c]['data'][] = floatval($rows[$r][$c] ?? 0);
            }
        }

        return ['labels' => $labels, 'datasets' => array_values($series)];
    }

    public function show(Chart $chart)
    {
        return view('charts.show', compact('chart'));
    }
}
