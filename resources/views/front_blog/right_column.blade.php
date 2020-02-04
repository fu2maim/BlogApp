{{--右カラム--}}
<div class="col-md-2">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">カテゴリー</h5>
        </div>
        <div class="card-body">
            <ul class="monthly_archive">
                @forelse($category_list as $category)
                    <li>
                        <a href="{{ route('front_index', ['category_id' => $category->category_id]) }}">
                            {{ $category->name }}
                        </a>
                    </li>
                @empty
                    <p>カテゴリーがありません</p>
                @endforelse
            </ul>
        </div>
    </div>
    <br>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">月別アーカイブ</h5>
        </div>
        <div class="card-body">
            <ul class="monthly_archive">
                @forelse($month_list as $value)
                    <li>
                        <a href="{{ route('front_index', ['year' => $value->year, 'month' => $value->month]) }}">
                            {{ $value->year_month }}
                        </a>
                    </li>
                @empty
                    <p>記事がありません</p>
                @endforelse
            </ul>
        </div>
    </div>
</div>
