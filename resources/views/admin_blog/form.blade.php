@extends('admin_blog.app')
@section('title', 'ブログ記事投稿フォーム')
@section('body')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h2>ブログ記事投稿・編集</h2>

                {{--if 文による条件分岐--}}
                @if (session('message'))
                    <div class="alert alert-success">
                        {{--セッションヘルパーを使ってキーを指定して、セッションに保存されたデータを取り出す--}}
                        {{ session('message') }}
                    </div>
                    <br>
                @endif

                {{--$errors は Illuminate\Support\MessageBag インスタンスで、エラーメッセージの操作に便利なメソッドを使うことができる--}}
                {{--バリデートエラーがあった場合は、自動的にエラー内容・メッセージが保存された状態で、元のアドレスにリダイレクトされる--}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            {{--foreach 文によるループ--}}
                            {{--エラーメッセージがあるなら、それを全て取り出して表示--}}
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin_post') }}">
                    <div class="form-group">
                        <label>日付</label>
                        <input class="form-control" name="post_date" size="20" value="{{ isset($input['post_date']) ? $input['post_date'] : null }}" placeholder="日付を入力して下さい。">
                    </div>

                    <div class="form-group">
                        <label>カテゴリー</label>
                        <select class="form-control" name="category_id">
                            @foreach ($category_list as $category_id => $category_name)
                                @php
                                    $input_category_id = Arr::get($input, 'category_id');
                                    $selected = ($category_id == $input_category_id) ? ' selected' : null;
                                @endphp
                                <option value="{{ $category_id }}"{{$selected}}>{{ $category_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>タイトル</label>
                        <input class="form-control" name="title" value="{{ isset($input['title']) ? $input['title'] : null }}" placeholder="タイトルを入力して下さい。">
                    </div>

                    <div class="form-group">
                        <label>本文</label>
                        <textarea class="form-control" rows="15" name="body" placeholder="本文を入力してください。">{{ isset($input['body']) ? $input['body'] : null }}</textarea>
                    </div>

                    <input type="submit" class="btn btn-primary btn-sm" value="送信">
                    <input type="hidden" name="article_id" value="{{ $article_id }}">

                    {{ csrf_field() }}
                </form>

                @if ($article_id)
                    <br>
                    <form action="{{ route('admin_delete') }}" method="POST">
                        <input type="submit" class="btn btn-primary btn-sm" value="削除">
                        <input type="hidden" name="article_id" value="{{ $article_id }}">
                        {{ csrf_field() }}
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
