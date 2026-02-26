<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name')) — Pastry Shop</title>
    @section('head')
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Jost:wght@400;500;600&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables.net-bs5/2.2.1/dataTables.bootstrap5.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <style>
            :root {
                --pastry-cream: #FDF8F3;
                --pastry-sand: #E8DDD4;
                --pastry-caramel: #A67C52;
                --pastry-brown: #5C4033;
                --pastry-gold: #C9A227;
                --pastry-rose: #D4A5A5;
                --pastry-text: #3D2914;
            }
            body {
                font-family: 'Jost', sans-serif;
                background-color: var(--pastry-cream);
                color: var(--pastry-text);
                min-height: 100vh;
            }
            .font-serif { font-family: 'Cormorant Garamond', serif; }
            h1, h2, .navbar-brand { font-family: 'Cormorant Garamond', serif; font-weight: 600; }
            .btn-pastry {
                background-color: var(--pastry-caramel);
                border-color: var(--pastry-caramel);
                color: #fff;
            }
            .btn-pastry:hover {
                background-color: var(--pastry-brown);
                border-color: var(--pastry-brown);
                color: #fff;
            }
            .btn-outline-pastry {
                border-color: var(--pastry-caramel);
                color: var(--pastry-caramel);
            }
            .btn-outline-pastry:hover {
                background-color: var(--pastry-caramel);
                color: #fff;
            }
            .card-pastry {
                background: #fff;
                border-radius: 12px;
                border: none;
                box-shadow: 0 4px 20px rgba(92, 64, 51, 0.08);
            }
            .form-control:focus, .form-select:focus {
                border-color: var(--pastry-caramel);
                box-shadow: 0 0 0 0.2rem rgba(166, 124, 82, 0.2);
            }
            a { color: var(--pastry-caramel); }
            a:hover { color: var(--pastry-brown); }
        </style>
    @show
</head>

<body>
    @include('layouts.header')
    @yield('body')
    @stack('scripts')
</body>

</html>
