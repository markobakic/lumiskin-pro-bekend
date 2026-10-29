<?php

/*
 * CORS for the public + admin API consumed by the Next.js site.
 * Token auth (Bearer) is used, so credentialed cookies are not required.
 * Set FRONTEND_URL in .env to a comma-separated list of allowed origins,
 * e.g. "https://lumiskinpro.rs,http://localhost:3000".
 */

$origins = array_values(array_filter(array_map(
    'trim',
    explode(',', (string) env('FRONTEND_URL', 'http://localhost:3000'))
)));

return [
    'paths' => ['api/*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => $origins ?: ['http://localhost:3000'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,
];
