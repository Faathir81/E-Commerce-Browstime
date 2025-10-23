# Data Dictionary (Ringkas)

## categories
- **id** (PK, BIGINT)
- **name** (VARCHAR100)
- **slug** (VARCHAR120, unique)

## products
- **id** (PK)
- **category_id** (FK → categories.id)
- **name** (VARCHAR120)
- **slug** (VARCHAR140, unique)
- **price** (INT, rupiah)
- **is_active** (BOOL)

## product_images
- **id** (PK)
- **product_id** (FK → products.id)
- **url** (VARCHAR255)
- **is_cover** (BOOL)

## materials
- **id** (PK)
- **name** (VARCHAR120)
- **unit** (VARCHAR20, contoh: gram, ml, pcs)

## material_stocks
- **id** (PK)
- **material_id** (FK → materials.id)
- **type** (ENUM: in/out/adjust)
- **qty** (DECIMAL(12,3))
- **note** (VARCHAR191)
- **created_at** (timestamp)

## product_recipes
- **id** (PK)
- **product_id** (FK → products.id)
- **material_id** (FK → materials.id)
- **qty_per_unit** (DECIMAL(12,3))

## orders
- **id** (PK)
- **user_id** (nullable FK → users.id; guest = NULL)
- **code** (VARCHAR32, unik)
- **status** (ENUM: pending/awaiting_payment/paid/processing/shipped/delivered/cancelled)
- **buyer_name**, **buyer_phone**
- **ship_address**, **ship_city**, **ship_postal**
- **shipping_cost** (INT), **subtotal** (INT), **grand_total** (INT)

## order_items
- **id** (PK)
- **order_id** (FK → orders.id)
- **product_id** (FK → products.id)
- **qty** (INT)
- **price_frozen** (INT, harga disalin saat checkout)

## payments
- **id** (PK)
- **order_id** (FK → orders.id)
- **method** (ENUM: transfer/qris/cash)
- **amount** (INT)
- **status** (ENUM: pending/verified/failed)
- **proof_url** (nullable)

## views
- **v_material_stock_current**: saldo qty_current per material.
- **v_product_makeable_qty**: qty yang bisa dibuat per product dari stok saat ini.
