@extends('layouts.app')

@section('content')
<div class="container py-5 text-center">
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chartData = @json($chart->data);
        const type = '{{ $chart->chart_type }}';

        const ctx = document.getElementById('chartCanvas').getContext('2d');

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
                    fill: type === 'line' ? false : true
                }))
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: false }
                }
            }
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

