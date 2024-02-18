$table->string('title');
            $table->string('slug');
            $table->string('description')->nullable;
            $table->double('price',10,2);
            $table->double('compare_price',10,2)->nullable;
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('sub_category_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('brand_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('is_featured',['Yes','No'])->default('No');
            $table->string('sku');
            $table->string('barcode')->nullable();
            $table->enum('track_qty',['Yes','No'])->default('Yes');
            $table->integer('qty')->nullable();
            $table->integer('status')->default(1);



            
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('image');
            $table->integer('sort_order')->nullable();