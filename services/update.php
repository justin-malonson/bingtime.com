<?php

require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../vendor/autoload.php";

echo json_encode([
    'versionCode' => __("app_version_code"),
    'url' => Setting::get("application_url")
]);