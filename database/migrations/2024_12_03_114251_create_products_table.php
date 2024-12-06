<?php

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::disableForeignKeyConstraints();

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Store::class);
            $table->string('name');
            $table->text('description');
            $table->double('price');
            $table->unsignedInteger('stock');
            $table->string('image');
            $table->foreignIdFor(Category::class);                        
            $table->timestamps();
        });
        
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class);
            $table->foreignIdFor(Customer::class);                     
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('favorites');
    }
};
