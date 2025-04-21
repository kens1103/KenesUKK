<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Photo;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Like;

class UserDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $categories = Category::all();

        // ADMIN (MENAMPILKAN SELURUH FOTO)
        if ($user->role === 'admin') {
            $query = Photo::with(['likes.user', 'comments.user']);
        } else {
            // USER (HANYA MENAMPILKAN FOTO YANG DIUPLOAD OLEH USER)
            $query = Photo::with(['likes.user', 'comments.user'])->where('user_id', Auth::id());
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $photos = $query->latest()->paginate(20);

        return view('dashboard', compact('photos', 'categories'));
    }

    public function destroyPhoto($id)
    {
        $photo = Photo::findOrFail($id);
        $path = public_path('uploads/' . $photo->image);
        if (file_exists($path)) {
            unlink($path);
        }
        $photo->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
    }
    
    public function destroyComment($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return back()->with('success', 'Komentar berhasil dihapus.');
    }

}
