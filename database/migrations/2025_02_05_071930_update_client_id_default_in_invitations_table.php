<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->unsignedBigInteger('client_id')->nullable()->default(null)->change();
        });
    }

    public function down()
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->unsignedBigInteger('client_id')->nullable(false)->change(); // Reverting change
        });
    }
};
