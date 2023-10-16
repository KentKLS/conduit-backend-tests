<?php

namespace Tests\Unit;

use App\Models\Article;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\TestCase;

class UserTest extends \Tests\TestCase
{
    use RefreshDatabase;
    /**
     *
     * A basic unit test example.
     *
     * @return void
     */

    public function test_getRouteKeyName()
    {
        $this->assertEquals('username', User::make()->getRouteKeyName());
    }
    public function test_articles(){
        $user = User::firstWhere('username','Rose');
        $this->assertEquals(
            Article::where('user_id', $user->id)->get(),
            $user->articles
        );
    }

    public function test_favouriteArticles(){
        $user = User::firstWhere('username','Rose');
        $this->assertEquals(
            Article::whereHas('users',function (Builder $query ){
                $query->where('username','Rose');
            })->get()->pluck(['id', 'username', 'email', 'password', 'bio']),
            $user->favoritedArticles->pluck(['id', 'username', 'email', 'password', 'bio'])
        );
    }
    public function test_followers(){
        $user = User::firstWhere('username','Rose');
        $this->assertEquals(
            User::whereHas('following',function (Builder $query) use ($user) {
                $query->where('following_id', $user->id);
            })->get()->pluck(['id', 'username', 'email', 'password', 'bio']),
            $user->followers->pluck(['id', 'username', 'email', 'password', 'bio'])
        );
    }
    public function test_following(){
        $user = User::firstWhere('username','Rose');
        $this->assertEquals(
            User::whereHas('followers',function (Builder $query) use ($user) {
                $query->where('follower_id', $user->id);
            })->get()->pluck(['id', 'username', 'email', 'password', 'bio']),
            $user->following->pluck(['id', 'username', 'email', 'password', 'bio'])
        );
    }

    public function test_DoesUserFollowAnotherUser(){
        $user = User::firstWhere('username','Rose');
        $user1 = User::firstWhere('username','Musonda');
        $this->assertTrue(
            $user->doesUserFollowAnotherUser($user1->id, $user->id)
        );
    }

    public function test_doesUserFollowArticle(){
        $user = User::firstWhere('username','Rose');
        $article= Article::whereHas('users', function (Builder $query) use ($user){
            $query->where("id", $user->id);
        })->get()->first();
        $this->assertTrue(
            $user->doesUserFollowArticle($user->id, $article->id)
        );
        $this->assertFalse(
            $user->doesUserFollowArticle($article->user_id ,$article->id)
        );
    }

    public function test_setPasswordAttribute(){
        $user = User::firstWhere('username','Rose');
        $unashedPassword = 'pwd';
        $user->setPasswordAttribute($unashedPassword);
        $user->save();
        $this->assertCredentials(['username'=>$user->username, 'password'=>$unashedPassword], 'web');
        $user->password ='test';
        $user->save();
        $this->assertCredentials(['username'=>$user->username, 'password'=>'test'], 'web');
    }

    public function test_getJWTIdentifier(){
        $user = User::firstWhere('username','Rose');
        $this->assertNotNull($user->getJWTIdentifier());
    }
}
