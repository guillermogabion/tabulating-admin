<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Skydash Admin</title>
    <!-- plugins:css -->

    <link rel="stylesheet" href="{{ asset('vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/mdi/css/materialdesignicons.min.css') }}">

    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- <link rel="stylesheet" href="assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css"> -->
    <link rel="stylesheet" href="{{ asset('vendors/datatables.net-bs5/dataTables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('js/select.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('images/favicon.png') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <!-- End plugin css for this page -->
    <!-- inject:css -->

</head>

<body>
    <div id="app" class="container-scroller">
        <div id="proBanner" class="d-none">
            <!-- Pro Banner content here -->
            <p class="d-none">Your pro banner content goes here.</p>
            <button class="d-none" id="bannerClose">Close</button>
        </div>


        <main class="">
            <div>
                @auth
                <x-header />
                <div class="container-fluid page-body-wrapper">
                    <x-sidebar />
                    @yield('content')
                </div>
                @else
                @yield('content')
                @endauth

            </div>
        </main>
    </div>

    <!-- container-scroller -->
    <!-- plugins:js -->
    <!-- <script src="assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script> -->

    <script src="{{ asset('vendors/js/vendor.bundle.base.js')}}"></script>
    <script src="{{ asset('vendors/chart.js/chart.umd.js')}}"></script>
    <script src="{{ asset('vendors/datatables.net/jquery.dataTables.js')}}"></script>
    <script src="{{ asset('vendors/datatables.net-bs5/dataTables.bootstrap5.js')}}"></script>
    <script src="{{ asset('js/dataTables.select.min.js')}}"></script>
    <script src="{{ asset('js/off-canvas.js')}}"></script>
    <script src="{{ asset('js/template.js')}}"></script>
    <script src="{{ asset('js/settings.js')}}"></script>
    <script src="{{ asset('js/jquery.cookie.js')}}" type="text/javascript"></script>
    <script src="{{ asset('js/todolist.js')}}"></script>
    <script src="{{ asset('js/dashboard.js')}}"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <!-- endinject -->
    <!-- Custom js for this page-->
    <!-- <script src="assets/js/Chart.roundedBarCharts.js"></script> -->
    <!-- End custom js for this page-->
</body>

</html>