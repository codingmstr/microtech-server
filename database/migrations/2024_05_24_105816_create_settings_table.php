<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up () {

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('location')->nullable();
            $table->string('language')->nullable();
            $table->string('theme')->nullable();
            $table->string('facebook')->nullable();
            $table->string('youtube')->nullable();
            $table->string('instagram')->nullable();
            $table->string('telegram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('linkedin')->nullable();
            $table->float('balance')->default(0);
            $table->float('profit')->default(0);
            $table->float('income')->default(0);
            $table->float('expenses')->default(0);
            $table->float('withdraws')->default(0);
            $table->float('deposits')->default(0);
            $table->boolean('allow_mails')->default(true);
            $table->boolean('allow_chats')->default(true);
            $table->boolean('allow_registerations')->default(true);
            $table->boolean('allow_logins')->default(true);
            $table->boolean('allow_products')->default(true);
            $table->boolean('allow_coupons')->default(true);
            $table->boolean('allow_orders')->default(true);
            $table->boolean('allow_blogs')->default(true);
            $table->boolean('allow_contacts')->default(true);
            $table->boolean('allow_emails')->default(true);
            $table->boolean('allow_notifications')->default(true);
            $table->boolean('running')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

    }

};
