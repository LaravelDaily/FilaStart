<?php

use App\Enums\CrudFieldValidation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crud_fields', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('panel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('crud_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->string('key');
            $table->string('label');
            $table->string('validation')->default(CrudFieldValidation::NULLABLE->value);
            $table->boolean('in_list')->default(true);
            $table->boolean('in_show')->default(true);
            $table->boolean('in_create')->default(false);
            $table->boolean('in_edit')->default(false);
            $table->boolean('nullable');
            $table->string('tooltip')->nullable();
            $table->boolean('system')->default(false);
            $table->boolean('enabled')->default(true);
            $table->integer('order');
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
