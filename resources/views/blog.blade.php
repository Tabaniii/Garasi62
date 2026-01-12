@extends('template.temp')

@section('title', $blog->title . ' - GARASI62')

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

                    <!-- Comments Section (Placeholder - Anda bisa integrasikan sistem komentar nanti) -->
                    <div class="blog__details__comment">
                        <h4>{{ $blog->comment_count }} {{ $blog->comment_count == 1 ? 'Comment' : 'Comments' }}</h4>
                        <div class="alert alert-info">
                            Sistem komentar sedang dalam pengembangan. Silakan hubungi kami untuk memberikan masukan.
                        </div>
                    </div>

                    <!-- Comment Form (Nonaktif sementara) -->
                    {{-- 
                    <div class="blog__details__comment__form">
                        <h4>Leave A Reply</h4>
                        <form action="#">
                            <div class="input-list">
                                <div class="input-list-item">
                                    <p>Name <span class="text-danger">*</span></p>
                                    <input type="text" required>
                                </div>
                                <div class="input-list-item">
                                    <p>Email <span class="text-danger">*</span></p>
                                    <input type="email" required>
                                </div>
                                <div class="input-list-item">
                                    <p>Website</p>
                                    <input type="text">
                                </div>
                            </div>
                            <div class="input-desc">
                                <p>Comment <span class="text-danger">*</span></p>
                                <textarea required></textarea>
                            </div>
                            <button type="submit" class="site-btn">Submit Now</button>
                        </form>
                    </div>
                    --}}
                </div>
            </div>
        </div>
    </section>
    <!-- Blog Details Section End -->

@endsection