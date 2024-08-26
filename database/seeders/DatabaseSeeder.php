<?php

namespace Database\Seeders;


use App\Models\User;



// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Master',
            'email' => 'master@gmail.com',
            'password' => '$2y$12$92oHkBPmFAhEJ2PdkvhU6Od4QM3zme52DuedSFdWIEysJzOPlQYTq',
            'is_master' => 1
        ]);

        
        User::factory()->create([
            'name' => 'Doctor',
            'email' => 'pmo@gmail.com',
            'password' => '$2y$12$92oHkBPmFAhEJ2PdkvhU6Od4QM3zme52DuedSFdWIEysJzOPlQYTq'
        ]);

        User::factory()->create([
            'name' => 'Servidor',
            'email' => 'ceo@gmail.com',
            'password' => '$2y$12$92oHkBPmFAhEJ2PdkvhU6Od4QM3zme52DuedSFdWIEysJzOPlQYTq'
        ]);

        // \App\Models\User::factory(299)->create(); 
       
    }
}