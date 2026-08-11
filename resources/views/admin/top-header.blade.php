<!DOCTYPE html>
<html lang="en" data-textdirection="ltr" class="loading">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta name="description" content="Srii Harihar Ply a exlcusive store for Lucknowi Chikan kaari">
  <meta name="keywords" content="Srii Harihar Ply">
  <meta name="author" content="Webmingo">
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <title>Admin Dashboard | Srii Harihar Ply</title>
  <link rel="icon" type="image/png" href="{{ asset('assets/images/android-chrome-512x512.png') }}">
  <!--  <title>Krishna Chikan | @yield('title')</title> -->


  <!-- BEGIN VENDOR CSS-->
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
  <!-- END VENDOR CSS-->
  <link rel="stylesheet" type="text/css" href="https://site-assets.fontawesome.com/releases/v6.1.1/css/all.css">
  <!-- END STACK CSS-->
  <!-- BEGIN Page Level CSS-->
  <link rel="stylesheet" type="text/css" href="{{ URL::asset('admin/css/datatable.css') }}">
  <!-- END Page Level CSS-->
  <!-- BEGIN Custom CSS-->
  <!-- END Custom CSS-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.10.4/sweetalert2.min.css">
  <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

  <link rel="stylesheet" type="text/css" href="{{ URL::asset('admin/custom/css/header.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ URL::asset('admin/custom/css/style.css') }}">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  </script>

  <style>
    .main-section {
      display: flex !important;
    }

    .main-section #cssmenu {
      width: 280px !important;
      min-width: 280px !important;
      flex-shrink: 0 !important;
    }

    .main-section .app-content {
      flex: 1 !important;
      min-width: 0 !important;
    }
  </style>

</head>

<body>


  <div class="top-header-sec py-1 bg-light border-bottom mb-2">
    <div class="container-fluid">
      <div class="top-main-header d-flex align-items-center">
        <div class="admin-logo">
          <img src="{{ asset('admin/images/logo.png') }}" style="height:20px;">
        </div>
        <div class="ml-auto">

          <div class="btn-group">

            <button class="btn bg-transparent p-0 dropdown-toggle" type="button" data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">

              <i class="fa-solid fa-user-circle"></i> Admin

            </button>

            <div class="dropdown-menu keep-open header-dropdown">

              <a class="dropdown-item" href="{{ url('admin/profile-setting') }}">

                <i class="fa-solid fa-user mr-2"></i> Profile

              </a>

              <a class="dropdown-item" href="{{ url('admin/logout') }}">

                <i class="fa-solid fa-right-from-bracket mr-2"></i> Logout

              </a>

            </div>

          </div>

        </div>
      </div>
    </div>
  </div>
  </div>


  <script type="text/javascript">
    jQuery('.dropdown-menu.keep-open').on('click', function (e) {
      e.stopPropagation();
    });

    if (1) {
      $('body').attr('tabindex', '0');
    }
    else {
      alertify.confirm().set({ 'reverseButtons': true });
      alertify.prompt().set({ 'reverseButtons': true });
    }
  </script>