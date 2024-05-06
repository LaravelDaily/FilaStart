<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cruds', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('panel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('cruds');
            $table->string('type');
            $table->string('title');
            $table->string('visual_title');
            $table->string('icon')->nullable();
            $table->integer('menu_order');
            $table->boolean('is_hidden')->default(false);
            $table->boolean('module_crud')->default(false);
            $table->string('module_slug')->nullable();
            $table->integer('module_order')->nullable();
            $table->boolean('system')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
