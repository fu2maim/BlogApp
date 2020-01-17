@extends('front_blog.app')
@section('title', '私のブログ')
@section('main')
    <div class="col-md-10 col-md-offset-1">
        @forelse($list as $article)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $article->post_date->format('Y/m/d(D)') }}　{{ $article->title }}</h3>
                </div>
                <div class="card-body">
                    {!! nl2br(e($article->body)) !!}
                </div>
                <div class="card-footer text-right">
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
