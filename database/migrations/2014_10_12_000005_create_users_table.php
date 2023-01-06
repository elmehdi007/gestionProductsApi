<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Query\Expression;
use App\Helpers\Enum\TripType;
use App\Helpers\Enum\CustumDateFormat;
use App\Helpers\Enum\CustumTimeFormat;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        
        Schema::create('users', function (Blueprint $table) {
            $table->string('id')->primary();
            //$table->string('id')->primary()->default(new Expression('SHA2(CONCAT( UUID(), CURRENT_TIMESTAMP()), 512)'));
            //$table->id();                        
            $table->string('role_id')->nullable();
            $table->string('lname');
            $table->string('fname');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('password',250);
            $table->string('photo')->nullable();
            //$table->rememberToken();
            $table->dateTime('created_at')->default(new Expression('CURRENT_TIMESTAMP()'));
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->foreign('role_id')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //email,password,nom prenom, date naissance, date register, nombre point,idLang,idmemberchip
        Schema::dropIfExists('users');
    }
}
