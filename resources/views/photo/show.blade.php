<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Foto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card img {
            width: 100%;
            height: auto;;
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
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row d-flex justify-content-center">
            <!-- FOTO -->
            <div class="col-md-5">
                <div class="card">
                    <img src="{{ asset('uploads/'.$photo->image) }}" 
                         class="card-img-top img-fluid mx-auto d-block mb-6" 
                         style="max-width: 100%; height: auto; max-height: 600px;" 
                         alt="Foto">
                </div>
            </div>

            <!-- KOMENTAR & LIKE -->
            <div class="col-md-6 d-flex flex-column">
                <div>
                    @if ($photo->category)
                        <span class="badge bg-primary">{{ $photo->category }}</span>
                    @else
                        <span class="badge bg-secondary">Tidak ada kategori</span>
                    @endif
                </div>
                <div class="mt-2 mb-1">
                    <small class="text-muted fst-italic">
                        Diunggah oleh : <span class="fw-semibold text-dark">{{ $photo->user->name ?? 'Tidak diketahui' }}</span>
                    </small>
                </div>
                <div>
                    @auth
                        <form action="{{ route('photo.like', $photo->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-light">
                                ❤️ {{ $photo->likes->count() }} Like
                            </button>
                        </form>
                    @else
                        <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#loginModal">
                            ❤️ {{ $photo->likes->count() }} Like
                        </button>
                    @endauth
                </div>

                <div class="mt-2">
                    <h4>Komentar</h4>
                        <div class="list-group">
                            @foreach($photo->comments as $comment)
                                <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-1">
                                    <div>
                                        <strong>{{ $comment->user->name }} :</strong>
                                        <p class="mb-1 ms-2">{{ $comment->comment }}</p>
                                        <small class="text-muted ms-2">{{ $comment->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div class="d-flex flex-column align-items-end">
                                        @auth
                                            <!-- LIKE KOMENTAR -->
                                            <form action="{{ route('comment.like', $comment->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger mb-1">
                                                    ❤️ {{ $comment->likes->count() }}
                                                </button>
                                            </form>

                                            <!-- HAPUS KOMENTAR -->
                                            @if(auth()->id() === $comment->user_id || auth()->user()->is_admin)
                                                <form action="{{ route('photo.comment.delete', $comment->id) }}" method="POST" onsubmit="return confirm('Hapus komentar ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary">🗑</button>
                                                </form>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            @endforeach
                        </div>
                </div>

                <!-- FORM TAMBAH KOMENTAR -->
                @if ($photo->comments_enabled)
                    <div class="mt-2">
                        @auth
                            <form action="{{ route('photo.comment', $photo->id) }}" method="POST">
                                @csrf
                                <div class="input-group">
                                    <input type="text" name="comment" class="form-control" placeholder="Tambah komentar..." required>
                                    <button type="submit" class="btn btn-success">Kirim</button>
                                </div>
                            </form>
                        @else
                            <button class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#loginModal">
                                Login untuk berkomentar
                            </button>
                        @endauth
                    </div>
                @else
                    <div class="mt-3 alert alert-info">
                        Komentar untuk foto ini telah dinonaktifkan oleh pengunggah.
                    </div>
                @endif

                <div class="mt-3">
                    <a href="{{ route('galeri') }}" class="btn btn-secondary w-100">Kembali</a>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL LOGIN -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="loginModalLabel">Login</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Kata Sandi</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Masuk</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- MODAL GAGAL LOGIN -->
    @if(session('error'))
        <script>
            var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
        </script>
    @endif

</body>
</html>
