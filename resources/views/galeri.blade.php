<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Foto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .hero {
            position: relative;
            background-image: url('/uploads/1.jpeg');
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            image-rendering: auto;
            height: 60vh;
            z-index: 1;
        }

        .hero::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 2;
        }

        .hero > * {
            position: relative;
            z-index: 3;
        }

        .card img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 10px;
        }

        .card {
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.2s;
            padding: 0;
            border: none;
            background-color: transparent;
            display: inline-block;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .floating-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #007bff;
            color: white;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
        }

        .header-text {
            text-align: center;
            margin-bottom: 30px;
        }

        .header-text h1 {
            font-size: 32px;
            font-weight: bold;
        }

        .header-text p {
            font-size: 18px;
            color: #555;
        }

        .masonry {
            column-count: 4;
            column-gap: 1rem;
        }

        .masonry .masonry-item {
            break-inside: avoid;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid hero d-flex flex-column align-items-center justify-content-center text-center">
        <div class="position-absolute top-0 start-0 p-3">
            <a href="{{ route('galeri') }}" class="text-white" style="font-family: 'Great Vibes', cursive; font-size: 28px; text-decoration: none;">Galeries</a>
        </div>
        <div class="position-absolute top-0 end-0 p-3">
            @guest
                <a href="{{ route('loginForm') }}" class="text-white me-3" style="text-decoration: none;" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a>
                <a href="{{ route('registerForm') }}" class="text-white me-3" style="text-decoration: none;" data-bs-toggle="modal" data-bs-target="#registerModal">Register</a>
            @endguest
            @auth
                <span class="text-white me-3"><i class="bi bi-person-circle me-1" style="font-size: 20px;"></i>
                    <strong>{{ Auth::user()->name }}</strong>
                </span>
                <a href="{{ route('dashboard') }}" class="text-white me-3" style="text-decoration: none;">Dashboard</a>
                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#logoutModal">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </button>
            @endauth
        </div>

        <h1 class="mb-5" style="color: white;">Jelajahi dan Unggah Foto Favorit Anda!</h1>
        <form action="{{ route('galeri.search') }}" method="GET" class="d-flex justify-content-center align-items-center w-50">
            <select name="category" class="form-select w-25 me-2">
                <option value="">Pilih Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->category }}" {{ request('category') == $category->category ? 'selected' : '' }}>
                        {{ $category->category }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>

    <div class="container my-5 text-center">
        <h1>Kreatif!</h1>
        <p>Jelajahi beribu-ribu foto!</p>
    </div>

    <div class="container gallery-container">
        <div class="masonry">
        @foreach($photos as $photo)
            <div class="masonry-item">
                <div class="card shadow-sm">
                    <a href="{{ route('photo.show', $photo->id) }}">
                    <img src="{{ asset('uploads/' . $photo->image) }}" 
                        data-src="{{ asset('uploads/' . $photo->image) }}" 
                        class="card-img-top preview-img" alt="Foto">
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>

<!-- MODAL FOTO -->
<div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="photoModalLabel">Pratinjau Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid" alt="Preview">
            </div>
        </div>
    </div>
</div>

<!-- TOMBOL TAMBAH FOTO -->
@auth
<button class="floating-button" data-bs-toggle="modal" data-bs-target="#uploadModal">+</button>
@endauth

<!-- MODAL TAMBAH FOTO -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Tambah Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form action="{{ route('photos.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="image" class="form-label">Pilih Foto</label>
                        <input type="file" class="form-control" id="image" name="image" required>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Kategori</label>
                        <select name="category" class="form-select" required>
                            <option value="">Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->category }}">{{ $category->category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODAL REGISTER -->
<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="registerModalLabel">Register</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="modal-body">
        @if(session('success'))
            <script>
                var registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
                var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                registerModal.hide();  // NUTUP MODAL REGISTER
                loginModal.show();     // BUKA MODAL LOGIN
            </script>
        @endif
          @if(session('error'))
            <script>
                var Modal = new bootstrap.Modal(document.getElementById('registerModal'));
                registerModal.show();
            </script>
            @endif
          <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="name" class="form-control" name="name" required autofocus>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" required autofocus>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Register</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL LOGIN -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="loginModalLabel">Login</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="modal-body">
          @if(session('error'))
              <div class="alert alert-danger">{{ session('error') }}</div>
          @endif
          @if(session('error'))
            <script>
                var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                loginModal.show();
            </script>
            @endif
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" required autofocus>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Login</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL LOGOUT -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
      </div>
      <div class="modal-body">
        Apakah Anda yakin ingin logout?
      </div>
      <div class="modal-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">Ya, Logout</button>
        </form>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL BERHASIL LOGIN -->
@if(session('success'))
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1055;">
    <div id="loginToast" class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
    </div>
</div>
@endif

<!-- SCRIPT MODAL BERHASIL LOGIN -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const toastEl = document.getElementById('loginToast');
        if (toastEl) {
            const toast = new bootstrap.Toast(toastEl, {
                delay: 2000
            });
            toast.show();
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const previewImages = document.querySelectorAll(".preview-img");
        const modalImage = document.getElementById("modalImage");

        previewImages.forEach(image => {
            image.addEventListener("click", function() {
                modalImage.src = this.getAttribute("data-src");
            });
        });
    });
</script>

</body>
</html>
