<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        factory(\App\User::class)->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'twitter' => 'john',
            'country_code' => 'USA',
            'password' => \Illuminate\Support\Facades\Hash::make('secret'),
        ]);

        factory(\App\User::class, 50)->create();

        \Illuminate\Support\Facades\Artisan::call('elephpants:read');

        $me = \App\User::find(1);
        $elephpants = \App\Elephpant::inRandomOrder()->limit(15)->get();
        foreach ($elephpants as $elephpant) {
            $me->elephpants()->attach($elephpant->id, ['quantity' => rand(2, 4)]);
        }

        /** @var \Illuminate\Database\Eloquent\Collection $users */
        $users = \App\User::inRandomOrder()->where('id', '<>', 1)->limit(rand(10, 15))->get();

        foreach ($users as $user) {
            $elephpants = \App\Elephpant::inRandomOrder()->limit(rand(5, 10))->get();
            foreach ($elephpants as $elephpant) {
                $user->elephpants()->attach($elephpant->id, ['quantity' => rand(1, 3)]);
            }
        }
    }
}
