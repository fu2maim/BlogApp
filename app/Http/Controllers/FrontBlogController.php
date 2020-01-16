<?php

namespace App\Http\Controllers;

use App\Models\Article;

class FrontBlogController extends Controller
{
    /** @var Article */
    protected $article;

    // 1ページ当たりの表示件数
    const NUM_PER_PAGE = 10;

    function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * ブログトップページ
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function index()
    {
        // ブログ記事一覧を取得
        $list = $this->article->getArticleList(self::NUM_PER_PAGE);
        return view('front_blog.index', compact('list'));
    }
}
