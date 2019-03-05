<?php

use App\User;

use App\SocialAccount;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //ADMIN
        $user = User::create([
            'id' => 1,
            'name' => "Admin",
            'email' => 'admin@podflix.com.br',
            'password' => bcrypt('podflix12345'),
            'activated' => true,
        ]);
        $this->command->info('User Admin created');

        Role::create(['name' => 'admin']);
        $this->command->info('Role Admin created');
        Role::create(['name' => 'user']);
        $this->command->info('Role User created');
        Role::create(['name' => 'podcaster']);
        $this->command->info('Role Podcaster created');

        $user->assignRole('admin');
        $this->command->info('Admin user seeded :-)');
        $this->command->info($user);


        $this->command->info("Creating Users");


        //USERS WITH SOCIAL

        /*
         * SELECT
            users.id, IF(concat(first_name, " ", last_name)=" ",username, concat(first_name, " ", last_name) ) as name, email, password, status as activated,
            case
                when meta_key = 'twitter'
                then "TwitterProvider"
                when meta_key = 'facebook'
                then "FacebookProvider"
                when meta_key = 'googleplus'
                then "GoogleProvider"
                else null
              end as provider,
              case
                  when meta_key = 'twitter'
                  then meta_value
                  when meta_key = 'facebook'
                  then meta_value
                  when meta_key = 'googleplus'
                  then meta_value
                  else null
                end as provider_user_id

            FROM
            users left join usermeta on users.id = usermeta.user_id
            AND usermeta.meta_key in ('twitter', 'facebook', 'googleplus')
         */


    }

}
