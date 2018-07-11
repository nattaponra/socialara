<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialLarasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_laras', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("user_id")->index();
            $table->string("provider")->index();
            $table->string("provider_id")->index();
            $table->string("email")->index();
            $table->text("avatar_path");
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_laras');
    }
}
