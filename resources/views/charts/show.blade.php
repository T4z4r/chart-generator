@extends('layouts.app')

@section('content')
<div class="container py-4 text-center">
    <h3>{{ $chart->title }}</h3>
    <p class="text-muted">Type: {{ ucfirst($chart->chart_type) }}</p>

    <canvas id="chartCanvas" width="800" height="400" class="mx-auto"></canvas>

    <div class="mt-4">
        <button class="btn btn-success" id="downloadImage">Download Chart as Image</button>
        <a href="{{ route('charts.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
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
        const chartData = @json($chart->data);
        const originalType = '{{ $chart->chart_type }}';
        const type = originalType === 'area' ? 'line' : originalType;

        const ctx = document.getElementById('chartCanvas').getContext('2d');

        @php
        $defaultOptions = [
            'responsive' => true,
            'plugins' => [
                'legend' => ['position' => 'top'],
                'title' => ['display' => false]
            ]
        ];
        $chartOptions = array_merge($defaultOptions, $chart->data['options'] ?? []);
        @endphp

        const chartOptions = @json($chartOptions);

        const chart = new Chart(ctx, {
            type: type,
            data: {
                labels: chartData.labels,
                datasets: chartData.datasets.map((d, i) => ({
                    label: d.label || `Series ${i+1}`,
                    data: d.data,
                    backgroundColor: [
                        '#4e73df', '#1cc88a', '#36b9cc',
                        '#f6c23e', '#e74a3b', '#858796'
                    ],
                    borderWidth: 1,
                    fill: originalType === 'area' ? true : (type === 'line' ? false : true)
                }))
            },
            options: chartOptions
        });

        document.getElementById('downloadImage').addEventListener('click', function() {
            const link = document.createElement('a');
            link.download = '{{ Str::slug($chart->title ?: 'chart') }}.png';
            link.href = chart.toBase64Image();
            link.click();
        });
    });
</script>
@endsection

