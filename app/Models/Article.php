<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

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
     * @param  array $condition    検索条件
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getArticleList(int $num_per_page = 10, array $condition = [])
    {
        // パラメータの取得
        $year = Arr::get($condition, 'year');
        $month = Arr::get($condition, 'month');

        $query = $this->orderBy('article_id', 'desc');

        // 期間の指定
        if ($year) {
            if ($month) {
                // 月の指定がある場合はその月の1日を設定し、Carbonインスタンスを生成
                $start_date = Carbon::createFromDate($year, $month, 1);
                $end_date = Carbon::createFromDate($year, $month, 1)->addMonth();     // 1ヶ月後
            } else {
                // 月の指定が無い場合は1月1日に設定し、Carbonインスタンスを生成
                $start_date = Carbon::createFromDate($year, 1, 1);
                $end_date = Carbon::createFromDate($year, 1, 1)->addYear();           // 1年後
            }
            // Where句を追加
            $query->where('post_date', '>=', $start_date->format('Y-m-d'))
                  ->where('post_date', '<',  $end_date->format('Y-m-d'));
        }

        // paginate メソッドを使うと、ページネーションに必要な全件数やオフセットの指定などは全部やってくれる
        return $query->paginate($num_per_page);
    }

    /**
     * 月別アーカイブの対象月のリストを取得
     *
     * @return Illuminate\Database\Eloquent\Collection Object
     */
    public function getMonthList()
    {
        // selectRaw メソッドを使うと、引数にSELECT文の中身を書いてそのまま実行できる
        $month_list = $this->selectRaw('substring(post_date, 1, 7) AS year_and_month')
            ->groupBy('year_and_month')
            ->orderBy('year_and_month', 'desc')
            ->get();

        foreach ($month_list as $value) {
            // YYYY-MM をハイフンで分解して、YYYY年MM月という表記を作る
            list($year, $month) = explode('-', $value->year_and_month);
            $value->year  = $year;
            $value->month = (int)$month;
            $value->year_month = sprintf("%04d年%02d月", $year, $month);
        }
        return $month_list;
    }
}
