<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSfpColumnToPerangkatTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('perangkat', function (Blueprint $table) {
            $table->json('SFP')->nullable()->after('PRODUCT_ID_DEVICE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('perangkat', function (Blueprint $table) {
            $table->dropColumn('SFP');
        });
    }
}