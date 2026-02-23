<?php

namespace Database\Seeders;

use App\Models\ShowcaseProject;
use Illuminate\Database\Seeder;

class ShowcaseProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'name_ar' => 'نظام إدارة المستشفيات',
                'name_en' => 'Hospital Management System',
                'description_ar' => 'نظام شامل لإدارة المستشفيات يشمل إدارة المرضى والمواعيد والفواتير',
                'description_en' => 'Comprehensive hospital management system including patient, appointment, and billing management',
                'url' => 'https://example.com/hospital',
                'is_live' => true,
                'order' => 1,
            ],
            [
                'name_ar' => 'منصة التجارة الإلكترونية',
                'name_en' => 'E-Commerce Platform',
                'description_ar' => 'منصة تجارة إلكترونية متكاملة مع نظام دفع آمن وإدارة المخزون',
                'description_en' => 'Complete e-commerce platform with secure payment system and inventory management',
                'url' => 'https://example.com/ecommerce',
                'is_live' => true,
                'order' => 2,
            ],
            [
                'name_ar' => 'تطبيق توصيل الطعام',
                'name_en' => 'Food Delivery App',
                'description_ar' => 'تطبيق جوال لتوصيل الطعام مع تتبع الطلبات في الوقت الفعلي',
                'description_en' => 'Mobile app for food delivery with real-time order tracking',
                'url' => 'https://example.com/food-app',
                'is_live' => true,
                'order' => 3,
            ],
            [
                'name_ar' => 'نظام إدارة المدارس',
                'name_en' => 'School Management System',
                'description_ar' => 'نظام متكامل لإدارة المدارس يشمل الطلاب والمعلمين والدرجات',
                'description_en' => 'Integrated school management system including students, teachers, and grades',
                'url' => null,
                'is_live' => false,
                'order' => 4,
            ],
        ];

        foreach ($projects as $project) {
            ShowcaseProject::create($project);
        }
    }
}
