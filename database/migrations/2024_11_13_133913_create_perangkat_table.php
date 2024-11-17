<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerangkatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perangkat', function (Blueprint $table) {
            $table->id('PERANGKAT_ID');
            $table->string('HOST_NAME', 100)->unique();
            $table->string('TYPE', 100)->nullable();
            $table->string('SERIAL_NUMBER', 100)->nullable();
            $table->string('IP_ADDRESS', 100)->nullable();
            $table->string('LOCATION', 100)->nullable();
            $table->dateTime('LICENCE_END_DATE')->nullable();
            $table->string('PRODUCT_ID_DEVICE', 100)->nullable();
            $table->integer('JUMLAH_SFP_DICABUT')->nullable();
            $table->integer('STOCK')->nullable();
            $table->string('CATEGORY', 100)->nullable();
            $table->string('VENDOR', 100)->nullable();
            $table->string('TANGGAL_CABUT_SFP', 100)->nullable();
            $table->string('BRAND', 100)->nullable();
            $table->dateTime('EOS_HARDWARE')->nullable();
            $table->string('FIRMWARE', 100)->nullable();
            $table->string('OS_VERSION', 100)->nullable();
            $table->dateTime('EOS_FIRMWARE')->nullable();
            $table->string('USER', 100)->nullable();
            $table->string('NAMA_KONTRAK', 100)->nullable();
            $table->string('NO_KONTRAK', 100)->nullable();
            $table->string('STATUS_SUPPORT')->nullable();
            $table->dateTime('ATS_END_DATE')->nullable();
            $table->string('PIC', 100)->nullable();
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('perangkat');
    }
}
