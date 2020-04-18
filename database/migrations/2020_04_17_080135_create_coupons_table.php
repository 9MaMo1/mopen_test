<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('coupon_id');
            $table->string('coupon_name');
            $table->string('coupon_link');
            $table->integer('coupon_amount');
            $table->integer('coupon_brand_id');
            $table->string('coupon_code', 32);
            $table->enum('coupon_type',['voucher_code','discounted']);
            $table->integer('coupon_value')->nullable();
            $table->integer('coupon_percent_off')->nullable();
            $table->timestamp('expires_at')->nullable(); 
            $table->boolean('is_used')->default(false); 
            $table->timestamps();
        });



        Schema::create('coupon_user', function (Blueprint $table) {
     
            $table->increments('id');
            $table->bigInteger('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->bigInteger('coupon_id')->unsigned()->index();
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('cascade');

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
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('user_coupon');

    }
}
