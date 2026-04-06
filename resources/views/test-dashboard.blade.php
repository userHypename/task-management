<!DOCTYPE html>
<html>
<head>
    <title>Test Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container-fluid">
            <span class="navbar-brand">Task Manager - Test</span>
        </div>
    </nav>
    
    <div class="container mt-4">
        <h1>Dashboard Test</h1>
        <p>Logged in as: <strong>{{ auth()->user()->name ?? 'Not logged in' }}</strong></p>
        <p>Role: <strong>{{ auth()->user()->role ?? 'N/A' }}</strong></p>
        
        @if(auth()->check())
            <div class="alert alert-success">
                Authentication is working!
            </div>
        @else
            <div class="alert alert-danger">
                User is not authenticated. <a href="/login">Login here</a>
            </div>
        @endif
    </div>
</body>
</html>
