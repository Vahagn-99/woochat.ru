<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('amo_connections', function (Blueprint $table) {
            $table->id();
            $table->uuid('account_id')->unique();
            $table->string('scope_id')->unique();
            $table->string('title')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreignIdFor(User::class, 'user_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amo_connections');
    }
};
