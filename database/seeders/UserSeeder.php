<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nombres' => 'superusuario',
                'apellido_paterno'=> 'usuario',
                'email'=> 'superusuario@live.es',
                'roles_id'=> 1,
                'estatus_id'=> 1,
                'password' => bcrypt('123456'),
            ],
            [
                'nombres' => 'simpleusuario',
                'apellido_paterno'=> 'usuario',
                'email'=> 'simpleusuario@live.es',
                'roles_id'=> 2,
                'estatus_id'=> 1,
                'password' => bcrypt('123456'),
            ],


        ];
        DB::table('users')->insert($data);
    }
}
