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
            $table->string('name');
            $table->string('email');
            $table->enum('driver', ['mailgun', 'smtp'])->default('mailgun');
            $table->string('secret_key')->nullable(); // Bisa kosong jika SMTP dipilih
            $table->string('domain')->nullable();
            $table->enum('region', ['US', 'EU', 'Asia', 'AU'])->nullable();
            $table->string('host')->nullable(); // Bisa kosong jika Mailgun dipilih
            $table->unsignedSmallInteger('port')->nullable();
            $table->enum('encryption', ['ssl', 'tls', 'none'])->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
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
