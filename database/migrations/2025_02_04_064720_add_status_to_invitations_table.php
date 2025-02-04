<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invitations', function (Blueprint $table) {
            // Add the 'status' column with enum type ('pending', 'accepted', 'declined') and allow it to be nullable
            $table->enum('status', ['pending', 'accepted', 'declined'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invitations', function (Blueprint $table) {
            // Drop the 'status' column if rolling back
            $table->dropColumn('status');
        });
    }
};
