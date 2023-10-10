<?php

namespace Database\Seeders;

use App\Constants\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Role as SpatieRole;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->truncate();
        collect(Role::all())->each(fn ($name, $key) => $this->saveRole($key));
    }

    private function saveRole(string $name): void
    {
        $r = new SpatieRole(['guard_name' => 'web']);
        $r->name = $name;

        $r->save();
    }
}
