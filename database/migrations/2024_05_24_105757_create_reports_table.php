<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up () {

        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id')->default(0);
            $table->integer('vendor_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->string('action_table')->nullable();
            $table->integer('action_column')->default(0);
            $table->string('process')->nullable();
            $table->string('ip')->nullable();
            $table->string('agent')->nullable();
            $table->string('location')->nullable();
            $table->float('price')->default(0);
            $table->float('amount')->default(0);
            $table->boolean('paid')->default(false);
            $table->enum('status', ['pending', 'request', 'confirmed', 'cancelled']);
            $table->boolean('active');
            $table->timestamps();
            $table->softDeletes();
        });

    }

};
