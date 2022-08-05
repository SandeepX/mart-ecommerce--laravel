<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryProductViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("
        CREATE OR REPLACE VIEW  products_under_category AS
              WITH RECURSIVE
                  products_under_category AS
                (
                  SELECT
                    p.product_code,
                    c.category_code,
                    c.upper_category_code AS category_parent
                  FROM
                    products_master p
                  INNER JOIN
                    category_master   c
                      ON c.category_code = p.category_code
                  UNION ALL
                  SELECT
                    r.product_code,
                    c.category_code,
                    c.upper_category_code
                  FROM
                    products_under_category  r
                  INNER JOIN
                    category_master c
                      ON c.category_code = r.category_parent
                )
                SELECT
                 product_code,
                 category_code
                FROM
                  products_under_category
                        ");
    }
}
