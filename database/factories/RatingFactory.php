<?php

namespace Database\Factories;

use App\Models\Rating;
use App\Models\User;
use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RatingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Rating::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $users = User::pluck('id')->toArray();
        $articles = Article::pluck('id')->toArray();

        return [
            'user_id' => $this->faker->randomElement($users),
            'article_id' => $this->faker->randomElement($articles),
            'rating'  => $this->faker->randomNumber(),
        ];
    }
}
