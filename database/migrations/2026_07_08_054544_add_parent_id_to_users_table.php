<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->unsignedBigInteger('parent_id')
                ->nullable()
                ->after('role_id');

            $table->foreign('parent_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');

        });
    }
};