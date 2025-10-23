<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        DB::unprepared(<<<'SQL'
CREATE OR REPLACE VIEW v_material_stock_current AS
SELECT
  m.id AS material_id,
  m.name,
  m.unit,
  COALESCE(SUM(
    CASE
      WHEN ms.type = 'in'     THEN ms.qty
      WHEN ms.type = 'adjust' THEN ms.qty
      WHEN ms.type = 'out'    THEN -ms.qty
    END
  ), 0) AS qty_current
FROM materials m
LEFT JOIN material_stocks ms ON ms.material_id = m.id
GROUP BY m.id, m.name, m.unit;
SQL);

        DB::unprepared(<<<'SQL'
CREATE OR REPLACE VIEW v_product_makeable_qty AS
SELECT
  pr.product_id,
  MIN(FLOOR(v.qty_current / NULLIF(pr.qty_per_unit, 0))) AS makeable_qty
FROM product_recipes pr
JOIN v_material_stock_current v ON v.material_id = pr.material_id
GROUP BY pr.product_id;
SQL);
    }

    public function down(): void {
        DB::unprepared('DROP VIEW IF EXISTS v_product_makeable_qty;');
        DB::unprepared('DROP VIEW IF EXISTS v_material_stock_current;');
    }
};
