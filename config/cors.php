<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'mechanics'],
    
    'allowed_methods' => ['*'],
    
    'allowed_origins' => ['*'], // In production, specify your domain
    
    'allowed_origins_patterns' => [],
    
    'allowed_headers' => ['*'],
    
    'exposed_headers' => [],
    
    'max_age' => 0,
    
    'supports_credentials' => false,
];