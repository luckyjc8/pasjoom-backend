<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Product;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker\Generator $faker)
    {
    	$sizes = ['S','M','L','XL','XXL'];
    	$type = ['vintage','local'];
        $conditions = ['used','new'];


     	$user = new User;
     	$user->username = "Test User";
     	$user->email = "test@test.com";
     	$user->password = Hash::make("password");
     	$user->phone = "123456789";
     	$user->is_activated = true;
     	$user->save();

     	for($i=1;$i<=20;$i++){
     		$product = new Product;
            $product->user_id = 1;
     		$product->name = "Baju ".$i;
     		$product->price = rand(5,20) * 10000;
     		$product->size = $sizes[rand(0,4)];
     		$product->type = $type[rand(0,1)];
            $product->condition = $conditions[rand(0,1)];
     		$product->image_count = 1;
            $product->desc = $faker->sentence;
     		$product->save();
     	}
    }


}
