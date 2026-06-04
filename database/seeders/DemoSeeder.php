<?php

namespace Database\Seeders;

use App\Models\Klasifikasi;
use App\Models\Opd;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $opds = [
            ['kode' => 'SETDA', 'nama' => 'Sekretariat Daerah'],
            ['kode' => 'DISKOMINFO', 'nama' => 'Dinas Komunikasi dan Informatika'],
            ['kode' => 'DINKES', 'nama' => 'Dinas Kesehatan'],
        ];

        foreach ($opds as $data) {
            Opd::firstOrCreate(['kode' => $data['kode']], $data);
        }

        $klasifikasis = [
            ['kode' => '005', 'nama' => 'Undangan'],
            ['kode' => '010', 'nama' => 'Laporan'],
            ['kode' => '020', 'nama' => 'Pemberitahuan'],
            ['kode' => '030', 'nama' => 'Permohonan'],
            ['kode' => '050', 'nama' => 'Keputusan'],
        ];

        foreach ($klasifikasis as $data) {
            Klasifikasi::firstOrCreate(['kode' => $data['kode']], $data);
        }

        $superadmin = User::firstOrCreate(
            ['email' => 'superadmin@esurat.test'],
            ['name' => 'Super Admin', 'password' => bcrypt('password'), 'is_aktif' => true]
        );
        $superadmin->assignRole('superadmin');

        $roles = ['admin_tu', 'pimpinan', 'staf'];

        foreach (Opd::all() as $opd) {
            $kode = strtolower($opd->kode);
            foreach ($roles as $role) {
                $user = User::firstOrCreate(
                    ['email' => "{$role}.{$kode}@esurat.test"],
                    [
                        'name' => ucfirst(str_replace('_', ' ', $role)).' '.$opd->kode,
                        'password' => bcrypt('password'),
                        'opd_id' => $opd->id,
                        'is_aktif' => true,
                    ]
                );
                $user->assignRole($role);
            }
        }
    }
}
