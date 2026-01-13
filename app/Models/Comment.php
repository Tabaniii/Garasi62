<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'blog_id',
        'user_id',
        'parent_id',
        'name',
        'email',
        'comment',
        'status',
    ];

    // Relationship with Blog
    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Parent Comment (for replies)
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    // Relationship with Replies (all levels)
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->where('status', 'approved')->orderBy('created_at', 'asc');
    }

    // Recursive relationship for nested replies
    public function nestedReplies()
    {
        return $this->replies()->with('nestedReplies');
    }

    // Scope for approved comments
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Scope for pending comments
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Get commenter name
    public function getCommenterNameAttribute()
    {
        return $this->user ? $this->user->name : $this->name;
    }

    // Get commenter email
    public function getCommenterEmailAttribute()
    {
        return $this->user ? $this->user->email : $this->email;
    }
}
