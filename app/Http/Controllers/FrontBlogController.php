<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Http\Requests\FrontBlogRequest;

class FrontBlogController extends Controller
{
    /** @var Article */
    protected $article;

    // 1ページ当たりの表示件数
    const NUM_PER_PAGE = 5;

    // コンストラクタ
    function __construct(Article $article, Category $category)
    {
        $this->article = $article;
        $this->category = $category;
    }

    /**
     * ブログトップページ
     *
     * @param FrontBlogRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function index(FrontBlogRequest $request)
    {
        $input = $request->input();

        $list = $this->article->getArticleList(self::NUM_PER_PAGE, $input);

        $list->appends($input);

        $category_list = $this->category->getCategoryList();

        $month_list = $this->article->getMonthList();

        return view('front_blog.index', compact('list', 'month_list', 'category_list'));
    }
}
