<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up () {

        Schema::create('transactions', function ( Blueprint $table ) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->string('transaction_id')->nullable();
            $table->string('type')->nullable();
            $table->string('payment')->nullable();
            $table->string('method')->nullable();
            $table->string('currency')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('description')->nullable();
            $table->string('ip')->nullable();
            $table->string('agent')->nullable();
            $table->enum('status', ['pending', 'request', 'confirmed', 'cancelled']);
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

    }

};
