@extends('template.temp')

@section('title', 'Blog - GARASI62')

@section('content')
@php
    use Illuminate\Support\Str;
@endphp

    <!-- Breadcrumb End -->
    <div class="breadcrumb-option set-bg" data-setbg="img/breadcrumb-bg.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Latest Blogs</h2>
                        <div class="breadcrumb__links">
                            <a href="{{ route('index') }}"><i class="fa fa-home"></i> Home</a>
                            <span>Our Blogs</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Begin -->

    <!-- Blog Section Begin -->
    <section class="blog spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    @if($blogs->count() > 0)
                    <div class="row">
                        @foreach($blogs as $blog)
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="blog__item">
                                <div class="blog__item__pic set-bg" data-setbg="{{ $blog->image ? asset('storage/' . $blog->image) : asset('img/blog/blog-1.jpg') }}">
                                    <ul>
                                        <li>By {{ $blog->author }}</li>
                                        <li>{{ $blog->published_at ? $blog->published_at->format('M d, Y') : $blog->created_at->format('M d, Y') }}</li>
                                        <li>{{ $blog->comment_count }} {{ $blog->comment_count == 1 ? 'Comment' : 'Comments' }}</li>
                                    </ul>
                                </div>
                                <div class="blog__item__text">
                                    <h5><a href="{{ route('blog.show', $blog->slug) }}">{{ $blog->title }}</a></h5>
                                    <p>{{ $blog->excerpt ? Str::limit($blog->excerpt, 150) : Str::limit(strip_tags($blog->content), 150) }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if($blogs->hasPages())
                    <div class="pagination__option">
                        @if($blogs->onFirstPage())
                        <span class="disabled"><span class="arrow_carrot-2left"></span></span>
                        @else
                        <a href="{{ $blogs->previousPageUrl() }}"><span class="arrow_carrot-2left"></span></a>
                        @endif

                        @php
                        $currentPage = $blogs->currentPage();
                        $lastPage = $blogs->lastPage();
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($lastPage, $currentPage + 2);

                        if ($startPage > 1) {
                            $startPage = 1;
                        }
                        if ($endPage < $lastPage && ($endPage - $startPage) < 4) {
                            $endPage = min($lastPage, $startPage + 4);
                        }
                        @endphp

                        @if($startPage > 1)
                        <a href="{{ $blogs->url(1) }}">1</a>
                        @if($startPage > 2)
                        <span>...</span>
                        @endif
                        @endif

                        @for($page = $startPage; $page <= $endPage; $page++)
                            @if($page == $currentPage)
                            <a href="#" class="active">{{ $page }}</a>
                            @else
                            <a href="{{ $blogs->url($page) }}">{{ $page }}</a>
                            @endif
                        @endfor

                        @if($endPage < $lastPage)
                            @if($endPage < $lastPage - 1)
                            <span>...</span>
                            @endif
                            <a href="{{ $blogs->url($lastPage) }}">{{ $lastPage }}</a>
                        @endif

                        @if($blogs->hasMorePages())
                        <a href="{{ $blogs->nextPageUrl() }}"><span class="arrow_carrot-2right"></span></a>
                        @else
                        <span class="disabled"><span class="arrow_carrot-2right"></span></span>
                        @endif
                    </div>
                    @endif
                    @else
                    <div class="text-center py-5">
                        <h4>Belum ada blog yang tersedia</h4>
                        <p class="text-muted">Blog akan muncul di sini setelah admin menambahkan konten.</p>
                    </div>
                    @endif
                </div>
                <div class="col-lg-3 col-md-6 col-sm-9">
                    <div class="blog__sidebar">
                        <form action="{{ route('blog') }}" method="GET" class="blog__sidebar__search">
                            <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}">
                            <button type="submit"><i class="fa fa-search"></i></button>
                        </form>
                        @if($featuredBlogs->count() > 0)
                        <div class="blog__sidebar__feature">
                            <h4>Feature News</h4>
                            @foreach($featuredBlogs as $featuredBlog)
                            <div class="blog__sidebar__feature__item">
                                <h6><a href="{{ route('blog.show', $featuredBlog->slug) }}">{{ $featuredBlog->title }}</a></h6>
                                <ul>
                                    <li>By {{ $featuredBlog->author }}</li>
                                    <li>{{ $featuredBlog->published_at ? $featuredBlog->published_at->format('M d, Y') : $featuredBlog->created_at->format('M d, Y') }}</li>
                                </ul>
                            </div>
                            @endforeach
                        </div>
                        @endif
                        @if($categories->count() > 0)
                        <div class="blog__sidebar__categories">
                            <h4>Categories</h4>
                            <ul>
                                @foreach($categories as $category)
                                <li><a href="{{ route('blog', ['category' => $category]) }}">{{ $category }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        @php
                            $allTags = [];
                            foreach($blogs as $blog) {
                                if($blog->tags && is_array($blog->tags)) {
                                    $allTags = array_merge($allTags, $blog->tags);
                                }
                            }
                            $uniqueTags = array_unique($allTags);
                        @endphp
                        @if(count($uniqueTags) > 0)
                        <div class="blog__sidebar__tag">
                            <h4>Tag</h4>
                            @foreach(array_slice($uniqueTags, 0, 10) as $tag)
                            <a href="#">{{ $tag }}</a>
                            @endforeach
                        </div>
                        @endif
                        <div class="blog__sidebar__newslatter">
                            <h4>Newsletter</h4>
                            <p>Subscribe our newsletter for get</p>
                            <form action="#">
                                <input type="text" placeholder="Your email">
                                <button type="submit">Subscribe</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog Section End -->

@endsection

