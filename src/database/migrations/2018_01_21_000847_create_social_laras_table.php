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
            $table->string("platform")->index();
            $table->string("platform_id")->index();
            $table->string("email")->index();
            $table->string("token")->index();
            $table->string("avatar_extension");
            $table->timestamps();
        });
        DB::statement("ALTER TABLE social_laras ADD avatar_content LONGBLOB NULL");
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
