<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up () {

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->integer('product_id')->default(0);
            $table->integer('coupon_id')->default(0);
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('notes')->nullable();
            $table->string('secret_key')->unique();
            $table->float('price')->default(0);
            $table->float('coupon_discount')->default(0);
            $table->string('coupon_code')->nullable();
            $table->boolean('paid')->default(false);
            $table->enum('status', ['pending', 'request', 'confirmed', 'cancelled']);
            $table->boolean('active')->default(true);
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('ordered_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

    }

};