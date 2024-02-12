<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Chat;
use App\Models\Customer;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Organization::factory()
            ->count(10)
            ->has(User::factory()->count(20))
            ->create();

        /** @var Organization $organization */
        $organization = Organization::first();
        $user = new User();
        $user->name = 'Admin';
        $user->email = 'admin@example.com';
        $user->password = 'password';
        $user->save();

        $organization->users()->attach($user->id);

        Organization::all()->each(function (Organization $organization) {
            $customer = Customer::factory()
                ->count(10)
                ->state(new Sequence(
                    [ 'organization_id' => $organization->id ]
                ))
                ->has(Chat::factory()->state(new Sequence([ 'organization_id' => $organization->id ]))->count(3))
                ->create();
        });
    }
}
