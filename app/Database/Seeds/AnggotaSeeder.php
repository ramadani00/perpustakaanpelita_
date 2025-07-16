<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\AnggotaModel; 

class AnggotaSeeder extends Seeder
{
    public function run()
    {
        $model = new AnggotaModel();
        
        $data = [
            [
                'nama' => 'Anggota1',
                'email' => 'anggota1@email.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
            ],
        ];

        $model->insertBatch($data);
    }
}