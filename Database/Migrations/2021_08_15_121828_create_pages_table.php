<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->string('slug');
            $table->string('view');
            $table->boolean('active')->default(true);
            $table->mediumInteger('order')->unsigned()->default(0);
            $table->json('content')->nullable();
            $table->foreignId('page_meta_id')->constrained();
            $table->timestamps();
            $table->softDeletes();

            $table->unique('slug');
            $table->index('page_meta_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
