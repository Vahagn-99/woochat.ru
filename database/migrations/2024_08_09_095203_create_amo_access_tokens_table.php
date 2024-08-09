<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('amo_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('domain')->unique();
            $table->foreign('domain')->references('domain')->on('users')->cascadeOnDelete();
            $table->text('access_token');
            $table->text('refresh_token');
            $table->string('expires');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amo_access_tokens');
    }
};
