<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReplyFactory extends Factory {

    public function definition () {

        return [
            'user_id' => 5,
            'comment_id' => 1,
            'blog_id' => 1,
            'content' => fake()->paragraph(),
        ];

    }

}
