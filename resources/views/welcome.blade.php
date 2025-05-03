@extends('layouts.template')

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Halo, apakabar!!!</h3>
    </div>
    <div class="card-body text">
        <p>Selamat datang semua, ini adalah halaman utama dari aplikasi ini.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <!-- Profile Card -->
        <div class="card card-primary card-outline">
            <div class="card-body box-profile text-center">
                @if (Auth::user()->photo)
                    <img class="profile-user-img img-fluid img-circle"
                         src="{{ asset('storage/' . Auth::user()->photo) }}"
                         style="object-fit: cover; width: 100px; height: 100px;"
                         alt="User profile picture">
                @else
                    <img class="profile-user-img img-fluid img-circle"
                         src="https://ui-avatars.com/api/?name=User&background=random"
                         style="object-fit: cover; width: 100px; height: 100px;"
                         alt="Default profile picture">
                @endif

                <h3 class="profile-username text-center mt-2">Sherly Lutfi Azkiah Sulistyawati</h3>
                <p class="text-muted text-center">2341720241</p>

                <ul class="list-group list-group-unbordered mb-3 text-left">
                    <li class="list-group-item">
                        <b>Prodi</b> <span class="float-right">Teknik Informatika</span>
                    </li>
                    <li class="list-group-item">
                        <b>Email</b> <span class="float-right">sherlyazkiah12@email.com</span>
                    </li>
                    <li class="list-group-item">
                        <b>Github</b> <span class="float-right">https://github.com/sherlyazkiah</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Upload Photo Form -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Upload Foto Profil</h3>
            </div>
            <div class="card-body">
                <form action="{{ url('/profile-upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label for="profile_photo">Pilih Foto Baru</label>
                    <div class="form-group">
                        
                        <input type="file" name="profile_photo" id="profile_photo" class="form-control" required>
                        @error('profile_photo')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection