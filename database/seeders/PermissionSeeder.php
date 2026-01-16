<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        $permissions = [

            'work-list',
            'work-store',
            'work-update',
            'work-delete',
            'category-list',
            'category-store',
            'category-update',
            'category-delete',
            'application-list',
            'application-store',
            'application-update',
            'interview-list',
            'interview-create',
            'interview-update'

            
        ];

        foreach ($permissions as $perm) {
            Permission::findOrCreate($perm);
        }

        // ၂။ Roles များ ဖန်တီးခြင်း
        $admin = Role::findOrCreate('admin');
        $admin->givePermissionTo(Permission::all());

        $employer = Role::findOrCreate('employer');
        $employer->givePermissionTo(['work-list','category-list']);

        $recruiter = Role::findOrCreate('recruiter');
        $recruiter->givePermissionTo(['work-store', 'work-update','category-list','application-list','application-store','application-update',
        'interview-list','interview-create','interview-update']);

        $jobSeeker = Role::findOrCreate('job_seeker');
        $jobSeeker->givePermissionTo(['work-list','category-list']);


        $this->createUser('Admin User', 'admin@test.com', 'admin');
        $this->createUser('Employer One', 'employer@test.com', 'employer');
        $this->createUser('Recruiter One', 'recruiter@test.com', 'recruiter');
        $this->createUser('Job Seeker One', 'seeker@test.com', 'job_seeker');
    }

    private function createUser($name, $email, $roleName)
    {
        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make('password'),
            ]
        );
        $user->assignRole($roleName);
    }
}
