<?php return array (
  'admin_route' => 'smapp',
  'force_https' => false,
  'force_www' => false,
  'default_language' => 'en',
  'supported_languages' => 
  array (
    'tr' => 'Türkçe',
    'en' => 'English',
  ),
  'force_language_route' => true,
  'language_detect_use_ip' => false,
  'language_detect_use_browser' => false,
  'language_detect_use_user' => false,
  'language_detect_priority' => 
  array (
    0 => 'user',
    1 => 'ip',
    2 => 'browser',
    3 => 'default',
  ),
  'ci_environment' => 'development',
  'app_baseURL' => 'http://localhost/',
  'db_hostname' => '/Applications/MAMP/tmp/mysql/mysql.sock',
  'db_database' => 'smappai',
  'db_username' => 'smappai',
  'db_password' => '1Q2w3e4r..,',
  'db_driver' => 'MySQLi',
  'db_prefix' => '',
  'db_port' => 8889,
  'logger_threshold' => 4,
);