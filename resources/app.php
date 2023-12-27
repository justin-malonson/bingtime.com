<?php

$app_specific = [
    'application_title' => 'SMS Gateway',
    'application_description' => 'Use Your Phone as SMS Gateway',
    'application_version' => '9.4.3',
    'app_version_code' => 41,
    'company_name' => 'RBSoft',
    'company_url' => 'https://rbsoft.org',
    'application_url' => 'https://rbsoft.org/downloads/sms-gateway/sms-gateway.apk',
    'unsubscribe_url' => '%server%/unsubscribe.php',
    'logo_src' => 'logo.png',
    'favicon_src' => 'favicon.ico',
    'get_credits_url' => 'https://rbsoft.org/downloads/sms-gateway/index.html#section-pricing',
    'skin' => 'blue',
    'default_language' => 'English',
    'default_use_progressive_queue' => 1,
    'default_credits' => 200,
    'default_devices_limit' => 2,
    'default_contacts_limit' => 200,
    'smtp_ssl_verification' => 1,
    'use_credits_for_received_messages_enabled' => 1
];

$lang = array_merge($lang, $app_specific);