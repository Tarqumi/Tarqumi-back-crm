<?php

return [
    'roles' => [
        'super_admin' => [
            'label' => 'Super Admin',
            'permissions' => ['*'], // All permissions
        ],
        'admin' => [
            'label' => 'Admin',
            'permissions' => [
                'manage_team',
                'manage_clients',
                'manage_projects',
                'edit_landing_page',
                'manage_blog',
                'view_contact_submissions',
            ],
        ],
        'founder' => [
            'label' => 'Founder',
            'permissions' => [
                'view_clients',
                'view_projects',
            ],
            'founder_roles' => [
                'cto' => [
                    'label' => 'CTO',
                    'additional_permissions' => [
                        'edit_landing_page',
                        'manage_blog',
                        'view_contact_submissions',
                    ],
                ],
                'ceo' => [
                    'label' => 'CEO',
                    'additional_permissions' => [],
                ],
                'cfo' => [
                    'label' => 'CFO',
                    'additional_permissions' => [],
                ],
            ],
        ],
        'hr' => [
            'label' => 'HR',
            'permissions' => [
                'manage_team',
                'view_team',
            ],
        ],
        'employee' => [
            'label' => 'Employee',
            'permissions' => [
                'view_own_profile',
                'view_own_tasks',
            ],
        ],
    ],
];
