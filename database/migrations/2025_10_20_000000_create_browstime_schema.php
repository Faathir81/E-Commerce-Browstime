<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ========= MASTER DASAR =========
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('short_name', 10);
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 120)->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->foreignId('unit_id')->constrained('units')->restrictOnDelete();
            $table->decimal('min_stock', 15, 3)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // ========= KATALOG PRODUK =========
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('slug', 180)->unique();
            $table->string('short_label', 50)->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->integer('estimated_days')->default(1);
            $table->boolean('is_best_seller')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->text('url');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('product_categories', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->primary(['product_id', 'category_id']);
        });

        // ========= RESEP (bahan baku per kategori/produk) =========
        Schema::create('common_recipe', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('material_id')->constrained('materials')->restrictOnDelete();
            $table->decimal('qty_per_unit', 15, 3);
            $table->foreignId('unit_id')->constrained('units')->restrictOnDelete();
            $table->timestamps();
        });

        Schema::create('special_recipe', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('material_id')->constrained('materials')->restrictOnDelete();
            $table->decimal('qty_per_unit', 15, 3);
            $table->foreignId('unit_id')->constrained('units')->restrictOnDelete();
            $table->timestamps();

            $table->unique(['product_id','material_id']);
        });

        Schema::create('product_recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('material_id')->constrained('materials')->restrictOnDelete();
            $table->decimal('qty_per_unit', 15, 3);
            $table->foreignId('unit_id')->constrained('units')->restrictOnDelete();
            $table->timestamps();
        });

        // ========= PENGIRIMAN & PEMESANAN =========
        Schema::create('shipping_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->decimal('base_fee', 12, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 100);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('order_sequences', function (Blueprint $table) {
            $table->date('seq_date')->primary();
            $table->integer('last_seq')->default(0);
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['pending','paid','processing','ready','shipped','completed','cancelled','expired'])->default('pending');
            $table->string('order_code', 30)->unique();

            $table->string('customer_name', 150);
            $table->string('customer_email', 150);
            $table->string('customer_phone', 50);

            $table->foreignId('shipping_zone_id')->nullable()->constrained('shipping_zones')->nullOnDelete();
            $table->text('shipping_address');
            $table->text('shipping_notes')->nullable();

            $table->decimal('delivery_fee', 12, 2)->default(0);
            $table->decimal('subtotal_amount', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);

            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->nullOnDelete();

            $table->dateTime('placed_at')->useCurrent();
            $table->dateTime('paid_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('cancelled_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->string('product_name', 150);
            $table->decimal('unit_price', 12, 2);
            $table->integer('quantity');
            $table->decimal('line_subtotal', 12, 2);
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('method_id')->nullable()->constrained('payment_methods')->nullOnDelete();
            $table->string('provider', 50)->nullable();
            $table->string('provider_ref', 120)->nullable();
            $table->string('va_number', 50)->nullable();
            $table->decimal('gross_amount', 12, 2);
            $table->enum('status', ['pending','authorized','settlement','failed','expired','cancelled','refunded'])->default('pending');
            $table->dateTime('paid_at')->nullable();
            $table->json('raw_response')->nullable();
            $table->string('signature_key', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('payment_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->dateTime('received_at')->useCurrent();
            $table->json('headers')->nullable();
            $table->json('payload')->nullable();
            $table->string('signature_key', 255)->nullable();
        });

        // ========= GUDANG BAHAN & PERGERAKAN =========
        Schema::create('material_lots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained('materials')->cascadeOnDelete();
            $table->decimal('qty_initial', 15, 3);
            $table->decimal('qty_remaining', 15, 3);
            $table->foreignId('unit_id')->constrained('units')->restrictOnDelete();
            $table->date('received_at')->useCurrent();
            $table->date('expires_at')->nullable();
            $table->decimal('cost_per_unit', 14, 4)->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('material_stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained('materials')->cascadeOnDelete();
            $table->foreignId('lot_id')->nullable()->constrained('material_lots')->nullOnDelete();
            $table->enum('movement_type', [
                'purchase_in','use_for_order','waste_expired','waste_damaged','adjustment_plus','adjustment_min'
            ]);
            $table->decimal('qty', 15, 3);
            $table->foreignId('unit_id')->constrained('units')->restrictOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->text('note')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // ========= ULASAN PRODUK =========
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('customer_name', 150)->nullable();
            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });

        // ========= TRIGGER (generate order_code harian) =========
        DB::unprepared('DROP TRIGGER IF EXISTS trg_orders_set_code;');
        DB::unprepared(<<<SQL
CREATE TRIGGER trg_orders_set_code
BEFORE INSERT ON orders
FOR EACH ROW
BEGIN
    IF NEW.order_code IS NULL OR NEW.order_code = '' THEN
        INSERT INTO order_sequences (seq_date, last_seq)
        VALUES (CURDATE(), 1)
        ON DUPLICATE KEY UPDATE last_seq = last_seq + 1;

        SET @seq := (SELECT last_seq FROM order_sequences WHERE seq_date = CURDATE());
        SET NEW.order_code = CONCAT('BRW-', DATE_FORMAT(NOW(), '%Y%m%d'), '-', LPAD(@seq, 4, '0'));
    END IF;
END;
SQL);

        // ========= VIEWS (dibuat paling akhir) =========
        DB::unprepared(<<<SQL
CREATE OR REPLACE VIEW v_material_stock_current AS
SELECT 
    m.id AS material_id,
    m.name AS material_name,
    CAST(IFNULL(SUM(
        CASE msm.movement_type
            WHEN 'purchase_in' THEN msm.qty
            WHEN 'adjustment_plus' THEN msm.qty
            WHEN 'use_for_order' THEN -msm.qty
            WHEN 'waste_expired' THEN -msm.qty
            WHEN 'waste_damaged' THEN -msm.qty
            WHEN 'adjustment_min' THEN -msm.qty
            ELSE 0 END
    ),0) AS DECIMAL(15,3)) AS qty_current
FROM materials m
LEFT JOIN material_stock_movements msm ON msm.material_id = m.id
GROUP BY m.id, m.name
ORDER BY m.name ASC;
SQL);

        DB::unprepared(<<<SQL
CREATE OR REPLACE VIEW v_effective_recipe AS
SELECT sr.product_id, sr.material_id, sr.qty_per_unit, sr.unit_id
FROM special_recipe sr
UNION ALL
SELECT p.id AS product_id, cr.material_id, cr.qty_per_unit, cr.unit_id
FROM products p
JOIN product_categories pc ON pc.product_id = p.id
JOIN common_recipe cr ON cr.category_id = pc.category_id
LEFT JOIN special_recipe sr2 
    ON sr2.product_id = p.id AND sr2.material_id = cr.material_id
WHERE sr2.id IS NULL;
SQL);

        DB::unprepared(<<<SQL
CREATE OR REPLACE VIEW v_product_makeable_qty AS
SELECT 
    p.id AS product_id,
    p.name AS product_name,
    CAST(IFNULL(MIN(FLOOR((IFNULL(vms.qty_current,0) / er.qty_per_unit))),0) AS SIGNED) AS makeable_qty
FROM products p
JOIN v_effective_recipe er ON er.product_id = p.id
LEFT JOIN v_material_stock_current vms ON vms.material_id = er.material_id
GROUP BY p.id, p.name;
SQL);

        DB::unprepared(<<<SQL
CREATE OR REPLACE VIEW v_sales_daily AS
SELECT 
    CAST(o.placed_at AS DATE) AS order_date,
    SUM(CASE WHEN o.status IN ('paid','processing','ready','shipped','completed') THEN 1 ELSE 0 END) AS paid_orders,
    SUM(CASE WHEN o.status IN ('paid','processing','ready','shipped','completed') THEN o.total_amount ELSE 0 END) AS gross_revenue
FROM orders o
GROUP BY CAST(o.placed_at AS DATE)
ORDER BY order_date ASC;
SQL);
    }

    public function down(): void
    {
        // Drop views & trigger dulu
        DB::unprepared('DROP VIEW IF EXISTS v_sales_daily;');
        DB::unprepared('DROP VIEW IF EXISTS v_product_makeable_qty;');
        DB::unprepared('DROP VIEW IF EXISTS v_effective_recipe;');
        DB::unprepared('DROP VIEW IF EXISTS v_material_stock_current;');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_orders_set_code;');

        // Drop tabel (reverse urutan FK)
        Schema::dropIfExists('product_reviews');
        Schema::dropIfExists('material_stock_movements');
        Schema::dropIfExists('material_lots');
        Schema::dropIfExists('payment_notifications');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('shipping_zones');
        Schema::dropIfExists('product_recipes');
        Schema::dropIfExists('special_recipe');
        Schema::dropIfExists('common_recipe');
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('units');
        Schema::dropIfExists('order_sequences');
    }
};
