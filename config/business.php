<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Business Suite Master Configuration
    |--------------------------------------------------------------------------
    |
    | You can edit these core business parameters directly here or via cPanel.
    |
    */

    // Default System VAT Rate (Uganda standard 18%)
    'vat_rate' => 18.00,

    // Default Currency Settings
    'default_currency' => 'UGX',
    'default_currency_symbol' => 'UGX',

    // Low Stock Alert Threshold (units)
    'low_stock_threshold' => 10,

    // Default Trial Duration (Days)
    'default_trial_days' => 30,

    // Default Package Limits (fallback if plan limit is not specified)
    'limits' => [
        'starter' => [
            'max_products' => 100,
            'max_users' => 1,
            'max_invoices' => 100,
            'max_customers' => 100,
            'max_vendors' => 50,
            'max_locations' => 1,
        ],
        'professional' => [
            'max_products' => 500,
            'max_users' => 5,
            'max_invoices' => 1000,
            'max_customers' => 500,
            'max_vendors' => 200,
            'max_locations' => 3,
        ],
        'premium' => [
            'max_products' => null, // Unlimited
            'max_users' => null,
            'max_invoices' => null,
            'max_customers' => null,
            'max_vendors' => null,
            'max_locations' => null,
        ],
    ],
];
