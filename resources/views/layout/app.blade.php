<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>@yield('title', "Aranya" )</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('admin-assets/assets/img/favicon.ico')}}" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    @include('partials.header-assets')
    <style>
        .active_url a .menu_heading {
            color: #ffffff !important;
        }

        .v-lazy-image {
            filter: blur(10px);
            transition: filter 0.7s;
        }

        .v-lazy-image-loaded {
            filter: blur(0);
        }
    </style>
    @stack('style')
</head>

<body class="sidebar-noneoverflow">
    <!-- BEGIN LOADER -->
    <div id="load_screen">
        <div class="loader">
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  -->
    @include('partials.admin_topbar')
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        @include('partials.admin_sidebar')
        <!--  END SIDEBAR  -->

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing mt-4" id="app">
                @yield('content')
            </div>
            <div class="footer-wrapper">
                <div class="footer-section f-section-1">
                    <p class="">Copyright © {{ date('Y') }} <a target="_blank" href="https://webable.digital/">Amyt</a>, All rights reserved.</p>
                </div>

            </div>
        </div>
        <!--  END CONTENT AREA  -->


    </div>


    @include('partials.footer-assets')

    <script>
        window.userPermission = "{{ getPermission() }}";
    </script>

    @stack('script')

</body>

</html>