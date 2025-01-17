<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('cache', function (Blueprint $table) {
            $table->integer('expiration')->after('value');
        });
    }
    
    public function down()
    {
        Schema::table('cache', function (Blueprint $table) {
            $table->dropColumn('expiration');
        });
    }
    
};
