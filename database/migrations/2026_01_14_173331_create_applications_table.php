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
        Schema::create('applications', function (Blueprint $table) {
           $table->id(); // id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->foreignId('job_id')->constrained('works')->onDelete('cascade'); // job_id FK
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // user_id FK
            $table->string('cv_path'); // VARCHAR(255) NOT NULL
            $table->enum('status', ['pending', 'shortlisted', 'rejected'])->default('pending'); 
            $table->integer('score')->default(0); // INT DEFAULT 0
            $table->timestamps(); // created_at & updated_at with CURRENT_TIMESTAMP behavior
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
