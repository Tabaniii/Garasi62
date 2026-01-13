<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Blog;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // Store new comment
    public function store(Request $request, $blogSlug)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu untuk mengirim komentar.'
            ], 401);
        }

        $blog = Blog::where('slug', $blogSlug)->firstOrFail();

        $request->validate([
            'comment' => 'required|string|min:3|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = Comment::create([
            'blog_id' => $blog->id,
            'user_id' => Auth::id(),
            'parent_id' => $request->parent_id,
            'comment' => $request->comment,
            'status' => 'pending',
        ]);

        // Update comment count in blog (will be updated after approval)
        // $blog->increment('comment_count');

        return response()->json([
            'success' => true,
            'message' => 'Komentar Anda telah dikirim dan akan diperiksa oleh admin terlebih dahulu. Terima kasih!'
        ]);
    }

    // Admin: Get all comments
    public function adminIndex()
    {
        $comments = Comment::with(['blog', 'user', 'parent'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $pendingCount = Comment::where('status', 'pending')->count();
        $approvedCount = Comment::where('status', 'approved')->count();
        $rejectedCount = Comment::where('status', 'rejected')->count();

        return view('comments.index', compact('comments', 'pendingCount', 'approvedCount', 'rejectedCount'));
    }

    // Admin: Approve comment
    public function approve($id)
    {
        try {
            $comment = Comment::findOrFail($id);
            $comment->status = 'approved';
            $comment->save();

            // Update blog comment count (only count approved comments without parent)
            $blog = $comment->blog;
            $blog->comment_count = Comment::where('blog_id', $blog->id)
                ->whereNull('parent_id')
                ->where('status', 'approved')
                ->count();
            $blog->save();

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Komentar berhasil disetujui!'
                ]);
            }

            return redirect()->route('comments.admin.index')->with('success', 'Komentar berhasil disetujui!');
        } catch (\Exception $e) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('comments.admin.index')->with('error', 'Terjadi kesalahan!');
        }
    }

    // Admin: Reject comment
    public function reject($id)
    {
        try {
            $comment = Comment::findOrFail($id);
            $comment->status = 'rejected';
            $comment->save();

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Komentar berhasil ditolak!'
                ]);
            }

            return redirect()->route('comments.admin.index')->with('success', 'Komentar berhasil ditolak!');
        } catch (\Exception $e) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('comments.admin.index')->with('error', 'Terjadi kesalahan!');
        }
    }

    // Admin: Delete comment
    public function destroy($id)
    {
        try {
            $comment = Comment::findOrFail($id);
            $blog = $comment->blog;
            $comment->delete();

            // Update blog comment count (only count approved comments without parent)
            $blog->comment_count = Comment::where('blog_id', $blog->id)
                ->whereNull('parent_id')
                ->where('status', 'approved')
                ->count();
            $blog->save();

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Komentar berhasil dihapus!'
                ]);
            }

            return redirect()->route('comments.admin.index')->with('success', 'Komentar berhasil dihapus!');
        } catch (\Exception $e) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('comments.admin.index')->with('error', 'Terjadi kesalahan!');
        }
    }
}
