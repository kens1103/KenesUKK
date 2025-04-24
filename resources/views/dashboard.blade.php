@extends('layouts.app')

@section('content')

    <div class="container my-5">
        <a href="{{ route('dashboard') }}" class="text-black me-5" style="font-size: 35px; text-decoration: none;">Dashboard Anda</a>
            @auth
                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('users.index') }}" class="text-black" style="font-size: 25px; text-decoration: none;">User</a>
                @endif
            @endauth
                <form action="{{ route('dashboard') }}" method="GET" class="mb-4 row g-2">
                    <div class="col-md-2">
                        <div class="form-control-plaintext">
                            <strong>Total Foto : </strong>{{ $photos->total() }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="category" class="form-select">
                            <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->category }}" {{ request('category') == $cat->category ? 'selected' : '' }}>
                                        {{ $cat->category }}
                                    </option>
                                @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </div>
                </form>

                    @if ($photos->count())
                        <div class="row">
                            @foreach ($photos as $photo)
                                <div class="col-md-3 mb-4">
                                    <div class="card">
                                        <img src="{{ asset('uploads/' . $photo->image) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Foto">
                                            <div class="card-body text-center">
                                                <small class="text-muted">Kategori: {{ $photo->category ?? 'Tidak ada' }}</small><br>
                                                <small class="text-muted">Diunggah: {{ $photo->created_at->format('d M Y') }}</small><br>
                                                <small class="text-muted">Diunggah oleh : <span class="fw-semibold text-dark">{{ $photo->user->name ?? 'Tidak diketahui' }}</span></small>
                                                    <div class="mt-2">
                                                        <span class="badge bg-info text-dark" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#likesModal{{ $photo->id }}">
                                                            ❤️ {{ $photo->likes->count() }} Like</span>
                                                        <span class="badge bg-info text-dark" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#commentsModal{{ $photo->id }}">
                                                            💬 {{ $photo->comments->count() }} Komentar
                                                        </span>
                                                    </div>
                                                    <div class="text-center my-3">
                                                        <form action="{{ route('photos.destroy', $photo->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus foto ini?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger px-4">Hapus</button>
                                                        </form>
                                                    </div>
                                            </div>
                                    </div>
                                </div>

                                <!-- MODAL KOMENTAR -->
                                <div class="modal fade" id="commentsModal{{ $photo->id }}" tabindex="-1" aria-labelledby="commentsModalLabel{{ $photo->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="commentsModalLabel{{ $photo->id }}">Komentar</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                            </div>
                                            <div class="modal-body">
                                                @forelse ($photo->comments as $comment)
                                                    <div class="mb-2">
                                                        <strong>{{ $comment->user->name ?? 'Tidak diketahui' }}</strong>
                                                        <small class="text-muted">({{ $comment->created_at->diffForHumans() }})</small>
                                                            <div class="d-flex flex-column align-items-end">
                                                                @if(auth()->id() === $comment->user_id || auth()->user()->role === 'admin')
                                                                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Hapus komentar ini?')">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-sm btn-outline-secondary">🗑</button>
                                                                    </form>
                                                                @endif
                                                            </div>
                                                        <hr>
                                                    </div>
                                                @empty
                                                    <p class="text-muted">Belum ada komentar.</p>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- MODAL LIKE -->
                                <div class="modal fade" id="likesModal{{ $photo->id }}" tabindex="-1" aria-labelledby="likesModalLabel{{ $photo->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="likesModalLabel{{ $photo->id }}">Like</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                            </div>
                                            <div class="modal-body">
                                                @forelse ($photo->likes as $like)
                                                    <div class="mb-2">
                                                        <strong>{{ $like->user->name ?? 'Tidak diketahui' }}</strong>
                                                        <small class="text-muted">({{ $like->created_at->diffForHumans() }})</small>
                                                        <hr>
                                                    </div>
                                                @empty
                                                    <p class="text-muted">Belum ada like.</p>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-center">
                            {{ $photos->links('pagination::bootstrap-5') }}
                        </div>
                    @else
                        <p class="text-muted">Anda belum mengunggah foto.</p>
                    @endif
    </div>
    
@endsection
