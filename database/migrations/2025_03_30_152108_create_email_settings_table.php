<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('email_settings', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->text('secret_key');
            $table->string('domain');
            $table->enum('region', ['US', 'EU', 'Asia', 'AU'])->default('US');
            $table->string('host');
            $table->integer('port');
            $table->enum('encryption', ['ssl', 'tls', 'none'])->default('tls');
            $table->string('username');
            $table->text('password');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_settings');
    }
};
