<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $rose = User::create([
            'username' => 'Rose',
            'email'=> 'rose@mail.com',
            'password' => Hash::make("pwd"),
            'bio' => 'Je voudrais devenir enseignante pour enfants',
            'image' => null,
            'created_at' => date("d-m-Y h:i:sa")
        ]);
        $musonda = User::create([
            'username' => 'Musonda',
            'email'=> 'musonda@mail.com',
            'password' => Hash::make("pwd2"),
            'bio' => "Je songe à devenir infirmière, j'écris mes réflexions",
            'image' => null,
            'created_at' => "18-08-2018 13:37:42"
        ]);
        $rose->following()->attach($musonda);
        $rose->followers()->attach($musonda);
        $rose_article_1 = Article::create([
            'user_id'=> $rose->id,
            'title' => 'i forgor',
            'slug' => '???',
            'description' => 'une description',
            'body' => 'un corps de texte',
        ]);
        $rose_article_1->users()->attach($musonda);
        $musonda_article_1 = Article::create([
            'user_id'=> $musonda->id,
            'title' => 'i forgor',
            'slug' => '???',
            'description' => 'une description',
            'body' => 'un corps de texte',
        ]);
        $musonda_article_2 = Article::create([
            'user_id'=> $musonda->id,
            'title' => 'i forgor',
            'slug' => '???',
            'description' => 'une description',
            'body' => 'un corps de texte',
        ]);
        $musonda_article_1->users()->attach($rose);
        $musonda_article_2->users()->attach($rose);
        $tag_education = Tag::create([
           'name'=> 'éducation'
        ]);
        $rose_article_1->tags()->attach($tag_education);
        Comment::create([
            'user_id' => $musonda->id,
            'article_id' => $rose_article_1->id,
            'body' => "J'adore ta manière de concevoir l'éducation des enfants ^^"
        ]);


    }
}
