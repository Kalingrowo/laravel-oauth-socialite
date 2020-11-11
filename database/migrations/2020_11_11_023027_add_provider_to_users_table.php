<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProviderToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')
                ->nullable()
                ->change();
            $table->string('password')
                ->nullable()
                ->change();
            $table->string('provider')
                ->nullable()
                ->after('remember_token')
                ->comment('provider name for OAuth like facebook, google, twitter, etc');
            $table->string('provider_id')
                ->nullable()
                ->after('provider')
                ->comment('provider id generate from selected provider');
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
            $table->string('email')
                ->change();
            $table->string('password')
                ->change();
            $table->dropColumn('provider');
            $table->dropColumn('provider_id');
        });
    }
}
