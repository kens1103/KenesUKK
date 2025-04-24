@extends('layouts.app')

@section('content')

    <div class="container my-5">
        <a href="{{ route('dashboard') }}" class="text-black me-5" style="font-size: 35px; text-decoration: none;">Dashboard Anda</a>
            <h4 class="mb-6">Daftar Pengguna</h4>
                <div>
                    <a href="{{ route('dashboard') }}" class="btn btn-success">Kembali ke dashboard</a>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-muted text-center">Belum ada user yang login.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
    </div>
    
@endsection