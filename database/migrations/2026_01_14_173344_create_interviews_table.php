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
        Schema::create('interviews', function (Blueprint $table) {
            $table->id(); // id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->foreignId('application_id')->constrained('applications')->onDelete('cascade'); // application_id FK
            $table->foreignId('recruiter_id')->constrained('users')->onDelete('cascade'); // recruiter_id FK
            $table->dateTime('date_time'); // DATETIME NOT NULL
            $table->enum('type', ['online', 'offline'])->default('offline'); // ENUM with default
            $table->enum('outcome', ['pending','pass','fail'])->default('pending'); // ENUM with default
            $table->string('link')->nullable(); // VARCHAR(255) nullable
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};
