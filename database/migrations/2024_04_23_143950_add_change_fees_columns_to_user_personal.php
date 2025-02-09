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
        Schema::table('user_personal', function (Blueprint $table) {
            $table->double('new_fees')->nullable();
            $table->boolean('new_fees_confirmed')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_personal', function (Blueprint $table) {
            $table->dropColumn('new_fees');
            $table->dropColumn('new_fees_confirmed');
        });
    }
};
