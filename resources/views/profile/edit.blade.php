@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Profil Saya</h1>

    {{-- ALERT BANNER --}}
    @if (session('status') === 'profile-updated' || session('status') === 'photo-deleted' || session('status') === 'photo-updated')
        <div class="alert alert-success alert-dismissible fade show mb-3 rounded-0 px-4 py-3 text-center" role="alert" style="border-left: 5px solid #28a745;">
            @switch(session('status'))
                @case('profile-updated')
                    Profil berhasil diperbarui.
                    @break
                @case('photo-deleted')
                    Foto profil berhasil dihapus.
                    @break
                @case('photo-updated')
                    Foto profil berhasil diubah.
                    @break
            @endswitch
            <button type="button" class="close position-absolute" style="right: 20px;" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Form Profil -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Profil</h6>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <!-- Foto Profil -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Foto Profil</h6>
                </div>
                <div class="card-body text-center">

                    @php $user = Auth::user(); @endphp

                    <img class="img-profile rounded-circle mb-3"
                         src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('sbadmin/img/undraw_profile.svg') }}"
                         alt="Foto Profil"
                         style="width: 120px; height: 120px; object-fit: cover;">

                    {{-- Upload Foto --}}
                    <form method="POST" action="{{ route('profile.update.photo') }}" enctype="multipart/form-data" class="mb-3">
                        @csrf
                        <div class="form-group">
                            <input type="file" class="form-control-file @error('profile_photo') is-invalid @enderror" name="profile_photo" accept="image/*" required>
                            @error('profile_photo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-upload mr-1"></i> Upload
                        </button>
                    </form>

                    {{-- Hapus Foto --}}
                    @if ($user->profile_photo_path)
                        <form action="{{ route('profile.photo.delete') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus foto profil?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash mr-1"></i> Hapus Foto
                            </button>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <!-- Ganti Password -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ganti Password</h6>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
