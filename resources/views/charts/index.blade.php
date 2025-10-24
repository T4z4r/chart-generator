@extends('layouts.app')

@section('content')
<div class="container py-4">

    <ul class="nav nav-tabs custom-tabs mb-4" id="chartTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active d-flex align-items-center" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab">
                <i class="fas fa-home me-2"></i>Home
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link d-flex align-items-center" id="manual-tab" data-bs-toggle="tab" data-bs-target="#manual" type="button" role="tab">
                <i class="fas fa-edit me-2"></i>Manual Entry
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link d-flex align-items-center" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload" type="button" role="tab">
                <i class="fas fa-upload me-2"></i>Upload File
            </button>
        </li>
    </ul>

    <div class="tab-content" id="chartTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel">
            <div class="hero-section text-white py-5 mb-5 rounded">
                <div class="container text-center">
                    <img src="/logo.svg" alt="ChartForge Logo" width="120" height="120" class="mb-4">
                    <h1 class="display-4">Welcome to ChartForge</h1>
                    <p class="lead">Transform your data into beautiful, interactive charts in seconds.</p>
                    <p class="mb-4">Whether you're a data analyst, student, or business professional, ChartForge makes chart creation simple and fun.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="#manual" class="btn btn-light btn-lg" data-bs-toggle="tab">Get Started</a>
                        <a href="#upload" class="btn btn-outline-light btn-lg" data-bs-toggle="tab">Upload Data</a>
                    </div>
                </div>
            </div>

            <div class="container">
                <h2 class="text-center mb-4">Why Choose ChartForge?</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="feature-card text-center p-4 border rounded shadow-sm h-100">
                            <div class="mb-3">
                                <i class="fas fa-edit fa-3x text-purple-800"></i>
                            </div>
                            <h5>Easy Data Entry</h5>
                            <p>Enter your data directly into an editable table that works like Excel. No need for complex spreadsheets.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card text-center p-4 border rounded shadow-sm h-100">
                            <div class="mb-3">
                                <i class="fas fa-upload fa-3x text-purple-800"></i>
                            </div>
                            <h5>File Upload</h5>
                            <p>Upload Excel or CSV files for larger datasets. Supports multiple chart types seamlessly.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card text-center p-4 border rounded shadow-sm h-100">
                            <div class="mb-3">
                                <i class="fas fa-chart-bar fa-3x text-purple-800"></i>
                            </div>
                            <h5>Multiple Chart Types</h5>
                            <p>Create Pie, Bar, and Line charts with customizable colors and styles.</p>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-5">
                    <h3>Ready to Create Your First Chart?</h3>
                    <p>Choose how you'd like to input your data and start visualizing!</p>
                    <div class="d-flex justify-content-center gap-3 mt-3">
                        <button class="btn btn-primary" data-bs-toggle="tab" data-bs-target="#manual">Manual Entry</button>
                        <button class="btn btn-outline-primary" data-bs-toggle="tab" data-bs-target="#upload">File Upload</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="manual" role="tabpanel">
            <form action="{{ route('charts.upload') }}" method="POST" class="mb-4">
                @csrf
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <select name="chart_type" class="form-select" required>
                            <option value="pie">Pie</option>
                            <option value="bar">Bar</option>
                            <option value="line">Line</option>
                            <option value="doughnut">Doughnut</option>
                            <option value="area">Area</option>
                            <option value="radar">Radar</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="package_type" class="form-select" required>
                            <option value="chartjs">Chart.js</option>
                            <option value="apex">ApexCharts</option>
                            <option value="highchart">Highcharts</option>
                            <option value="echart">ECharts</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="title" placeholder="Chart title" class="form-control">
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
                                <th>Series 1</th>
                                <th>Series 2</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td contenteditable="true">Category 1</td>
                                <td contenteditable="true">10</td>
                                <td contenteditable="true">20</td>
                            </tr>
                            <tr>
                                <td contenteditable="true">Category 2</td>
                                <td contenteditable="true">15</td>
                                <td contenteditable="true">25</td>
                            </tr>
                            <tr>
                                <td contenteditable="true">Category 3</td>
                                <td contenteditable="true">20</td>
                                <td contenteditable="true">30</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mb-3">
                    <label class="form-label">Dataset Colors (comma-separated hex colors, e.g., #ff0000,#00ff00)</label>
                    <input type="text" name="colors" class="form-control">
                </div>

                <input type="hidden" name="table_data" id="tableData">
                <button type="submit" class="btn btn-primary">Generate Chart</button>
            </form>
        </div>

        <div class="tab-pane fade" id="upload" role="tabpanel">
            <form action="{{ route('charts.upload') }}" method="POST" enctype="multipart/form-data" class="row g-3 mb-4">
                @csrf
                <div class="col-md-3">
                    <select name="chart_type" class="form-select" required>
                        <option value="pie">Pie</option>
                        <option value="bar">Bar</option>
                        <option value="line">Line</option>
                        <option value="doughnut">Doughnut</option>
                        <option value="area">Area</option>
                        <option value="radar">Radar</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="package_type" class="form-select" required>
                        <option value="chartjs">Chart.js</option>
                        <option value="apex">ApexCharts</option>
                        <option value="highchart">Highcharts</option>
                        <option value="echart">ECharts</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="title" placeholder="Chart title" class="form-control">
                </div>
                <div class="col-md-3">
                    <input type="file" name="file" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary w-100">Upload & Generate</button>
                </div>
            </form>
        </div>
    </div>

    <p><strong>Download Templates:</strong>
        <a href="{{ route('charts.template','pie') }}" class="btn btn-outline-secondary btn-sm">Pie</a>
        <a href="{{ route('charts.template','bar') }}" class="btn btn-outline-secondary btn-sm">Bar</a>
        <a href="{{ route('charts.template','line') }}" class="btn btn-outline-secondary btn-sm">Line</a>
        <a href="{{ route('charts.template','doughnut') }}" class="btn btn-outline-secondary btn-sm">Doughnut</a>
        <a href="{{ route('charts.template','area') }}" class="btn btn-outline-secondary btn-sm">Area</a>
        <a href="{{ route('charts.template','radar') }}" class="btn btn-outline-secondary btn-sm">Radar</a>
    </p>

    <div class="row mt-4">
        @forelse($charts as $chart)
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{{ $chart->title }}</h5>
                    <p class="card-text">Type: {{ ucfirst($chart->chart_type) }}</p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('charts.show', $chart->id) }}" class="btn btn-sm btn-primary">View</a>
                        <a href="{{ route('charts.edit', $chart->id) }}" class="btn btn-sm btn-secondary">Edit</a>
                        <form action="{{ route('charts.destroy', $chart->id) }}" method="POST" class="d-inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-chart-title="{{ $chart->title }}">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <p>No charts generated yet.</p>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle delete confirmation with SweetAlert
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('.delete-form');
            const chartTitle = this.getAttribute('data-chart-title');

            Swal.fire({
                title: 'Are you sure?',
                text: `You want to delete "${chartTitle}"? This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Show success alert if there's a success message
    @if(session('success'))
        Swal.fire({
            title: 'Success!',
            text: '{{ session('success') }}',
            icon: 'success',
            timer: 3000,
            showConfirmButton: false
        });
    @endif
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
