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
        Schema::create('expeditions', function (Blueprint $table) {
            $table->id();

            // Column 1: Sequential number (resets annually)
            $table->integer('sequential_number');
            $table->year('year');

            // Column 2: Document number and date
            $table->string('document_number');
            $table->date('document_date');

            // Column 3: Recipient name/position
            $table->string('recipient_name');

            // Column 4: Document subject
            $table->text('subject');

            // Column 5: Number of attachments
            $table->integer('attachments_count')->default(0);

            // Column 6: Date and time received/sent
            $table->datetime('received_at');

            // Column 7: Recipient signature and clear name
            $table->longText('recipient_signature')->nullable();
            $table->string('recipient_name_clear');

            // Column 8: Classification and notes
            $table->enum('classification', ['Biasa', 'Rahasia', 'Lainnya'])->default('Biasa');
            $table->text('notes')->nullable();

            $table->timestamps();

            // Indexes for better performance
            $table->index(['year', 'sequential_number']);
            $table->index('document_date');
            $table->index('classification');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expeditions');
    }
};
