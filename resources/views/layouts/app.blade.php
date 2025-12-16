<!DOCTYPE html>
<html>
<head>
    <title>Product CRUD</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ route('products.index') }}">Product CRUD</a>
    </div>
</nav>

<div class="container">
    @yield('content')
</div>

</body>
</html>
