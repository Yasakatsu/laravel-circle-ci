<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    // 記事一覧画面のテスト
    // (1)関数：testIndex
    // (2)route関数でarticles.indexにアクセス　|　$this：ArticleControllerTest
    // (3)　ステータスコード200を確認(アクセス成功/正常終了。アクセス失敗/異常終了は404)　|　$response：レスポンス
    // (4)　ビューがarticles.indexであることをテスト
    public function testIndex() // (1)
    {
        $response = $this->get(route('articles.index'));  // (2)
        $response->assertStatus(200)  // (3)
            ->assertViewIs('articles.index');  // (4)
    }
    // 記事投稿画面のテスト(未ログイン時)
    // (1)関数：testGuestCreate
    // (2)route関数でarticles.createにアクセス　|　$this：ArticleControllerTest
    // (3)　リダイレクト先がloginであることを確認
    // (4)　ログイン画面にリダイレクトされることをテスト
    public function testGuestCreate() // (1)
    {
        $response = $this->get(route('articles.create'));  // (2)
        $response->assertRedirect(route('login'));  // (3)
    }

    // 記事投稿画面のテスト(ログイン時)
    // AAAパターン：Arrange(準備)、Act(実行)、Assert(検証)
    
    // (1)関数：testAuthCreate
    // (2)factory関数でUserモデルのインスタンスを作成し、データベースに保存　|　$this：ArticleControllerTest
    // (3)actingAsメソッドでユーザーを認証し、route関数でarticles.createにアクセス　|　$this：ArticleControllerTest
    // (4)　ステータスコード200を確認(アクセス成功/正常終了。アクセス失敗/異常終了は404/リダイレクト時は302)　|　$response：レスポンス
    // (5)　ビューがarticles.createであることをテスト

    public function testAuthCreate() // (1)
    {
        // テストに必要なUserモデルのインスタンスを「準備」
        $user = factory(User::class)->create();  // (2)
        // ログインして記事投稿画面にアクセスする事を「実行」
        $response = $this->actingAs($user)  // (3)　
            ->get(route('articles.create'));
        // レスポンスを「検証」
        $response->assertStatus(200)  // (4)　
            ->assertViewIs('articles.create'); // (5) 
    }
}
