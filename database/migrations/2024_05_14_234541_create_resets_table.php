<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up () {

        Schema::create('resets', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->string('token')->unique();
            $table->timestamps();
        });

    }

};
