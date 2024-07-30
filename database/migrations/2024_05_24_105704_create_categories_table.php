<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up () {

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('company')->nullable();
            $table->string('phone')->nullable();
            $table->string('location')->nullable();
            $table->longText('description')->nullable();
            $table->string('notes')->nullable();
            $table->boolean('allow_products')->default(true);
            $table->boolean('allow_orders')->default(true);
            $table->boolean('allow_coupons')->default(true);
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

    }

};
