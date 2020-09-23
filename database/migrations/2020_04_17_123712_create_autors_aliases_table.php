<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutorsAliasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autors_aliases', function (Blueprint $table) {
            $table->id();
            $table->string('surname_initials');
            $table->foreignId('autors_id');
            $table->boolean('select')->default(0);
            $table->timestamps();
        });

        Schema::table('autors_aliases', function (Blueprint $table) {
            $table->index('autors_id');
            $table->foreign('autors_id')->references('id')->on('authors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('autors_aliases');
    }
}
