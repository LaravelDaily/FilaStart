<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('panel_deployments', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('panel_id')->constrained()->cascadeOnDelete();
            $table->string('deployment_id');
            $table->string('status');
            $table->string('file_path')->nullable();
            $table->longText('deployment_log')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
