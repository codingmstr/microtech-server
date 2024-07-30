<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up () {

        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('ip')->nullable();
            $table->string('agent')->nullable();
            $table->longText('content')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

    }

};
