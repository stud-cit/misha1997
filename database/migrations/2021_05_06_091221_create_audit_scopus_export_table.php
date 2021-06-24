<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditScopusExportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_scopus_export', function (Blueprint $table) {
            $table->id();
            $table->integer('last_number'); // остання сторінка
            $table->integer('total_count'); // кількість публікацій
            $table->integer('count'); // кількість збережених публікацій
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
        Schema::dropIfExists('audit_scopus_export');
    }
}
