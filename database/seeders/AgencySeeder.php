<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\Zone;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AgencySeeder extends Seeder
{
    public function run(): void
    {
        $agency = Agency::create([
            'name' => 'Agence Immobilière SGI',
            'slug' => 'sgi',
            'email' => 'contact@sgi-immo.sn',
            'telephone' => '+221 77 000 00 00',
            'adresse' => 'Dakar, Sénégal',
            'ville' => 'Dakar',
            'taux_commission' => 10,
            'is_active' => true,
        ]);

        $zoneDakar = Zone::create([
            'agency_id' => $agency->id,
            'nom' => 'Antenne Dakar',
            'ville' => 'Dakar',
            'is_active' => true,
        ]);

        Zone::create([
            'agency_id' => $agency->id,
            'nom' => 'Antenne Ziguinchor',
            'ville' => 'Ziguinchor',
            'is_active' => true,
        ]);

        // Super admin (pas d'agency)
        $superAdmin = User::create([
            'nom' => 'Admin',
            'prenom' => 'Super',
            'email' => 'superadmin@sgi-immo.sn',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'agency_id' => null,
            'zone_id' => null,
        ]);
        $superAdmin->assignRole('super_admin');

        // Agency admin
        $admin = User::create([
            'nom' => 'Admin',
            'prenom' => 'Agence',
            'email' => 'admin@sgi-immo.sn',
            'password' => Hash::make('password'),
            'role' => 'agency_admin',
            'agency_id' => $agency->id,
            'zone_id' => null,
        ]);
        $admin->assignRole('agency_admin');
    }
}
