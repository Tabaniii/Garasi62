@extends('layouts.admin')

@section('header-title', 'Edit Blog')

@section('content')
<style>
.form-section {
    background: #fff;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
    margin-bottom: 25px;
}

.form-label {
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 8px;
    font-size: 14px;
}

.form-control, .form-select {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 12px 15px;
    transition: all 0.3s;
}

.form-control:focus, .form-select:focus {
    border-color: #dc2626;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
}

.btn-submit {
    background: linear-gradient(135deg, #dc2626, #ef4444);
    color: #fff;
    padding: 12px 30px;
    border-radius: 8px;
    font-weight: 700;
    border: none;
    transition: all 0.3s;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(220, 38, 38, 0.4);
    color: #fff;
}

.btn-cancel {
    background: #f3f4f6;
    color: #1a1a1a;
    padding: 12px 30px;
    border-radius: 8px;
    font-weight: 700;
    border: none;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s;
}

.btn-cancel:hover {
    background: #e9ecef;
    color: #1a1a1a;
}

.image-preview {
    max-width: 300px;
    max-height: 200px;
    border-radius: 8px;
    margin-top: 10px;
    border: 2px solid #e9ecef;
}

.current-image {
    max-width: 300px;
    max-height: 200px;
    border-radius: 8px;
    margin-top: 10px;
    border: 2px solid #e9ecef;
}
</style>

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Terjadi kesalahan!</strong>
    <ul class="mb-0 mt-2">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="form-section">
    <h3 class="mb-4" style="font-weight: 800; color: #1a1a1a;">Edit Blog</h3>
    
    <form action="{{ route('blogs.admin.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label for="title" class="form-label">Judul Blog <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $blog->title) }}" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="image" class="form-label">Gambar Blog</label>
            @if($blog->image)
            <div class="mb-2">
                <p class="mb-1"><strong>Gambar Saat Ini:</strong></p>
                <img src="{{ asset('storage/' . $blog->image) }}" alt="Current Image" class="current-image">
            </div>
            @endif
            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*" onchange="previewImage(this)">
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">Format: JPG, PNG, GIF, WEBP (Maks 5MB). Kosongkan jika tidak ingin mengubah gambar.</small>
            <div id="imagePreview" class="mt-3"></div>
        </div>

        <div class="mb-4">
            <label for="author" class="form-label">Penulis <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('author') is-invalid @enderror" id="author" name="author" value="{{ old('author', $blog->author) }}" required>
            @error('author')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="excerpt" class="form-label">Ringkasan</label>
            <textarea class="form-control @error('excerpt') is-invalid @enderror" id="excerpt" name="excerpt" rows="3" maxlength="500">{{ old('excerpt', $blog->excerpt) }}</textarea>
            @error('excerpt')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">Ringkasan singkat blog (maksimal 500 karakter)</small>
        </div>

        <div class="mb-4">
            <label for="content" class="form-label">Konten Blog <span class="text-danger">*</span></label>
            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="10" required>{{ old('content', $blog->content) }}</textarea>
            @error('content')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <label for="category" class="form-label">Kategori</label>
                <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category', $blog->category) }}" placeholder="Contoh: News, Design, Inspiration">
                @error('category')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-4">
                <label for="tags" class="form-label">Tags</label>
                <input type="text" class="form-control @error('tags') is-invalid @enderror" id="tags" name="tags" value="{{ old('tags', is_array($blog->tags) ? implode(', ', $blog->tags) : $blog->tags) }}" placeholder="Pisahkan dengan koma, contoh: Car Dealer, BMW, Honda">
                @error('tags')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Pisahkan setiap tag dengan koma</small>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                    <option value="draft" {{ old('status', $blog->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status', $blog->status) == 'published' ? 'selected' : '' }}>Published</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex gap-3 mt-4">
            <button type="submit" class="btn btn-submit">
                <i class="fas fa-save me-2"></i>Update Blog
            </button>
            <a href="{{ route('blogs.admin.index') }}" class="btn btn-cancel">
                <i class="fas fa-times me-2"></i>Batal
            </a>
        </div>
    </form>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<p class="mb-2"><strong>Preview Gambar Baru:</strong></p><img src="' + e.target.result + '" class="image-preview" alt="Preview">';
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.innerHTML = '';
    }
}
</script>
@endsection

