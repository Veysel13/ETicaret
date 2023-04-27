<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductDetail;
use Faker\Generator;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Generator $faker)
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Product::truncate();
        ProductDetail::truncate();

        for ($i=0;$i<30;$i++){
            $urun_adi=$faker->sentence(2);

            $product=Product::create(array(
                'restaurant_id'=>1,
                "name"=>$urun_adi,
                "slug"=>\Str::slug($urun_adi),
                "description"=>$faker->sentence(20),
                "price"=>$faker->randomFloat(3,1,20)
            ));

            $product->detail()->create(array(
                "is_slider"=>rand(0,1),
                "is_opportunity"=>rand(0,1),
                "is_bestseller"=>rand(0,1),
                "is_discount"=>rand(0,1),
            ));

            \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }
    }
}
