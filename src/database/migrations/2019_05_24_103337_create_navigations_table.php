<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateNavigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navigations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedInteger('depth')->default(1);
            $table->timestamps();
        });

        Schema::create('navigation_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('navigation_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name');
            $table->string('url');
            $table->string('target')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('draggable')->default(true);
            $table->boolean('editable')->default(false);
            $table->timestamps();

            $table->foreign('navigation_id')->references('id')->on('navigations')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('navigation_items')->onDelete('cascade');
        });

        // Create first navigation.
        DB::table('navigations')->insert([
            'name' => 'Navigation', 
            'depth' => 2, 
            'created_at' => now()
        ]);
        
        // Create first navigation item.
        DB::table('navigation_items')->insert([
            'navigation_id' => 1, 
            'name' => 'Example item', 
            'url' => 'https://voicecode.nl',
            'created_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('navigation_items');
        Schema::dropIfExists('navigations');
    }
}
