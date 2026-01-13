@foreach($replies as $reply)
@php
    $marginLeft = 80 + ($level * 40); // Increase margin for deeper nesting
    $avatarSize = max(40, 50 - ($level * 5)); // Decrease avatar size for deeper nesting
    $fontSize = max(14, 16 - ($level * 1)); // Decrease font size for deeper nesting
@endphp
<div class="blog__details__comment__item reply__comment" id="comment-{{ $reply->id }}" style="margin-left: {{ $marginLeft }}px; margin-top: 20px;">
    <div class="blog__details__comment__item__pic">
        <div style="width: {{ $avatarSize }}px; height: {{ $avatarSize }}px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6, #60a5fa); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: bold; font-size: {{ $fontSize }}px;">
            {{ strtoupper(substr($reply->commenter_name, 0, 1)) }}
        </div>
    </div>
    <div class="blog__details__comment__item__text">
        <h6>{{ $reply->commenter_name }}</h6>
        <p>{{ $reply->comment }}</p>
        <ul>
            <li><i class="fa fa-calendar-o"></i> {{ $reply->created_at->format('M d, Y H:i') }}</li>
            <li><a href="#" class="reply-btn" data-comment-id="{{ $reply->id }}" data-commenter="{{ $reply->commenter_name }}"><i class="fa fa-reply"></i> Reply</a></li>
        </ul>
    </div>
    
    <!-- Recursive: Show nested replies -->
    @if($reply->replies->count() > 0)
        @include('blog.comment-replies', ['replies' => $reply->replies, 'level' => $level + 1])
    @endif
</div>
@endforeach

