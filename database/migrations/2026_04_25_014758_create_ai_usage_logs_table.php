<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_usage_logs', function (Blueprint $table) {
            $table->id();
            $table->string('provider'); 
            $table->string('feature');
            $table->integer('tokens_used')->default(0);
            $table->decimal('cost_estimate', 10, 6)->default(0);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
            
            $table->index(['provider', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_usage_logs');
    }
};