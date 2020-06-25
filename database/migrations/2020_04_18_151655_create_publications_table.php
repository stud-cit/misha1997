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
            $table->foreignId('science_type_id');
            $table->string('type');
            $table->string('impact_factor');
            $table->integer('sub_db_index');
            $table->string('quartil');
            $table->integer('department_id');
            $table->string('scie');
            $table->string('ssci');
            $table->string('nature_index');
            $table->string('nature_scince');
            $table->string('other_organizations');
            $table->string('forbes_fortune');
            $table->date('date');
            $table->string('international_patents');
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
