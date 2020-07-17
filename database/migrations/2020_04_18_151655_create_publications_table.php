<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('science_type_id')->nullable();
            $table->string('type');
            $table->string('impact_factor');
            $table->integer('sub_db_index');
            $table->string('quartil_scopus')->nullable();
            $table->string('quartil_wos')->nullable();
            $table->string('scie')->nullable();
            $table->string('ssci')->nullable();
            $table->string('nature_index')->nullable();
            $table->string('nature_scince')->nullable();
            $table->string('other_organizations')->nullable();
            $table->string('forbes_fortune')->nullable();
            $table->date('date')->nullable();
            $table->string('international_patents')->nullable();
            $table->string('snip');
            $table->timestamps();
        });

        Schema::table('publications', function (Blueprint $table) {
            $table->index('science_type_id');
            $table->foreign('science_type_id')->references('id')->on('science_type')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('publications');
    }
}
