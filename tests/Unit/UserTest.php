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
            })->get()->pluck('id'),
            $user->favoritedArticles->pluck('id')
        );
    }

}
