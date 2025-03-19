<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->index();
            $table->string('name')->index();
            $table->string('email')->unique();
            $table->string('phone')->nullable()->index();
            $table->string('city')->nullable()->index();;
            $table->char('state', 2)->nullable()->index();;
            $table->string('photo')->nullable();
            $table->integer('age')->nullable();
            $table->boolean('welcome_email_sent')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
