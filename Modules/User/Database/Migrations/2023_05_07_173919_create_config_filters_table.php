<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_filters', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->unsignedBigInteger('customer_id');
            $table->string('type')
                ->default('categories')
                ->comment('types: categories, sources and authors')
                ->index();
            $table->string('value');
            $table->timestamps();

            $table->foreign('customer_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('config_filters', function (Blueprint $table) {
            $table->dropUnique('config_filters_uuid_unique');
            $table->dropIndex('config_filters_type_index');

            $table->dropForeign(['customer_id']);
        });

        Schema::dropIfExists('config_filters');
    }
};
