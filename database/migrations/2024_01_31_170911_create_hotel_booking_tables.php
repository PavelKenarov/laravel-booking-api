<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \App\Models\Room;
use \App\Models\Payment;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('room', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('number', false, true);
            $table->string('name');
            $table->double('price');
            $table->enum('type', [Room::TYPE_ONE, Room::TYPE_STUDIO, Room::TYPE_TWO])->default(Room::TYPE_ONE );
            $table->timestamps();
        });

        Schema::create('customer', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->timestamps();
        });

        Schema::create('booking', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customer_id');
            $table->index('customer_id', 'customer_id_index');
            $table->unsignedBigInteger('room_id');
            $table->index('room_id', 'room_id_index');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->double('price');
            $table->timestamps();

            // Create a unique index on the combination of customer_id and room_id
            $table->unique(['customer_id', 'room_id']);
        });

        Schema::create('payment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('booking_id');
            $table->index('booking_id', 'booking_id_index');
            $table->dateTime('payment_date');
            $table->double('amount');
            $table->enum('status', [Payment::STATUS_SUCCEED, Payment::STATUS_PENDING, Payment::STATUS_FAILED])->default(Payment::STATUS_SUCCEED );
            $table->timestamps();
        });

        Schema::table('booking', function (Blueprint $table) {
            $table->foreign('customer_id', 'booking_customer_foreign')->references('id')->on('customer')->onDelete('cascade');
            $table->foreign('room_id', 'bookings_room_foreign')->references('id')->on('room')->onDelete('cascade');
        });

        Schema::table('payment', function (Blueprint $table) {
            $table->foreign('booking_id', 'payment_booking_foreign')->references('id')->on('booking')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            $table->dropUnique('booking_customer_id_room_id_unique');
            $table->dropForeign('booking_customer_foreign');
            $table->dropForeign('bookings_room_foreign');
        });

        Schema::table('payment', function (Blueprint $table) {
            $table->dropForeign('payment_booking_foreign');
        });

        Schema::dropIfExists('room');
        Schema::dropIfExists('customer');
        Schema::dropIfExists('booking');
        Schema::dropIfExists('payment');
    }
};
