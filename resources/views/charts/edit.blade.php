@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Edit Chart: {{ $chart->title }}</h2>

    <form action="{{ route('charts.update', $chart->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <select name="chart_type" class="form-select" required>
                    <option value="pie" {{ $chart->chart_type == 'pie' ? 'selected' : '' }}>Pie</option>
                    <option value="bar" {{ $chart->chart_type == 'bar' ? 'selected' : '' }}>Bar</option>
                    <option value="line" {{ $chart->chart_type == 'line' ? 'selected' : '' }}>Line</option>
                    <option value="doughnut" {{ $chart->chart_type == 'doughnut' ? 'selected' : '' }}>Doughnut</option>
                    <option value="area" {{ $chart->chart_type == 'area' ? 'selected' : '' }}>Area</option>
                    <option value="radar" {{ $chart->chart_type == 'radar' ? 'selected' : '' }}>Radar</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="package_type" class="form-select" required>
                    <option value="chartjs" {{ $chart->package_type == 'chartjs' ? 'selected' : '' }}>Chart.js</option>
                    <option value="apex" {{ $chart->package_type == 'apex' ? 'selected' : '' }}>ApexCharts</option>
                    <option value="highchart" {{ $chart->package_type == 'highchart' ? 'selected' : '' }}>Highcharts</option>
                    <option value="echart" {{ $chart->package_type == 'echart' ? 'selected' : '' }}>ECharts</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="title" placeholder="Chart title" class="form-control" value="{{ $chart->title }}">
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-outline-primary" id="addRow">Add Row</button>
                <button type="button" class="btn btn-outline-danger" id="removeRow">Remove Row</button>
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-outline-primary" id="addCol">Add Column</button>
                <button type="button" class="btn btn-outline-danger" id="removeCol">Remove Column</button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable">
                <thead>
                    <tr>
                        <th>Label</th>
                        @if(in_array($chart->chart_type, ['pie', 'doughnut']))
                            <th>Value</th>
                        @else
                            @foreach($chart->data['datasets'] as $dataset)
                                <th>{{ $dataset['label'] ?? 'Series ' . ($loop->index + 1) }}</th>
                            @endforeach
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @php
                        $labels = $chart->data['labels'] ?? [];
                        $datasets = $chart->data['datasets'] ?? [];
                        $maxRows = count($labels) ?: 1;
                    @endphp
                    @for($i = 0; $i < $maxRows; $i++)
                        <tr>
                            <td contenteditable="true">{{ $labels[$i] ?? 'Category ' . ($i + 1) }}</td>
                            @if(in_array($chart->chart_type, ['pie', 'doughnut']))
                                <td contenteditable="true">{{ $datasets[0]['data'][$i] ?? 0 }}</td>
                            @else
                                @foreach($datasets as $dataset)
                                    <td contenteditable="true">{{ $dataset['data'][$i] ?? 0 }}</td>
                                @endforeach
                            @endif
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        <div class="mb-3">
            <label class="form-label">Dataset Colors (comma-separated hex colors, e.g., #ff0000,#00ff00)</label>
            <input type="text" name="colors" class="form-control" value="{{ is_array($chart->colors) ? implode(',', $chart->colors) : '' }}">
        </div>

        <input type="hidden" name="table_data" id="tableData">
        <button type="submit" class="btn btn-primary">Update Chart</button>
        <a href="{{ route('charts.show', $chart->id) }}" class="btn btn-outline-secondary">Cancel</a>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('dataTable');
    const tbody = table.querySelector('tbody');
    const thead = table.querySelector('thead tr');
    const tableDataInput = document.getElementById('tableData');

    function updateTableData() {
        const data = [];
        const headers = Array.from(thead.querySelectorAll('th')).map(th => th.textContent.trim());
        data.push(headers);

        const rows = tbody.querySelectorAll('tr');
        rows.forEach(row => {
            const rowData = Array.from(row.querySelectorAll('td')).map(td => td.textContent.trim());
            data.push(rowData);
        });

        tableDataInput.value = JSON.stringify(data);
    }

    // Add row
    document.getElementById('addRow').addEventListener('click', function() {
        const rowCount = tbody.querySelectorAll('tr').length;
        const colCount = thead.querySelectorAll('th').length;
        const newRow = document.createElement('tr');
        for (let i = 0; i < colCount; i++) {
            const td = document.createElement('td');
            td.contentEditable = 'true';
            td.textContent = i === 0 ? `Category ${rowCount + 1}` : '0';
            newRow.appendChild(td);
        }
        tbody.appendChild(newRow);
        updateTableData();
    });

    // Remove row
    document.getElementById('removeRow').addEventListener('click', function() {
        if (tbody.querySelectorAll('tr').length > 1) {
            tbody.lastElementChild.remove();
            updateTableData();
        }
    });

    // Add column
    document.getElementById('addCol').addEventListener('click', function() {
        const colCount = thead.querySelectorAll('th').length;
        const header = document.createElement('th');
        header.textContent = `Series ${colCount}`;
        thead.appendChild(header);

        const rows = tbody.querySelectorAll('tr');
        rows.forEach(row => {
            const td = document.createElement('td');
            td.contentEditable = 'true';
            td.textContent = '0';
            row.appendChild(td);
        });
        updateTableData();
    });

    // Remove column
    document.getElementById('removeCol').addEventListener('click', function() {
        if (thead.querySelectorAll('th').length > 1) {
            const thIndex = thead.querySelectorAll('th').length - 1;
            thead.querySelectorAll('th')[thIndex].remove();

            const rows = tbody.querySelectorAll('tr');
            rows.forEach(row => {
                row.querySelectorAll('td')[thIndex].remove();
            });
            updateTableData();
        }
    });

    // Update data on edit
    table.addEventListener('input', updateTableData);

    // Initial update
    updateTableData();
});
</script>
@endsection
