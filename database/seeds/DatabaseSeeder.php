<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (config('database.default') !== 'sqlite') {
        		DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }

        // Model::unguard();

        if (false) {
            App\User::truncate();
            $this->call(UsersTableSeeder::class);

            App\Article::truncate();
            $this->call(ArticlesTableSeeder::class);
            

            App\Tag::truncate();
            DB::table('article_tag')->truncate();
            $tags = config('project.tags');

            foreach($tags as $slug => $name) {
                App\Tag::create([
                    'name' => $name,
                    'slug' => str_slug($slug)
                ]);
            }
            $this->command->info('Seeded: tags table');


            /* 변수선언 */
            $faker = app(Faker\Generator::class);
            $users = App\User::all();
            $articles = App\Article::all();
            $tags = App\Tag::all();


            /* 아티클과 태그 연결 */
            foreach($articles as $article) {
                $article->tags()->sync(
                    $faker->randomElements(
                        $tags->pluck('id')->toArray(), rand(1, 3)
                    )
                );
            }
            $this->command->info('Seeded: article_tag table');
            

            // 최상위 댓글
            $articles->each(function ($article) {
                $article->comments()->save(factory(App\Comment::class)->make());
                $article->comments()->save(factory(App\Comment::class)->make());
            });

            // 자식 댓글
            $articles->each(function ($article) use ($faker) {
                $commentIds = App\Comment::pluck('id')->toArray();

                foreach(range(1,5) as $index) {
                    $article->comments()->save(
                        factory(App\Comment::class)->make([
                            'parent_id' => $faker->randomElement($commentIds),
                        ])
                    );
                }
            });

            $this->command->info('Seeded: comments table');
        }

        $comments = App\Comment::all();

        $comments->each(function ($comment) {
            $comment->votes()->save(factory(App\Vote::class)->make());
            $comment->votes()->save(factory(App\Vote::class)->make());
            $comment->votes()->save(factory(App\Vote::class)->make());
        });

        $this->command->info('Seeded: votes table');

        // Model::reguard();

        if (config('database.default') !== 'sqlite') {
        		DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }
}
