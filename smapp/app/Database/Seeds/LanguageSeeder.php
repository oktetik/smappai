<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\LanguageModel;

class LanguageSeeder extends Seeder
{
    public function run()
    {
        $model = new LanguageModel();

        // Ensure a clean slate when re-seeding.
        $model->truncate();

        $model->insertBatch([
            [
                'code'               => 'tr',
                'locale'             => 'tr_TR',
                'name_key'           => 'language.tr',
                'native_name'        => 'Türkçe',
                'direction'          => 'ltr',
                'status'             => 'active',
                'is_default'         => 1,
                'is_system_language' => 1,
                'origin'             => 'core',
            ],
            [
                'code'               => 'en',
                'locale'             => 'en_US',
                'name_key'           => 'language.en',
                'native_name'        => 'English',
                'direction'          => 'ltr',
                'status'             => 'active',
                'is_default'         => 0,
                'is_system_language' => 1,
                'origin'             => 'core',
            ],
        ]);
    }
}