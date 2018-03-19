<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    // Permissions
    private $permissions = [
        ['name' => 'user'],
        ['name' => 'city'],
        ['name' => 'country'],
        ['name' => 'phone'],
        ['name' => 'email'],
        ['name' => 'product'],
        ['name' => 'client'],
        ['name' => 'discount'],
    ];

    // TypeOrganisation
    private $typesOrganisation = [
                ['name' => 'sell'],
                ['name' => 'realtor'],
                ['name' => 'call'],
                ['name' => 'cafe'],
            ];

    // Discount
    private $discounts = [
                ['value' =>5],
                ['value' =>10],
                ['value' =>15],
                ['value' =>20],
            ];
    
    // roles
    private $roles = [
            ['name' => 'client'],
            ['name' => 'agent'],
            ['name' => 'admin'],
        ];

    /**1
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        
        factory(App\Models\User::class, 5)->create();
        factory(App\Models\City::class, 5)->create();
        factory(App\Models\Country::class, 5)->create();
        factory(App\Models\Organisation::class, 5)->create();
        factory(App\Models\Client::class, 5)->create();
        factory(App\Models\Product::class, 5)->create();
        factory(App\Models\Email::class, 5)->create();
        factory(App\Models\Phone::class, 5)->create();
        
        DB::table('permissions')->insert($this->permissions);
        DB::table('type_organisations')->insert($this->typesOrganisation);
        DB::table('roles')->insert($this->roles);
        
        foreach ($this->discounts as $discount){
            DB::table('discounts')
                ->insert([
                    'value' => $discount['value'],
                    'description' => $faker->text(100)
                ]);
        }
        
        // ===== With foreign key =====   
        
        factory(App\Models\OrganisationInformation::class, 5)->create();
        factory(App\Models\ClientAdress::class, 5)->create();
        factory(App\Models\ClientEmail::class, 5)->create();
        factory(App\Models\ClientPhone::class, 5)->create();
        factory(App\Models\ProductDiscount::class, 5)->create();
        factory(App\Models\UsersPermission::class, 5)->create();
        factory(App\Models\UsersRoles::class, 5)->create();

    }
}
