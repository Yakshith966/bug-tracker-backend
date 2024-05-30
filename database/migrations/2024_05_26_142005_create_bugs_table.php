<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBugsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bugs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('tester_id');
            $table->timestamps();

            $table->foreign('tester_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('bug_developer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bug_id');
            $table->unsignedBigInteger('developer_id');
            $table->timestamps();

            $table->foreign('bug_id')->references('id')->on('bugs')->onDelete('cascade');
            $table->foreign('developer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bug_developer');
        Schema::dropIfExists('bugs');
    }
}
