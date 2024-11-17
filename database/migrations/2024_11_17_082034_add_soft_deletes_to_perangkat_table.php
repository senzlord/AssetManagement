<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeletesToPerangkatTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('perangkat', function (Blueprint $table) {
            $table->softDeletes(); // Add the deleted_at column for soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('perangkat', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Remove the deleted_at column on rollback
        });
    }
}