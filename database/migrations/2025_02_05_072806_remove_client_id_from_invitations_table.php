<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('invitations', function (Blueprint $table) {
            // First, drop the foreign key constraint
            $table->dropForeign(['client_id']);

            // Then, drop the column
            $table->dropColumn('client_id');
        });
    }

    public function down()
    {
        Schema::table('invitations', function (Blueprint $table) {
            // Re-add the column if needed in rollback
            $table->unsignedBigInteger('client_id')->nullable();

            // Re-add the foreign key constraint
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }
};
