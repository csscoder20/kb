<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('basics', function (Blueprint $table) {
            // Drop kolom lama
            $table->dropColumn(['banner', 'banner_description', 'homepage', 'color', 'is_darkmode_active']);

            // Tambahkan kolom baru
            $table->string('alert')->after('description');
            $table->string('text_available')->after('alert');
            $table->string('text_unavailable')->after('text_available');
            $table->string('pdf_unavailable')->after('text_unavailable');
            $table->string('dark_color', 7)->default('#ecf0f6')->after('pdf_unavailable');
            $table->string('light_color', 7)->default('#020617')->after('dark_color');

            // Rename logo jadi logo utama, dan tambahkan logo_light dan logo_dark
            $table->string('logo_dark')->nullable()->after('logo');
            $table->string('logo_light')->nullable()->after('logo_dark');

            // Footer
            $table->string('footer')->after('favicon');
        });
    }

    public function down(): void
    {
        Schema::table('basics', function (Blueprint $table) {
            // Kembalikan ke kondisi sebelumnya
            $table->dropColumn([
                'alert',
                'text_available',
                'text_unavailable',
                'pdf_unavailable',
                'dark_color',
                'light_color',
                'logo_dark',
                'logo_light',
                'footer'
            ]);

            $table->enum('homepage', ['all_discussions', 'tags'])->default('tags');
            $table->string('color', 7)->default('#ecf0f6');
            $table->enum('is_darkmode_active', ['yes', 'no'])->default('no');
            $table->string('banner');
            $table->text('banner_description');
        });
    }
};
