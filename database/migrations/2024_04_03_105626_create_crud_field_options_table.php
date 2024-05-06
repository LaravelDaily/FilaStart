<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crud_field_options', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('crud_field_id')->constrained('crud_fields')->cascadeOnDelete();
            $table->foreignId('crud_id')->constrained('cruds')->cascadeOnDelete();
            $table->foreignId('related_crud_field_id')->constrained('crud_fields')->cascadeOnDelete();
            $table->string('relationship')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
