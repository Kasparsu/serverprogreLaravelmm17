<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function show($post)
    {
        $posts = [
            'hello' => 'hello',
            'hello-again' => 'Hello Again'
        ];
        if (!array_key_exists($post, $posts)) {
            abort(404, 'Post not found');
        }
        return view('test', [
            'post' => $posts[$post]
        ]);
    }
}
