<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        User::all()->each(function ($user) {
            $user->delete();
        });
        User::truncate();

        $user_count = 3;
        $name = 'admin@admin.com';
        User::create([
            'name' => $name,
            'email' => $name,
            'password' => Hash::make($name),
        ]);
        for ($i = 1; $i < $user_count; $i++) {
            $name = 'user' . $i . '@user.com';
            $user = User::create([
                'name' => $name,
                'email' => $name,
                'password' => Hash::make($name),
            ]);
        }

        Category::all()->each(function ($cat) {
            $cat->delete();
        });
        Category::truncate();
        foreach (['Picture', 'Video', 'Archive', 'Document'] as $name) {
            Category::create(['name' => $name, 'logo' => 'image' . DIRECTORY_SEPARATOR . 'cat_' . strtolower($name) . '.png']);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
