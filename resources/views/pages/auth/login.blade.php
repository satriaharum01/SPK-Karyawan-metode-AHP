@include('layouts.header')

<body class="bg-gray-200">
  @include('sweetalert::alert')

  <!-- Video Background -->
  <video autoplay muted loop id="bg-video">
    <source src="{{ asset('rusyda.mp4') }}" type="video/mp4">
      Your browser does not support HTML5 video.
    </video>

    <div class="container position-sticky z-index-sticky top-0">
      <div class="row">
        <div class="col-12">
          <!-- Navbar -->
          <!-- <x-login-nav /> -->
          <!-- End Navbar -->
        </div>
      </div>
    </div>

    <main class="main-content mt-0">
      <div class="page-header align-items-center min-vh-100">
        <div class="container my-auto text-center">
          <h1 class="text-white font-weight-bolder">SISTEM INFORMASI PENILAIAN KINERJA GURU YAYASAN RUSYDA MEDI ANDRI MEDAN BERBASIS WEB MENGGUNAKAN METODE AHP </h1>
          <button type="button" class="btn btn-primary mt-4" data-bs-toggle="modal" data-bs-target="#loginModal">
            <i class="fa fa-sign-in-alt"></i> Login
          </button>
          <!-- Tentang Button -->
          <button type="button" class="btn btn-info mt-4" data-bs-toggle="modal" data-bs-target="#aboutModal">
            <i class="fa fa-info-circle"></i> Tentang
          </button>
        </div>
      </div>
    </main>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="loginModalLabel">Login | SPK Penilaian Kinerja Guru </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
              <!-- SVG Icon Close -->
              <svg width="24" height="24" fill="black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M19.07 4.93c-.39-.39-1.02-.39-1.41 0L12 10.59 6.34 4.93c-.39-.39-1.02-.39-1.41 0s-.39 1.02 0 1.41L10.59 12l-5.66 5.66c-.39.39-.39 1.02 0 1.41.39.39 1.02.39 1.41 0L12 13.41l5.66 5.66c.39.39 1.02.39 1.41 0s.39-1.02 0-1.41L13.41 12l5.66-5.66c.39-.39.39-1.02 0-1.41z"/>
              </svg>
            </button>
          </div>
          <div class="modal-body">
            <form method="POST" action="{{ route('signin') }}">
              @csrf
              <div class="mb-3">
                <label for="email" class="form-label @error('email') text-danger @enderror">Email address</label>
                <input type="email" class="form-control border ps-2 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}">
                @error('email')
                <span class="text-danger text-xs my-2">{{ $message }}</span>
                @enderror
              </div>

              <div class="mb-3">
                <label for="password" class="form-label @error('password') text-danger @enderror">Password</label>
                <input type="password" class="form-control border ps-2 @error('password') is-invalid @enderror" name="password">
                @error('password')
                <span class="text-danger text-xs my-2">{{ $message }}</span>
                @enderror
              </div>

              @error('auth_login')
              <div class="text-center">
                <span class="text-danger my-2">{{ $message }}</span>
              </div>
              @enderror

              <div class="text-center">
                <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">Sign in</button>
              </div>
            </form>
            <div class="copyright text-center text-sm text-white text-lg-center">
              <a href="https://wa.me/628179851011" target="_blank">Copyright &copy; {{date('Y')}} Yayasan Rusyda Medi Andri</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- About Modal -->
    <div class="modal fade" id="aboutModal" tabindex="-1" aria-labelledby="aboutModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="aboutModalLabel">Tentang Aplikasi</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
              <!-- SVG Icon Close -->
              <svg width="24" height="24" fill="black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M19.07 4.93c-.39-.39-1.02-.39-1.41 0L12 10.59 6.34 4.93c-.39-.39-1.02-.39-1.41 0s-.39 1.02 0 1.41L10.59 12l-5.66 5.66c-.39.39-.39 1.02 0 1.41.39.39 1.02.39 1.41 0L12 13.41l5.66 5.66c.39.39 1.02.39 1.41 0s.39-1.02 0-1.41L13.41 12l5.66-5.66c.39-.39.39-1.02 0-1.41z"/>
              </svg>
            </button>
          </div>
          <div class="modal-body">
            <h5>Informasi Aplikasi</h5>
            <p>Ini adalah aplikasi Sistem Pendukung Keputusan Penilaian Guru menggunakan metode Analytical Hierarchy Process (AHP). Aplikasi ini dirancang untuk membantu dalam proses penilaian Guru secara objektif dan sistematis.</p>
            <h5>Cara Penggunaan</h5>
            <p>Untuk menggunakan aplikasi ini, Anda perlu login dengan email dan password yang valid. Setelah login, Anda dapat mengakses berbagai fitur untuk melakukan penilaian terhadap Guru berdasarkan kriteria yang telah ditentukan.</p>
            <h5>Manfaat Aplikasi</h5>
            <ul>
              <li>Memudahkan proses penilaian Guru secara objektif.</li>
              <li>Memberikan analisis yang mendalam mengenai kinerja Guru.</li>
              <li>Membantu dalam pengambilan keputusan terkait pengembangan dan manajemen Guru.</li>
            </ul>
          </div>
        </div>
      </div>
    </div>


    <!-- Footer -->
    <footer class="footer fixed-bottom py-2 w-100">
      <div class="container">
        <div class="row align-items-center justify-content-lg-between">
          <div class="col-12 col-md-12 my-auto">
            <div class="copyright text-center text-sm text-white text-lg-center">
              <a href="https://wa.me/628179851011" target="_blank">Copyright &copy; {{date('Y')}} Yayasan Rusyda Medi Andri</a>
            </div>
          </div>
        </div>
      </div>
    </footer>

    <!-- Core JS Files -->
    <script src="{{ asset('/') }}js/core/popper.min.js"></script>
    <script src="{{ asset('/') }}js/core/bootstrap.min.js"></script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <!-- Custom CSS -->
    <style>
      #bg-video {
        position: fixed;
        right: 0;
        bottom: 0;
        min-width: 100%;
        min-height: 100%;
        z-index: -1;
      }
      .page-header {
        position: relative;
        z-index: 1;
      }
      .footer {
        z-index: 2;
      }
      .footer a {
        color: #fff;
        text-decoration: none;
      }
      .footer a:hover {
        text-decoration: underline;
      }
      .btn-info i, .btn-primary i {
        margin-right: 5px;
      }
    </style>
  </body>

  </html>
