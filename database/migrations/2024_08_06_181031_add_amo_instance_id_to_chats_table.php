<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up(): void
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->foreignId('amo_chat_instance_id')
                ->nullable()
                ->references('id')
                ->on('amo_instances')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->dropForeign('chats_amo_chat_instance_id_foreign');
        });
    }
};
