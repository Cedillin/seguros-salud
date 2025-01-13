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
        Schema::create('leads', function (Blueprint $table) {

            $table->ulid('id')->primary();
            $table->ulid('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
            $table->string('phone');
            $table->date('main_insured_birth_date');
            $table->boolean('has_copay');
            $table->json('additional_insured')->nullable();
            $table->decimal('calculated_price', 10, 2);
            $table->decimal('final_price', 10, 2)->nullable();
            $table->string('payment_frequency')->nullable();
            $table->enum('status', ['pending', 'in_process', 'completed', 'rejected'])
                ->default('pending');
            $table->boolean('is_smoker')->nullable();
            $table->string('payment_method')->nullable();
            $table->json('payment_details')->nullable();
            $table->boolean('payment_completed')->default(false);
            $table->boolean('document_signed')->default(false);
            $table->timestamp('signed_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate();
            $table->softDeletes();
            $table->index(['user_id', 'status']);
            $table->index(['phone', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
