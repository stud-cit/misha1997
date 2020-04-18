<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->integer('number');
            $table->string('country');
            $table->string('applicant');
            $table->date('date_application');
            $table->date('date_publication');
            $table->boolean('commerc_university');
            $table->boolean('commerc_employees');
            $table->foreignId('publications_id');
            $table->timestamps();
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->index('publications_id');
            $table->foreign('publications_id')->references('id')->on('publications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('certificates');
    }
}
