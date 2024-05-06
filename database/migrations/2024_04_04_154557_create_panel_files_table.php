<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('panel_files', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('panel_id')->constrained();
            $table->foreignId('crud_id')->nullable()->constrained();
            $table->foreignId('crud_field_id')->nullable()->constrained();

            $table->text('path');

            $table->timestamps();
            $table->softDeletes();
        });
    }
};
