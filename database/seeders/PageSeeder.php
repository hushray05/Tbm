<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'home',
                'title' => 'Homepage',
                'meta_description' => 'TBM Enterprise Suite - Uganda\'s complete business management platform.',
                'content' => json_encode([
                    'hero_badge' => 'Uganda\'s #1 Business Management Platform',
                    'hero_title' => 'Run your entire Ugandan business from one operating system',
                    'hero_text' => 'Sales, purchases, inventory, invoicing, payroll, URA tax compliance and multi-branch operations — unified in UGX with MTN Mobile Money and Airtel Money support.',
                    'hero_cta_text' => 'Start 30-Day Free Trial',
                    'hero_image' => 'https://images.unsplash.com/photo-1556745753-b2904692b3cd?fm=jpg&q=60&w=1200&auto=format&fit=crop',
                    'trust_badge_text' => 'URA Compliant',
                    'trust_badge_subtext' => 'PAYE, NSSF, VAT 18%, LED',
                ]),
                'is_active' => true,
            ],
            [
                'slug' => 'features',
                'title' => 'Features & Modules',
                'meta_description' => 'Explore all 13 integrated modules covering the full commercial cycle.',
                'content' => json_encode([
                    'hero_badge' => 'Complete Feature Set',
                    'hero_title' => 'Everything you need to run a modern Ugandan enterprise',
                    'hero_text' => 'Thirteen integrated modules covering the full commercial cycle — from POS sales in Kampala to supplier bills in Jinja, all reconciling in a single UGX ledger with URA compliance built in.',
                ]),
                'is_active' => true,
            ],
            [
                'slug' => 'how-it-works',
                'title' => 'How It Works',
                'meta_description' => 'Get started with TBM Enterprise in under 15 minutes.',
                'content' => json_encode([
                    'hero_badge' => 'Get Started in Minutes',
                    'hero_title' => 'From sign-up to first sale in under 15 minutes',
                    'hero_text' => 'No implementation consultants, no months of setup. TBM is designed for Ugandan businesses to start transacting immediately — with URA compliance and mobile money integrated from day one.',
                ]),
                'is_active' => true,
            ],
            [
                'slug' => 'about',
                'title' => 'About TBM Enterprise',
                'meta_description' => 'Built in Uganda, for African enterprises. Learn our story.',
                'content' => json_encode([
                    'hero_badge' => 'About TBM Enterprise',
                    'hero_title' => 'Built in Uganda, for African enterprises',
                    'hero_text' => 'We\'re on a mission to make enterprise-grade business management accessible to every Ugandan entrepreneur — from a boda-boda spare parts shop in Nateete to a regional logistics firm with depots across East Africa.',
                    'story_title' => 'Why we built TBM',
                    'story_text' => 'In 2022, our founders watched Ugandan business owners wrestle with disconnected tools: a POS system that couldn\'t talk to their accounting software, spreadsheets for inventory that never matched reality, and manual URA tax calculations that consumed days every month.',
                ]),
                'is_active' => true,
            ],
            [
                'slug' => 'faq',
                'title' => 'FAQ & Support',
                'meta_description' => 'Quick answers to common questions about TBM Enterprise Suite.',
                'content' => json_encode([
                    'hero_badge' => 'Frequently Asked Questions',
                    'hero_title' => 'Quick answers to common questions',
                    'hero_text' => 'Can\'t find what you\'re looking for? Our Kampala-based support team is here to help.',
                ]),
                'is_active' => true,
            ],
            [
                'slug' => 'contact',
                'title' => 'Contact Us',
                'meta_description' => 'Get in touch with TBM Enterprise support team in Kampala.',
                'content' => json_encode([
                    'hero_badge' => 'Contact Support',
                    'hero_title' => 'Let\'s talk about your business',
                    'hero_text' => 'Our Kampala-based team is ready to help you get started with TBM Enterprise.',
                    'address' => 'Plot 45, Nakawa Business Park, Kampala, Uganda',
                    'phone' => '+256 700 123 456',
                    'email' => 'support@tbm-enterprise.com',
                    'whatsapp' => '+256 700 123 456',
                    'business_hours' => 'Mon-Fri: 8:00 AM - 6:00 PM EAT',
                ]),
                'is_active' => true,
            ],
        ];

        foreach ($pages as $pageData) {
            Page::updateOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );
        }
    }
}
