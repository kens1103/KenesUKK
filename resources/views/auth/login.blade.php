<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form method="POST" action="{{ route('login') }}" autocomplete="off">
                    @csrf 
                    <div class="modal-body">
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" autocomplete="off" required autofocus>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">password</label>
                                <input type="password" class="form-control" name="password" autocomplete="off" required>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- MODAL LOGIN OTOMATIS ADA KALAU ERROR PASSWORD/EMAIL -->
    @if(session('error'))
    <script>
        const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
        loginModal.show();
    </script>
    @endif

    <script>
        // OTOMATIS ADA KETIKA BERADA DI HALAMAN
        const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
        loginModal.show();
    </script>

</body>
</html>