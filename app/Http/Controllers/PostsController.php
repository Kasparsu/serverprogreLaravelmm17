<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

use DB;

class PostsController extends Controller
{
    public function show($slug)
    {
        return view('test', [
            'post' => Post::where('slug', $slug)->firstOrFail()
        ]);
    }
}
