<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    public function show($articleId){
        $article = Article::find($articleId);
        return view('articles.show', [
            'article' => $article
        ]);
    }
}
