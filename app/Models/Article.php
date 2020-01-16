<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'article_id';
    protected $fillable = ['post_date', 'title', 'body'];
    protected $dates = ['post_date', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * post_date のアクセサ YYYY/MM/DD のフォーマットにする
     *
     * @return string
     */

    public function getPostDateTextAttribute()
    {
        return $this->post_date->format('Y/m/d');
    }

    /**
     * post_date のミューテタ YYYY-MM-DD のフォーマットでセットする
     *
     * @param $value
     */
    public function setPostDateAttribute($value)
    {
        $post_date = new Carbon($value);
        $this->attributes['post_date'] = $post_date->format('Y-m-d');
    }

    /**
     * 記事リストを取得する
     *
     * @param  int $num_per_page 1ページ当たりの表示件数
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getArticleList($num_per_page = 10)
    {
        return $this->orderBy('article_id', 'desc')->paginate($num_per_page);
    }
}
