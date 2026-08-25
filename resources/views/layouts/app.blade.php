<!DOCTYPE html>
<html>

<head>

    <title>Inventory CRUD</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          rel="stylesheet">

</head>


<body>

<nav class="navbar navbar-dark bg-dark mb-4">

    <div class="container">

        <a class="navbar-brand"
           href="{{ route('dashboard') }}">
            Inventory CRUD
        </a>


        <div class="navbar-nav d-flex flex-row gap-3">

            <a class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active' : '' }}"
               href="{{ route('dashboard') }}">
                Dashboard
            </a>


            <a class="nav-link text-white {{ request()->routeIs('products.*') ? 'active' : '' }}"
               href="{{ route('products.index') }}">
                Products
            </a>


            <a class="nav-link text-white {{ request()->routeIs('categories.*') ? 'active' : '' }}"
               href="{{ route('categories.index') }}">
                Categories
            </a>


            <a class="nav-link text-white {{ request()->routeIs('sizes.*') ? 'active' : '' }}"
               href="{{ route('sizes.index') }}">
                Sizes
            </a>


            <a class="nav-link text-white {{ request()->routeIs('stock-movements.*') ? 'active' : '' }}"
               href="{{ route('stock-movements.index') }}">
                Stock History
            </a>

        </div>

    </div>

</nav>


<div class="container">

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    @yield('content')

</div>

</body>

</html>