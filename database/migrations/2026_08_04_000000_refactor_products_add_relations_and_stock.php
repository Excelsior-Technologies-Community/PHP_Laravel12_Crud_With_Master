<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Replace legacy string columns with proper foreign key references
            $table->dropColumn(['categories', 'size']);

            // Foreign keys are added as indexed columns. Actual constraint
            // enforcement is intentionally omitted so the migration stays
            // compatible with SQLite (used in the default .env). Eloquent
            // relationships enforce referential integrity at the app level.
            $table->foreignId('category_id')->nullable()->after('id');
            $table->foreignId('size_id')->nullable()->after('category_id');

            // Stock / inventory management
            $table->string('sku')->unique()->nullable()->after('size_id');
            $table->integer('stock_quantity')->default(0)->after('sku');
            $table->integer('min_stock')->default(0)->after('stock_quantity');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['category_id']);
            $table->dropIndex(['size_id']);
            $table->dropColumn(['category_id', 'size_id', 'sku', 'stock_quantity', 'min_stock']);

            $table->string('categories')->after('details');
            $table->string('size')->after('categories');
        });
    }
};
