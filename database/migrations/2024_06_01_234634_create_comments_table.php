<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up () {

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->integer('blog_id')->default(0);
            $table->longText('content')->nullable();
            $table->integer('likes')->default(0);
            $table->integer('dislikes')->default(0);
            $table->boolean('allow_replies')->default(true);
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

    }

};