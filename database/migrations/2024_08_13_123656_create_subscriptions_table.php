<?php

use App\Base\Subscription\SubscriptionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('domain')->unique();
            $table->foreign('domain')->references('domain')->on('users')->cascadeOnDelete();
            $table->string('status')->default(SubscriptionStatus::ACTIVE);
            $table->tinyInteger('is_trial')->default(false);
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
