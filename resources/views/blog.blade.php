@extends('template.temp')

@section('title', $blog->title . ' - GARASI62')
@include('components.messages-widget')

@section('content')
    <!-- Blog Details Hero Begin -->
    <section class="blog-details-hero spad set-bg" data-setbg="{{ $blog->image ? asset('storage/' . $blog->image) : asset('img/blog/details/details-hero-bg.jpg') }}">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-10">
                    <div class="blog__details__hero__text">
                        @if($blog->tags && is_array($blog->tags))
                            <span class="label">{{ implode(', ', $blog->tags) }}</span>
                        @else
                            <span class="label">{{ $blog->category ?? 'Blog' }}</span>
                        @endif
                        <h2>{{ $blog->title }}</h2>
                        <ul>
                            <li><i class="fa fa-user"></i> <span>By {{ $blog->author }}</span></li>
                            <li><i class="fa fa-calendar-o"></i> <span>{{ $blog->published_at ? $blog->published_at->format('M d, Y') : $blog->created_at->format('M d, Y') }}</span></li>
                            <li><i class="fa fa-comments"></i> <span>{{ $blog->comment_count }} {{ $blog->comment_count == 1 ? 'Comment' : 'Comments' }}</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog Details Hero End -->

    <!-- Blog Details Section Begin -->
    <section class="blog-details spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="blog__details__pic">
                        <img src="{{ $blog->image ? asset('storage/' . $blog->image) : asset('img/blog/details/details-pic.jpg') }}" alt="{{ $blog->title }}">
                    </div>
                </div>
            </div>
            <div class="row d-flex justify-content-center">
                <div class="col-lg-8">
                    <div class="blog__details__text">
                        {!! $blog->content !!}
                    </div>                    

                    <!-- Author Bio (opsional, bisa ambil dari user jika ada relasi) -->
                    <div class="blog__details__author">
                        <div class="blog__details__author__text">
                            <h5>{{ $blog->author }}</h5>
                            <p>Penulis artikel ini adalah bagian dari tim GARASI62 yang berdedikasi menyediakan konten berkualitas.</p>
                        </div>
                    </div>

                    <!-- Related Posts -->
                    @if($relatedBlogs->count() > 0)
                    <div class="blog__details__btns">
                        <div class="row">
                            <div class="col-lg-12">
                                <h5 class="mb-4">Artikel Terkait</h5>
                                <div class="row">
                                    @foreach($relatedBlogs as $index => $related)
                                        @if($index < 2)
                                        <div class="col-lg-6">
                                            <a href="{{ route('blog.show', $related->slug) }}" class="blog__details__btns__item set-bg"
                                                data-setbg="{{ $related->image ? asset('storage/' . $related->image) : asset('img/blog/blog-1.jpg') }}">
                                                <h6>{{ $related->title }}</h6>
                                                <ul>
                                                    <li>By {{ $related->author }}</li>
                                                    <li>{{ $related->published_at ? $related->published_at->format('M d, Y') : $related->created_at->format('M d, Y') }}</li>
                                                </ul>
                                            </a>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Comments Section -->
                    <div class="blog__details__comment">
                        <h4>{{ $blog->comments->count() }} {{ $blog->comments->count() == 1 ? 'Comment' : 'Comments' }}</h4>
                        
                        @if($blog->comments->count() > 0)
                            @foreach($blog->comments as $comment)
                            <div class="blog__details__comment__item" id="comment-{{ $comment->id }}">
                                <div class="blog__details__comment__item__pic">
                                    <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #dc2626, #991b1b); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: bold; font-size: 20px;">
                                        {{ strtoupper(substr($comment->commenter_name, 0, 1)) }}
                                    </div>
                                </div>
                                <div class="blog__details__comment__item__text">
                                    <h6>{{ $comment->commenter_name }}</h6>
                                    <p>{{ $comment->comment }}</p>
                                    <ul>
                                        <li><i class="fa fa-calendar-o"></i> {{ $comment->created_at->format('M d, Y H:i') }}</li>
                                        <li><a href="#" class="reply-btn" data-comment-id="{{ $comment->id }}" data-commenter="{{ $comment->commenter_name }}"><i class="fa fa-reply"></i> Reply</a></li>
                                    </ul>
                                </div>
                                
                                <!-- Nested Replies (Recursive) -->
                                @if($comment->replies->count() > 0)
                                    @include('blog.comment-replies', ['replies' => $comment->replies, 'level' => 1])
                                @endif
                            </div>
                            @endforeach
                        @else
                            <div class="alert alert-info">
                                Belum ada komentar. Jadilah yang pertama untuk berkomentar!
                            </div>
                        @endif
                    </div>

                    <!-- Comment Form -->
                    <div class="blog__details__comment__form" id="commentForm" data-is-logged-in="{{ Auth::check() ? 'true' : 'false' }}">
                        <h4>Leave A Reply</h4>
                        <form id="commentFormElement">
                            <input type="hidden" id="parent_id" name="parent_id" value="">
                            <div id="replyTo" style="display: none; margin-bottom: 15px; padding: 10px; background: #f3f4f6; border-radius: 5px;">
                                <span>Membalas ke: <strong id="replyToName"></strong></span>
                                <a href="#" id="cancelReply" style="float: right; color: #dc2626;">Batal</a>
                            </div>
                            <div class="input-desc">
                                <p>Comment <span class="text-danger">*</span></p>
                                <textarea id="commentText" name="comment" required rows="5" placeholder="Tulis komentar Anda di sini..."></textarea>
                            </div>
                            <button type="submit" class="site-btn" id="submitCommentBtn">
                                <span id="submitBtnText">Submit Now</span>
                                <span id="submitBtnLoading" style="display: none;">Mengirim...</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog Details Section End -->

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const commentForm = document.getElementById('commentFormElement');
    const submitBtn = document.getElementById('submitCommentBtn');
    const submitBtnText = document.getElementById('submitBtnText');
    const submitBtnLoading = document.getElementById('submitBtnLoading');
    const commentText = document.getElementById('commentText');
    const parentIdInput = document.getElementById('parent_id');
    const replyToDiv = document.getElementById('replyTo');
    const replyToName = document.getElementById('replyToName');
    const cancelReplyBtn = document.getElementById('cancelReply');
    const commentFormDiv = document.getElementById('commentForm');
    
    // Check if user is logged in
    const isLoggedIn = commentFormDiv.getAttribute('data-is-logged-in') === 'true';
    
    // Handle reply button click using event delegation (works for all levels including nested)
    document.addEventListener('click', function(e) {
        if (e.target.closest('.reply-btn')) {
            e.preventDefault();
            const replyBtn = e.target.closest('.reply-btn');
            
            if (!isLoggedIn) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Login Diperlukan',
                    text: 'Anda harus login terlebih dahulu untuk mengirim komentar.',
                    showCancelButton: true,
                    confirmButtonText: 'Login',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#dc2626'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route("login") }}';
                    }
                });
                return;
            }
            
            const commentId = replyBtn.getAttribute('data-comment-id');
            const commenterName = replyBtn.getAttribute('data-commenter');
            
            parentIdInput.value = commentId;
            replyToName.textContent = commenterName;
            replyToDiv.style.display = 'block';
            
            // Scroll to form
            document.getElementById('commentForm').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            commentText.focus();
        }
    });
    
    // Cancel reply
    cancelReplyBtn.addEventListener('click', function(e) {
        e.preventDefault();
        parentIdInput.value = '';
        replyToDiv.style.display = 'none';
    });
    
    // Handle form submission
    commentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Check if user is logged in
        if (!isLoggedIn) {
            Swal.fire({
                icon: 'warning',
                title: 'Login Diperlukan',
                text: 'Anda harus login terlebih dahulu untuk mengirim komentar.',
                showCancelButton: true,
                confirmButtonText: 'Login',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc2626'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route("login") }}';
                }
            });
            return;
        }
        
        const comment = commentText.value.trim();
        
        if (!comment) {
            Swal.fire({
                icon: 'error',
                title: 'Komentar Kosong',
                text: 'Silakan isi komentar Anda terlebih dahulu.',
                confirmButtonColor: '#dc2626'
            });
            return;
        }
        
        // Disable submit button
        submitBtn.disabled = true;
        submitBtnText.style.display = 'none';
        submitBtnLoading.style.display = 'inline';
        
        // Prepare data
        const formData = {
            comment: comment,
            parent_id: parentIdInput.value || null,
            _token: '{{ csrf_token() }}'
        };
        
        // Send AJAX request
        fetch('{{ route("comments.store", $blog->slug) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Komentar Terkirim!',
                    text: data.message,
                    confirmButtonColor: '#dc2626'
                }).then(() => {
                    // Reset form
                    commentText.value = '';
                    parentIdInput.value = '';
                    replyToDiv.style.display = 'none';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Mengirim Komentar',
                    text: data.message || 'Terjadi kesalahan. Silakan coba lagi.',
                    confirmButtonColor: '#dc2626'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: 'Tidak dapat mengirim komentar. Silakan coba lagi.',
                confirmButtonColor: '#dc2626'
            });
        })
        .finally(() => {
            // Enable submit button
            submitBtn.disabled = false;
            submitBtnText.style.display = 'inline';
            submitBtnLoading.style.display = 'none';
        });
    });
});
</script>
@endpush

@endsection