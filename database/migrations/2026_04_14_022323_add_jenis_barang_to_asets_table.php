<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('asets', function (Blueprint $table) {
            $table->string('jenis_barang')->nullable()->after('nama_barang');
        });
    }

    public function down()
    {
        Schema::table('asets', function (Blueprint $table) {
            $table->dropColumn('jenis_barang');
        });
    }
};