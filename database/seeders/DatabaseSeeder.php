<?php

namespace Database\Seeders;


use App\Models\User;
use App\Models\Perfiles;



// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Modulo;
use App\Models\UserModule;
use Illuminate\Support\Facades\DB;

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
            'email' => 'doctor@gmail.com',
            'password' => '$2y$12$92oHkBPmFAhEJ2PdkvhU6Od4QM3zme52DuedSFdWIEysJzOPlQYTq'
        ]);

        User::factory()->create([
            'name' => 'Secretaria',
            'email' => 'secretaria@gmail.com',
            'password' => '$2y$12$92oHkBPmFAhEJ2PdkvhU6Od4QM3zme52DuedSFdWIEysJzOPlQYTq'
        ]);

        // \App\Models\User::factory(299)->create(); 

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Modulo::truncate();
        UserModule::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $modulos = [
            [
                'nombre' => 'Inicio',
                'items' => [
                    [
                        'nombre' => 'Analythical',
                        'route' => '/',
                    ],
                    /*
                    [
                        'nombre' => 'Classic',
                        'route' => '/dashboards/classic',
                    ],
                    [
                        'nombre' => 'Demographical',
                        'route' => '/dashboards/demographical',
                    ],
                    [
                        'nombre' => 'Minimal',
                        'route' => '/dashboards/minimal',
                    ],
                    [
                        'nombre' => 'Ecommerce',
                        'route' => '/dashboards/ecommerce',
                    ],
                    [
                        'nombre' => 'Modern',
                        'route' => '/dashboards/modern',
                    ]
                    */
                ],
            ],
            [
                'nombre' => 'Configuración',
                'items' => [
                    [
                        'nombre' => 'Usuarios',
                        'items' => [
                            [
                                'nombre' => 'Usuarios',
                                'route' => '/usuarios',
                            ],
                            [
                                'nombre' => 'Roles',
                                'route' => '/roles',
                            ],
                            [
                                'nombre' => 'Equipos',
                                'route' => '/equipos',
                            ]
                        ]
                    ],
                    [
                        'nombre' => 'Accesos',
                        'route' => '/accesos',
                    ],
                    [
                        'nombre' => 'Locaciones',
                        'items' => [
                            [
                                'nombre' => 'Países',
                                'route' => '/paises',
                            ],
                            [
                                'nombre' => 'Estados',
                                'route' => '/estados',
                            ],
                            [
                                'nombre' => 'Municipios',
                                'route' => '/municipios',
                            ],
                            [
                                'nombre' => 'Ciudades',
                                'route' => '/ciudades',
                            ]
                        ]
                    ]
                ],
            ],
            [
                'nombre' => 'Nube',
                'items' => [
                    [
                        'nombre' => 'Sincronizar',
                        'route' => '/cloud'
                    ],
                ],
            ],
            [
                'nombre' => 'Administración',
                'items' => [
                    [
                        'nombre' => 'Empresas',
                        'route' => '/empresas'
                    ],
                    [
                        'nombre' => 'Plazas',
                        'route' => '/plazas'
                    ],
                    [
                        'nombre' => 'Medicamentos',
                        'route' => '/medicamentos'
                    ],
                ],
            ]
        ];

        foreach($modulos as $row) {
            $m1 = Modulo::create(['nombre' => $row['nombre']]);
            if(isset($row['items'])) {
                foreach($row['items'] as $row2) {
                    $route = isset($row2['route']) ? $row2['route'] : null;
                    $m2 = Modulo::create(['nombre' => $row2['nombre'], 'route' => $route, 'parent_id' => $m1->id]);

                    if(isset($row2['items'])) {
                        foreach($row2['items'] as $row3) {
                            $route = isset($row3['route']) ? $row3['route'] : null;
                            $m3 = Modulo::create(['nombre' => $row3['nombre'], 'route' => $route, 'parent_id' => $m2->id]);
                        }
                    }
                }
            }
        }        
        
    }
}