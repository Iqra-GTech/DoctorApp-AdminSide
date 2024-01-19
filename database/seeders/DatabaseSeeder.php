<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{

    public function run()
    {

        // roles table
        DB::table('roles')->insert([

            [
                'name' => 'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Doctor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Patient',
                'created_at' => now(),
                'updated_at' => now(),
            ]

        ]);

        // user table
        DB::table('users')->insert([
            [
                'role_id' => '1',
                'email' => 'abdulrehman176617@gmail.com',
                'password' => Hash::make('123'),
                'phone_number' => '+61712649672',
                'verified' => '0',
                'created_by' => '1',
                'remember_token' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => '2',
                 'email' => 'doctor@gmail.com',
                 'password' => Hash::make('123'),
                 'phone_number' => '+61512345435',
                 'verified' => '0',
                 'created_by' => '1',
                 'remember_token' => '',
                 'created_at' => now(),
                 'updated_at' => now(),
             ],
             [
                'role_id' => '3',
                 'email' => 'patient@gmail.com',
                 'password' => Hash::make('123'),
                 'phone_number' => '+61412345678',
                 'verified' => '0',
                 'created_by' => '1',
                 'remember_token' => '',
                 'created_at' => now(),
                 'updated_at' => now(),
             ],
        ]);

        // user meta

        DB::table('user_meta')->insert([
            
            [
                'user_id' => '1',
                'role_id' => '1',
                'option' => '_Gender',
                'value' => 'Male',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => '2',
                'role_id' => '2',
                'option' => '_Gender',
                'value' => 'Male',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => '3',
                'role_id' => '3',
                'option' => '_Gender',
                'value' => 'Female',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);


        // Module Manager
        DB::table('module_manager')->insert([
            
            [
                'name' => 'Medicine',
                'table_name' => 'medicines',
                'role_id' => '2',
                'created_by' => '1',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Adverse Affects',
                'table_name' => 'adverse_affects',
                'role_id' => '3',
                'created_by' => '1',
                'created_at' => now(),
                'updated_at' => now()
            ]
            // [
            //     'name' => 'Disorders',
            //     'table_name' => 'disorders',
            //     'role_id' => '3',
            //     'created_by' => '1',
            //     'created_at' => now(),
            //     'updated_at' => now()
            // ],
            // [
            //     'name' => 'Vaccinations',
            //     'table_name' => 'vaccinations',
            //     'role_id' => '2',
            //     'created_by' => '1',
            //     'created_at' => now(),
            //     'updated_at' => now()
            // ]
        ]);


        // Module Mata

        DB::table('module_mata')->insert([

            [
                'module_id' => '1',
                'type' => 'text', // text,textarea,number,dropdown,date,datetime,file
                'option' => 'medicine_name',
                'value' => 'Panadol',
                'required' => '1',
                'dependency' => 'medicines',
                'import_option' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'module_id' => '1',
                'type' => 'text', // text,textarea,number,dropdown,date,datetime,file
                'option' => 'company_name',
                'value' => 'GSK Group',
                'required' => '1',
                'dependency' => 'medicines',
                'import_option' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'module_id' => '1',
                'type' => 'number', // text,textarea,number,dropdown,date,datetime,file
                'option' => 'mg',
                'value' => '250',
                'required' => '1',
                'dependency' => 'medicines',
                'import_option' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ]
            
        ]);


        // 'module_id' => '2'

        DB::table('module_mata')->insert([

            [
                'module_id' => '2',
                'type' => 'text', // text,textarea,number,dropdown,date,datetime,file
                'option' => 'Name',
                'value' => 'Stomach Ulcers',
                'required' => '1',
                'dependency' => 'medicines',
                'import_option' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'module_id' => '2',
                'type' => 'text', // text,textarea,number,dropdown,date,datetime,file
                'option' => 'due_to_which_medicine',
                'value' => 'Panadol',
                'required' => '1',
                'dependency' => 'medicines',
                'import_option' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ]
            
        ]);




        
    }
}
