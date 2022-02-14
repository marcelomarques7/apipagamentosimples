<?php

namespace Database\Seeders;

use App\Models\TipoUser;
use Illuminate\Database\Seeder;

class TipoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoUser::create([
            'tipo' => 'Comum',
            'autorizar_transferencia' => true            
        ]);

        TipoUser::create([
            'tipo' => 'Logista',
            'autorizar_transferencia' => false
        ]);
    }
}
