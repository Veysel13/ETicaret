<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table("categories")->truncate();
        $id=DB::table("categories")->insertGetId(["name"=>"Elekronik","slug"=>"elektronik",'restaurant_id'=>1]);
        DB::table("categories")->insertGetId(["name"=>"Bilgisayar / Tablet","slug"=>"bilgisayar","parent_id"=>$id,'restaurant_id'=>1]);
        DB::table("categories")->insertGetId(["name"=>"Telefon","slug"=>"telefon","parent_id"=>$id,'restaurant_id'=>1]);
        DB::table("categories")->insertGetId(["name"=>"Tv ve Ses Sistemleri","slug"=>"tv-ses-sistemleri","parent_id"=>$id,'restaurant_id'=>1]);
        DB::table("categories")->insertGetId(["name"=>"Kamera","slug"=>"kamera","parent_id"=>$id,'restaurant_id'=>1]);


        $id=DB::table("categories")->insertGetId(["name"=>"Kitap","slug"=>"kitap",'restaurant_id'=>1]);
        DB::table("categories")->insertGetId(["name"=>"Edebiyat","slug"=>"edebiyat","parent_id"=>$id,'restaurant_id'=>1]);
        DB::table("categories")->insertGetId(["name"=>"Çocuk","slug"=>"cocuk","parent_id"=>$id,'restaurant_id'=>1]);
        DB::table("categories")->insertGetId(["name"=>"Bilgisayar","slug"=>"bilgisayar","parent_id"=>$id,'restaurant_id'=>1]);
        DB::table("categories")->insertGetId(["name"=>"Sınava hazırlık","slug"=>"sinava-hazirlik","parent_id"=>$id,'restaurant_id'=>1]);


        DB::table("categories")->insert(["name"=>"Dergi","slug"=>"dergi",'restaurant_id'=>1]);
        DB::table("categories")->insert(["name"=>"Mobilya","slug"=>"mobilya",'restaurant_id'=>1]);
        DB::table("categories")->insert(["name"=>"Dekerasyon","slug"=>"dekerasyon",'restaurant_id'=>1]);
        DB::table("categories")->insert(["name"=>"Kozmetik","slug"=>"dekerasyon",'restaurant_id'=>1]);
        DB::table("categories")->insert(["name"=>"Kişisel Bakım","slug"=>"kisisel-bakım",'restaurant_id'=>1]);
        DB::table("categories")->insert(["name"=>"Giyim Moda","slug"=>"giyim-moda",'restaurant_id'=>1]);
        DB::table("categories")->insert(["name"=>"Anne ve Coçuk","slug"=>"anne-cocuk",'restaurant_id'=>1]);
    }
}
