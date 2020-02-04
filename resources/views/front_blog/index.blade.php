@extends('front_blog.app')
@section('title', 'そんな感じの備忘録')
@section('main')
    <div class="col-md-10 col-md-offset-1">
{{--    @forelseは、データがある間ループし、ない場合は@empty以下を実行--}}
        @forelse($list as $article)
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $article->post_date->format('Y/m/d(D)') }}　{{ $article->title }}</h4>
                </div>
                <div class="card-body">
                    {!! nl2br(e($article->body)) !!}
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('front_index', ['category_id' => $article->category->category_id]) }}">
                        {{ $article->category->name }}
                    </a>
                    &nbsp;&nbsp;
                    {{ $article->updated_at->format('Y/m/d H:i:s') }}
                </div>
            </div>
            <br>
        @empty
            <p>記事がありません</p>
        @endforelse

        {{ $list->links() }}
    </div>
@endsection
