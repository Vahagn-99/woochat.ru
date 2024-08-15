<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('amo_instances', function (Blueprint $table) {
            $table->id();

            $table->string('scope_id')->unique();
            $table->string('title')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->uuid('account_id')->unique();
            $table->foreign('account_id')
                ->references('amojo_id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amo_instances');
    }
};
