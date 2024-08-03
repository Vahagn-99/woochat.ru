<?php

use App\Enums\InstanceStatus;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('instances', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('token')->unique();
            $table->string('name')->nullable();
            $table->string('phone')->nullable()->unique();
            $table->string('status')->default(InstanceStatus::NOT_AUTHORIZED);
            $table->timestamp('created_at')->useCurrent();

            $table->foreignIdFor(User::class, 'user_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instances');
    }
};
