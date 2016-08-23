<?php
use Illuminate\Database\Seeder;
use App\Data\Blog\Permission;

class PermissionTableSeeder extends Seeder
{

    public function run()
    {
        $resources = [
            'tag' => [
                'read',
                'add',
                'edit',
                'delete',
            ],
            'privilege' => [
                'read',
                'add',
                'edit',
                'delete',
            ],
        ];
        foreach ($resources as $resource => $actions) {
            foreach ($actions as $action) {
                Permission::create([
                    'resource' => $resource,
                    'action' => $action,
                ]);
            }
        }
    }

}