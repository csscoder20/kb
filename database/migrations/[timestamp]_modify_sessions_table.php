<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sessions', function (Blueprint $table) {
            // Ubah tipe data last_activity menjadi BIGINT
            $table->bigInteger('last_activity')->change();
        });
    }

    public function down()
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->integer('last_activity')->change();
        });
    }
};
