<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('module_panel', static function (Blueprint $table) {
            $table->id();

            $table->foreignId('panel_id')->constrained();
            $table->foreignId('module_id')->constrained();

            $table->timestamps();
        });
    }
};
