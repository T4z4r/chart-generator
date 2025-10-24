@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Chart Generator</h2>

    <form action="{{ route('charts.upload') }}" method="POST" enctype="multipart/form-data" class="row g-3 mb-4">
        @csrf
        <div class="col-md-3">
            <select name="chart_type" class="form-select" required>
                <option value="pie">Pie</option>
                <option value="bar">Bar</option>
                <option value="line">Line</option>
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

    <p><strong>Download Templates:</strong>
        <a href="{{ route('charts.template','pie') }}" class="btn btn-outline-secondary btn-sm">Pie</a>
        <a href="{{ route('charts.template','bar') }}" class="btn btn-outline-secondary btn-sm">Bar</a>
        <a href="{{ route('charts.template','line') }}" class="btn btn-outline-secondary btn-sm">Line</a>
    </p>

    <div class="row mt-4">
        @forelse($charts as $chart)
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{{ $chart->title }}</h5>
                    <p class="card-text">Type: {{ ucfirst($chart->chart_type) }}</p>
                    <a href="{{ route('charts.show', $chart->id) }}" class="btn btn-sm btn-primary">View Chart</a>
                </div>
            </div>
        </div>
        @empty
        <p>No charts generated yet.</p>
        @endforelse
    </div>
</div>
@endsection
