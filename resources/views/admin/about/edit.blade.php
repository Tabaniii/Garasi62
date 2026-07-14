@extends('layouts.admin')

@section('header-title', 'Kontrol About Us')

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 5px;">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 5px;">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-2" style="font-size: 20px;"></i>
                <div class="flex-grow-1">
                    <strong>Terjadi kesalahan!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('admin.about.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Heading</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Utama</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $settings['title']) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="subtitle" class="form-label">Subjudul</label>
                            <input type="text" class="form-control @error('subtitle') is-invalid @enderror" id="subtitle" name="subtitle" value="{{ old('subtitle', $settings['subtitle']) }}" required>
                            @error('subtitle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description', $settings['description']) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar Utama</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="mt-3">
                                <img src="{{ media_url($settings['image']) }}" alt="About Image" style="max-width: 100%; border-radius: 5px;">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="fas fa-bullseye me-2"></i>Mission</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="mission_title" class="form-label">Judul Mission</label>
                            <input type="text" class="form-control @error('mission_title') is-invalid @enderror" id="mission_title" name="mission_title" value="{{ old('mission_title', $settings['mission_title']) }}" required>
                            @error('mission_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="mission_text" class="form-label">Deskripsi Mission</label>
                            <textarea class="form-control @error('mission_text') is-invalid @enderror" id="mission_text" name="mission_text" rows="4" required>{{ old('mission_text', $settings['mission_text']) }}</textarea>
                            @error('mission_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="fas fa-eye me-2"></i>Vision</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="vision_title" class="form-label">Judul Vision</label>
                            <input type="text" class="form-control @error('vision_title') is-invalid @enderror" id="vision_title" name="vision_title" value="{{ old('vision_title', $settings['vision_title']) }}" required>
                            @error('vision_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="vision_text" class="form-label">Deskripsi Vision</label>
                            <textarea class="form-control @error('vision_text') is-invalid @enderror" id="vision_text" name="vision_text" rows="4" required>{{ old('vision_text', $settings['vision_text']) }}</textarea>
                            @error('vision_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="fas fa-star me-2"></i>Feature</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Feature 1</label>
                            <input type="text" class="form-control @error('feature_1_title') is-invalid @enderror" name="feature_1_title" value="{{ old('feature_1_title', $settings['feature_1_title']) }}" required>
                            @error('feature_1_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <textarea class="form-control mt-2 @error('feature_1_text') is-invalid @enderror" name="feature_1_text" rows="3" required>{{ old('feature_1_text', $settings['feature_1_text']) }}</textarea>
                            @error('feature_1_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <input type="file" class="form-control mt-2 @error('feature_1_icon') is-invalid @enderror" name="feature_1_icon" accept="image/*">
                            @error('feature_1_icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="mt-2">
                                <img src="{{ media_url($settings['feature_1_icon']) }}" alt="Feature 1" style="max-width: 90px;">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Feature 2</label>
                            <input type="text" class="form-control @error('feature_2_title') is-invalid @enderror" name="feature_2_title" value="{{ old('feature_2_title', $settings['feature_2_title']) }}" required>
                            @error('feature_2_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <textarea class="form-control mt-2 @error('feature_2_text') is-invalid @enderror" name="feature_2_text" rows="3" required>{{ old('feature_2_text', $settings['feature_2_text']) }}</textarea>
                            @error('feature_2_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <input type="file" class="form-control mt-2 @error('feature_2_icon') is-invalid @enderror" name="feature_2_icon" accept="image/*">
                            @error('feature_2_icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="mt-2">
                                <img src="{{ media_url($settings['feature_2_icon']) }}" alt="Feature 2" style="max-width: 90px;">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Feature 3</label>
                            <input type="text" class="form-control @error('feature_3_title') is-invalid @enderror" name="feature_3_title" value="{{ old('feature_3_title', $settings['feature_3_title']) }}" required>
                            @error('feature_3_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <textarea class="form-control mt-2 @error('feature_3_text') is-invalid @enderror" name="feature_3_text" rows="3" required>{{ old('feature_3_text', $settings['feature_3_text']) }}</textarea>
                            @error('feature_3_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <input type="file" class="form-control mt-2 @error('feature_3_icon') is-invalid @enderror" name="feature_3_icon" accept="image/*">
                            @error('feature_3_icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="mt-2">
                                <img src="{{ media_url($settings['feature_3_icon']) }}" alt="Feature 3" style="max-width: 90px;">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
