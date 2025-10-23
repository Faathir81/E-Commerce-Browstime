# Traceability — Kebutuhan ↔ DB ↔ Endpoint ↔ UI

| Kebutuhan | Tabel/View | Endpoint (contoh) | UI |
|---|---|---|---|
| Browse katalog | products, product_images, categories | GET /products?category= | CatalogPage |
| Lihat detail & makeable qty | v_product_makeable_qty, product_images | GET /products/{id}, GET /products/{id}/makeable-qty | ProductDetail |
| Add to cart | — (session) | POST /cart/items | Catalog/ProductDetail |
| Checkout (guest/login) | orders, order_items | POST /orders | CheckoutPage |
| Instruksi pembayaran | orders, payments | GET /orders/{id} | OrderDetail |
| Upload/rekam pembayaran | payments | POST /payments | OrderDetail/Admin Order |
| Verifikasi pembayaran | payments, orders | POST /payments/{id}/verify | Admin Order |
| Kurangi stok saat paid | material_stocks, product_recipes | (otomatis saat verify) | — |
| Kelola bahan baku | materials, material_stocks | CRUD /materials, /material-stocks | Admin Materials |
| Kelola resep | product_recipes | CRUD /product-recipes | Admin Recipes |
| Kelola produk | products, product_images, categories | CRUD /products, /categories, /product-images | Admin Catalog |
| Laporan harian | orders, payments | GET /reports/sales-daily | Admin Dashboard |
| Ringkas stok | v_material_stock_current | GET /reports/stock-current | Admin Dashboard |
