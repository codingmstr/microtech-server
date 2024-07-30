<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory {

    public function definition () {

        return [
            'user_id' => 5,
            'blog_id' => 1,
            'content' => fake()->paragraph(),
        ];

    }

}
