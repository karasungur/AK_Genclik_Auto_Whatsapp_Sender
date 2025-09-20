<?php

return [
    'per_page' => [
        'default' => 12,
        'blog' => 9,
        'products' => 12,
        'projects' => 12,
        'stores' => 12,
    ],

    'menu' => [
        'limits' => [
            'brands' => env('MENU_LIMIT_BRANDS', 10),
            'stores' => env('MENU_LIMIT_STORES', 10),
            'projects' => env('MENU_LIMIT_PROJECTS', 10),
        ],
    ],

    'megaMenu' => [
        'orientation' => env('MEGA_MENU_ORIENTATION', 'vertical'),
    ],

    'cache' => [
        'ttl_minutes' => (int) env('CACHE_TTL_MIN', 10),
    ],

    'brand_theme' => [
        'primary' => '#ED8B00',
        'accent' => '#30D5C8',
    ],

    'ga4' => [
        'measurement_id' => env('GA4_ID'),
    ],

    'pwa' => [
        'theme_color' => env('PWA_THEME_COLOR', '#ED8B00'),
        'background_color' => env('PWA_BG_COLOR', '#ffffff'),
    ],

    'uploads' => [
        'max_size_kb' => (int) env('UPLOAD_MAX_KB', 10240),
        'allowed_mimes' => [
            'jpg',
            'jpeg',
            'png',
            'webp',
            'svg',
            'gif',
        ],
        'allow_svg' => filter_var(env('UPLOAD_ALLOW_SVG', false), FILTER_VALIDATE_BOOLEAN),
    ],

    'security' => [
        'admin_timeout_minutes' => 30,
        'admin_max_attempts' => 5,
        'admin_lockout_seconds' => 120,
    ],
];
