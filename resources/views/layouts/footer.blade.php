<!-- footer.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Metadata and other head content -->
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <style>
    body {
      display: flex;
      min-height: 100vh;
      flex-direction: column;
    }

    main {
      flex: 1;
    }

    .footer {
     
      color: #000;
      text-align: center;
      padding: 10px 0;
    }
  </style>
</head>

<body>
  <div class="wrapper">
    <main>
      <!-- Your main content here -->
    </main>

    <!-- Footer -->
    <footer class="footer">
      <div class="container">
        <div class="row align-items-center justify-content-lg-between">
          <div class="col-12 col-md-12 my-auto">
            <div class="copyright text-center text-sm text-white text-lg-center">
              <a href="https://wa.me/628179851011" target="_blank">Copyright &copy; {{ date('Y') }} Yayasan Rusyda Medi Andri</a>
            </div>
          </div>
        </div>
      </div>
    </footer>
  </div>

  <!-- Core JS Files -->
  <script src="{{ asset('/') }}js/core/popper.min.js"></script>
  <script src="{{ asset('/') }}js/core/bootstrap.min.js"></script>
  <script src="{{ asset('/') }}js/plugins/perfect-scrollbar.min.js"></script>
  <script src="{{ asset('/') }}js/plugins/smooth-scrollbar.min.js"></script>
  <script src="{{ asset('/') }}js/plugins/chartjs.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{ asset('/') }}js/material-dashboard.min.js?v=3.0.4"></script>
</body>

</html>
