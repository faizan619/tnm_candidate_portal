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
        Schema::table('candidate_users', function (Blueprint $table) {
            $table->string('mobile')->unique()->after('email');
        });
    }

    public function down()
    {
        Schema::table('candidate_users', function (Blueprint $table) {
            $table->dropColumn('mobile');
        });
    }
};
