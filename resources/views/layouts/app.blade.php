<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ChartForge</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/svg+xml" href="/logo.svg">
    @vite('resources/css/app.css')
</head>
<body class="bg-light d-flex flex-column min-vh-100">
    <header class="shadow-sm mb-4">
        <div class="container d-flex align-items-center py-3">
            <img src="/logo.svg" alt="ChartForge Logo" width="40" height="40" class="me-3">
            <h1 class="h4 mb-0">ChartForge</h1>
        </div>
    </header>
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <footer class="bg-white shadow-sm mt-4">
        <div class="container py-3 text-center">
            <p>&copy; {{ date('Y') }} ChartForge. Version 1.0.0</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @yield('scripts')
</body>
</html>

