<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_views', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_ip',100);
            $table->unsignedBigInteger('video_id');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null')
                ->onUpdate('cascade');

            $table->foreign('video_id')
                ->references('id')
                ->on('videos')
                ->onDelete('cascade')
                ->onUpdate('cascade');


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
        Schema::dropIfExists('video_views');
    }
}
