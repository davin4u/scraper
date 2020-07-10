<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTablePermissionsField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('api_token')->after('remember_token')->nullable();
            $table->json('permissions')->after('api_token')->nullable();
            $table->integer('max_api_requests')->default(60)->after('permissions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('api_token');
            $table->dropColumn('permissions');
            $table->dropColumn('max_api_requests');
        });
    }
}
