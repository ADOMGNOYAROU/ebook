<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'STARTER',
                'slug' => 'starter',
                'description' => 'Parfait pour démarrer et tester votre librairie d\'e-books',
                'price' => 0.00,
                'billing_cycle' => 'monthly',
                'features' => [
                    'upload_ebooks' => true,
                    'basic_reader' => true,
                    'basic_analytics' => true,
                    'email_support' => true,
                    'custom_domain' => false,
                    'api_access' => false,
                    'advanced_analytics' => false,
                    'mobile_app' => false,
                    'priority_support' => false,
                    'white_label' => false,
                ],
                'max_ebooks' => 10,
                'max_users' => 1,
                'storage_mb' => 100,
                'has_custom_domain' => false,
                'has_api' => false,
                'has_analytics' => false,
                'has_mobile_app' => false,
                'is_active' => true,
                'stripe_price_id' => null, // Gratuit
            ],
            [
                'name' => 'PRO',
                'slug' => 'pro',
                'description' => 'Idéal pour les auteurs indépendants et petites maisons d\'édition',
                'price' => 29.00,
                'billing_cycle' => 'monthly',
                'features' => [
                    'upload_ebooks' => true,
                    'advanced_reader' => true,
                    'advanced_analytics' => true,
                    'email_support' => true,
                    'chat_support' => true,
                    'custom_domain' => true,
                    'api_access' => true,
                    'data_export' => true,
                    'custom_branding' => true,
                    'email_notifications' => true,
                    'mobile_app' => false,
                    'priority_support' => false,
                    'white_label' => false,
                ],
                'max_ebooks' => 100,
                'max_users' => 5,
                'storage_mb' => 5120, // 5 Go
                'has_custom_domain' => true,
                'has_api' => true,
                'has_analytics' => true,
                'has_mobile_app' => false,
                'is_active' => true,
                'stripe_price_id' => 'price_pro_monthly', // À configurer dans Stripe
            ],
            [
                'name' => 'PRO YEARLY',
                'slug' => 'pro-yearly',
                'description' => 'Version annuelle du plan PRO avec 2 mois gratuits',
                'price' => 290.00,
                'billing_cycle' => 'yearly',
                'features' => [
                    'upload_ebooks' => true,
                    'advanced_reader' => true,
                    'advanced_analytics' => true,
                    'email_support' => true,
                    'chat_support' => true,
                    'custom_domain' => true,
                    'api_access' => true,
                    'data_export' => true,
                    'custom_branding' => true,
                    'email_notifications' => true,
                    'mobile_app' => false,
                    'priority_support' => false,
                    'white_label' => false,
                ],
                'max_ebooks' => 100,
                'max_users' => 5,
                'storage_mb' => 5120, // 5 Go
                'has_custom_domain' => true,
                'has_api' => true,
                'has_analytics' => true,
                'has_mobile_app' => false,
                'is_active' => true,
                'stripe_price_id' => 'price_pro_yearly', // À configurer dans Stripe
            ],
            [
                'name' => 'ENTERPRISE',
                'slug' => 'enterprise',
                'description' => 'Solution complète pour les grandes maisons d\'édition et entreprises',
                'price' => 99.00,
                'billing_cycle' => 'monthly',
                'features' => [
                    'upload_ebooks' => true,
                    'premium_reader' => true,
                    'advanced_analytics' => true,
                    'email_support' => true,
                    'chat_support' => true,
                    'phone_support' => true,
                    'priority_support' => true,
                    'custom_domain' => true,
                    'api_access' => true,
                    'data_export' => true,
                    'custom_branding' => true,
                    'email_notifications' => true,
                    'mobile_app' => true,
                    'white_label' => true,
                    'sso_integration' => true,
                    'webhooks' => true,
                    'zapier_integration' => true,
                    'audit_logs' => true,
                    'dedicated_support' => true,
                ],
                'max_ebooks' => -1, // Illimité
                'max_users' => -1, // Illimité
                'storage_mb' => 51200, // 50 Go
                'has_custom_domain' => true,
                'has_api' => true,
                'has_analytics' => true,
                'has_mobile_app' => true,
                'is_active' => true,
                'stripe_price_id' => 'price_enterprise_monthly', // À configurer dans Stripe
            ],
            [
                'name' => 'ENTERPRISE YEARLY',
                'slug' => 'enterprise-yearly',
                'description' => 'Version annuelle du plan Enterprise avec 2 mois gratuits',
                'price' => 990.00,
                'billing_cycle' => 'yearly',
                'features' => [
                    'upload_ebooks' => true,
                    'premium_reader' => true,
                    'advanced_analytics' => true,
                    'email_support' => true,
                    'chat_support' => true,
                    'phone_support' => true,
                    'priority_support' => true,
                    'custom_domain' => true,
                    'api_access' => true,
                    'data_export' => true,
                    'custom_branding' => true,
                    'email_notifications' => true,
                    'mobile_app' => true,
                    'white_label' => true,
                    'sso_integration' => true,
                    'webhooks' => true,
                    'zapier_integration' => true,
                    'audit_logs' => true,
                    'dedicated_support' => true,
                ],
                'max_ebooks' => -1, // Illimité
                'max_users' => -1, // Illimité
                'storage_mb' => 51200, // 50 Go
                'has_custom_domain' => true,
                'has_api' => true,
                'has_analytics' => true,
                'has_mobile_app' => true,
                'is_active' => true,
                'stripe_price_id' => 'price_enterprise_yearly', // À configurer dans Stripe
            ],
        ];

        foreach ($plans as $planData) {
            Plan::create($planData);
        }
    }
}
