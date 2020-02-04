<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;

class AdminBlogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // 現在実行しているアクション名を取得
        // アクション名により、どのルールを使うのか場合分けをしておく
        $action = $this->getCurrentAction();

        $rules['post'] = [
            'post_date' => 'required|date',
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:10000',
        ];

        $rules['delete'] = [
            'article_id' => 'required|integer'
        ];

        $rules['editCategory'] = [
            'category_id' => 'integer|min:1|nullable',
            'name' => 'required|string|max:255',
            'display_order' => 'required|integer|min:1'
        ];

        $rules['deleteCategory'] = [
            'category_id' => 'required|integer|min:1'
        ];

        return Arr::get($rules, $action, []);
    }
    public function messages()
    {
        // バリデートエラーのメッセージ
        return [
            'post_date.required' => '日付は必須です',
            'post_date.date' => '日付は日付形式で入力してください',
            'title.required' => 'タイトルは必須です',
            'title.string' => 'タイトルは文字列を入力してください',
            'title.max' => 'タイトルは:max文字以内で入力してください',
            'body.required' => '本文は必須です',
            'body.string' => '本文は文字列を入力してください',
            'body.max' => '本文は:max文字以内で入力してください',
            'category_id.required' => 'カテゴリーIDは必須です',
            'category_id.integer' => 'カテゴリーIDは整数でなければなりません',
            'category_id.min' => 'カテゴリーIDは1以上でなければなりません',
            'name.required' => 'カテゴリ名は必須です',
            'name.string' => 'カテゴリ名は文字列を入力してください',
            'name.max' => 'カテゴリ名は:max文字以内で入力してください',
            'display_order.required' => '表示順は必須です',
            'display_order.integer' => '表示順は整数を入力してください',
            'display_order.min' => '表示順は1以上を入力してください',
        ];
    }

    /**
     * オーバーライドしてAPIとして実行されているときのレスポンスを json にする
     *
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        $action = $this->getCurrentAction();

        if ($action == 'post' || $action == 'delete') {
            parent::failedValidation($validator);
        }

        $response['errors'] = $validator->errors()->toArray();
        throw new HttpResponseException(
            response()->json($response, 422)
        );
    }


    /**
     * 現在実行中のアクション名を返す
     *
     * @return mixed
     */
    public function getCurrentAction()
    {
        // 実行中のアクション名を取得
        // App\Http\Controllers\AdminBlogController@post のような返り値が返ってくるので @ で分割
        $route_action = Route::currentRouteAction();
        list(, $action) = explode('@', $route_action);
        return $action;
    }
}
