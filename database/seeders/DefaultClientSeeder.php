<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class DefaultClientSeeder extends Seeder
{
    /**
     * "Tarqumi" is the default client that CANNOT be deleted (business rule).
     * If no client is assigned to a project, it defaults to Tarqumi.
     */
    public function run(): void
    {
        Client::firstOrCreate(
            ['is_default' => true],
            [
                'name' => 'Tarqumi',
                'company_name' => 'Tarqumi',
                'email' => 'info@tarqumi.com',
                'phone' => '+966-XX-XXX-XXXX',
                'website' => 'https://tarqumi.com',
                'industry' => 'Technology',
                'notes' => 'Default client for internal projects. Cannot be deleted.',
                'is_active' => true,
                'is_default' => true,
            ]
        );
    }
}
