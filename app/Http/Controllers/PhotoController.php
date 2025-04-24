<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Category;

class PhotoController extends Controller
{
    public function index(Request $request)
    {
        $categories = \App\Models\Category::all();
        $category = $request->input('category');
        $query = $request->input('query');
        $photoQuery = Photo::with('category', 'user')->latest();

        if ($category) {
            $photoQuery->where('category', $category);
            
        }
        if ($query) {
            $photoQuery->where('image', 'like', "%$query%");
        }

        $photos = $photoQuery->get();

        return view('galeri', compact('photos', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image',
            'category' => 'required|exists:categories,category',
            'comments_enabled' => 'required|boolean',
        ]);

        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('uploads'), $imageName);

        Photo::create([
            'user_id' => Auth()->id(),
            'image' => $imageName,
            'category' => $request->category,
            'comments_enabled' => $request->comments_enabled,
        ]);

        return redirect()->route('galeri')->with('success', 'Foto berhasil diunggah');
    }

    public function destroy($id)
    {
        $photo = Photo::findOrFail($id);

        if (!auth()->user()->is_admin && auth()->id() !== $photo->user_id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus foto ini.');
        }        

        $filePath = public_path('uploads/' . $photo->image);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $photo->delete();

        return redirect()->back()->with('success', 'Foto berhasil dihapus');
    }

    public function addComment(Request $request, $photo_id)
    {
        $request->validate(['comment' => 'required']);

        Comment::create([
            'photo_id' => $photo_id,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    public function likePhoto($photo_id)
    {
        $existingLike = Like::where('photo_id', $photo_id)->where('user_id', Auth::id())->first();

        if ($existingLike) {
            $existingLike->delete();
            return redirect()->back()->with('success', 'Like dihapus');
        }

        Like::create([
            'photo_id' => $photo_id,
            'user_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Foto disukai');
    }

    public function deleteComment($id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return redirect()->back()->with('error', 'Komentar tidak ditemukan.');
        }

        if (auth()->user()->id !== $comment->user_id && !auth()->user()->is_admin) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus komentar ini.');
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Komentar berhasil dihapus.');
    }

    public function show($id)
    {
        $photo = Photo::with(['category', 'likes', 'comments.user'])->find($id);

        if (!$photo) {
            abort(404, 'Foto tidak ditemukan');
        }
        
        return view('photo.show', compact('photo'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $category = $request->input('category');
        $photos = Photo::query();

        if ($category) {
            $photos->where('category', $category);
        }

        if ($query) {
            $photos = $photos->where('image', 'like', "%$query%");
        }

        $photos = $photos->latest()->get();
        $categories = Category::all();

        return view('galeri', compact('photos', 'categories'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('photos.create', compact('categories'));
    }

    public function likeComment($id)
    {
        $comment = Comment::findOrFail($id);
        $user = auth()->user();

        $like = $comment->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            return back()->with('success', 'Reaksi dihapus');
        }

        \App\Models\CommentLike::create([
            'comment_id' => $id,
            'user_id' => $user->id,
        ]);

        return back()->with('success', 'Komentar disukai');
    }

    public function adminDestroy($id)
    {
        $photo = Photo::findOrFail($id);
        $filePath = public_path('uploads/' . $photo->image);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        $photo->delete();

        return redirect()->back()->with('success', 'Foto berhasil dihapus.');
    }

}
