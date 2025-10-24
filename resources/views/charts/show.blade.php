@extends('layouts.app')

@section('content')
<div class="container py-4 text-center">
    <h3>{{ $chart->title }}</h3>
    <p class="text-muted">Type: {{ ucfirst($chart->chart_type) }} | Library: {{ ucfirst($chart->package_type) }}</p>

    @if($chart->package_type === 'chartjs')
        <canvas id="chartCanvas" width="800" height="400" class="mx-auto"></canvas>
    @else
        <div id="chartCanvas" class="mx-auto"></div>
    @endif

    <div class="mt-4">
        <button class="btn btn-success" id="downloadImage">Download Chart as Image</button>
        <a href="{{ route('charts.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>
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

        const packageType = '{{ $chart->package_type }}';
        const chartData = @json($chart->data);
        const originalType = '{{ $chart->chart_type }}';
        const customColors = @json($chart->colors ?? []);
        const defaultColors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'];

        if (packageType === 'chartjs') {
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
                        backgroundColor: customColors.length > 0 ? customColors : defaultColors,
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
        } else if (packageType === 'apex') {
            // Basic ApexCharts implementation
            let series = [];
            let options = {
                chart: {
                    type: originalType === 'area' ? 'area' : originalType,
                    height: 400
                },
                colors: customColors.length > 0 ? customColors : defaultColors,
                xaxis: {
                    categories: chartData.labels
                }
            };

            if (['pie', 'doughnut'].includes(originalType)) {
                series = chartData.datasets[0].data;
                options = {
                    series: series,
                    chart: {
                        type: 'pie',
                        height: 400
                    },
                    colors: customColors.length > 0 ? customColors : defaultColors,
                    labels: chartData.labels
                };
            } else {
                series = chartData.datasets.map((d, i) => ({
                    name: d.label || `Series ${i+1}`,
                    data: d.data
                }));
                options.series = series;
            }

            const chart = new ApexCharts(document.querySelector("#chartCanvas"), options);
            chart.render();

            document.getElementById('downloadImage').addEventListener('click', function() {
                chart.dataURI().then(({ imgURI }) => {
                    const link = document.createElement('a');
                    link.download = '{{ Str::slug($chart->title ?: 'chart') }}.png';
                    link.href = imgURI;
                    link.click();
                });
            });
        } else if (packageType === 'highchart') {
            // Basic Highcharts implementation
            let series = [];
            let options = {
                chart: {
                    type: originalType === 'area' ? 'area' : originalType,
                    height: 400
                },
                colors: customColors.length > 0 ? customColors : defaultColors,
                xAxis: {
                    categories: chartData.labels
                },
                series: chartData.datasets.map((d, i) => ({
                    name: d.label || `Series ${i+1}`,
                    data: d.data
                }))
            };

            if (['pie', 'doughnut'].includes(originalType)) {
                options = {
                    chart: {
                        type: 'pie',
                        height: 400
                    },
                    colors: customColors.length > 0 ? customColors : defaultColors,
                    series: [{
                        name: 'Data',
                        data: chartData.labels.map((label, i) => ({
                            name: label,
                            y: chartData.datasets[0].data[i]
                        }))
                    }]
                };
            }

            Highcharts.chart('chartCanvas', options);

            document.getElementById('downloadImage').addEventListener('click', function() {
                const chart = Highcharts.charts[Highcharts.charts.length - 1];
                chart.exportChart({
                    type: 'image/png',
                    filename: '{{ Str::slug($chart->title ?: 'chart') }}'
                });
            });
        } else if (packageType === 'echart') {
            // Basic ECharts implementation
            const chartDom = document.getElementById('chartCanvas');
            const myChart = echarts.init(chartDom);

            let option = {
                tooltip: {},
                legend: {
                    data: chartData.datasets.map((d, i) => d.label || `Series ${i+1}`)
                },
                xAxis: {
                    type: 'category',
                    data: chartData.labels
                },
                yAxis: {
                    type: 'value'
                },
                series: chartData.datasets.map((d, i) => ({
                    name: d.label || `Series ${i+1}`,
                    type: originalType === 'area' ? 'line' : originalType,
                    data: d.data,
                    itemStyle: {
                        color: customColors[i] || defaultColors[i]
                    }
                }))
            };

            if (['pie', 'doughnut'].includes(originalType)) {
                option = {
                    tooltip: {
                        trigger: 'item'
                    },
                    legend: {
                        orient: 'vertical',
                        left: 'left'
                    },
                    series: [{
                        name: 'Data',
                        type: 'pie',
                        radius: '50%',
                        data: chartData.labels.map((label, i) => ({
                            value: chartData.datasets[0].data[i],
                            name: label,
                            itemStyle: {
                                color: customColors[i] || defaultColors[i]
                            }
                        }))
                    }]
                };
            }

            myChart.setOption(option);

            document.getElementById('downloadImage').addEventListener('click', function() {
                const url = myChart.getDataURL({
                    type: 'png',
                    pixelRatio: 2,
                    backgroundColor: '#fff'
                });
                const link = document.createElement('a');
                link.download = '{{ Str::slug($chart->title ?: 'chart') }}.png';
                link.href = url;
                link.click();
            });
        } else {
            document.getElementById('chartCanvas').innerHTML = '<p>Chart library not implemented yet.</p>';
        }
    });
</script>
@endsection

