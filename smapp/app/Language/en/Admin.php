<?php

/**
 * Admin Panel Language File - English
 * 
 * This file contains all admin panel texts.
 */

return [
    // General
    'title' => 'Admin Panel',
    'welcome' => 'Welcome',
    'dashboard' => 'Dashboard',
    'settings' => 'Settings',
    'users' => 'Users',
    'logout' => 'Logout',
    'login' => 'Login',
    'save' => 'Save',
    'cancel' => 'Cancel',
    'delete' => 'Delete',
    'restore' => 'Restore',
    'hard_delete' => 'Hard Delete',
    'edit' => 'Edit',
    'add' => 'Add',
    'back' => 'Back',
    'yes' => 'Yes',
    'no' => 'No',
    'enabled' => 'Enabled',
    'disabled' => 'Disabled',
    'active' => 'Active',
    'inactive' => 'Inactive',
    'loading' => 'Loading...',
    'processing' => 'Processing...',
    
    // Titles
    'admin_panel' => 'SMAPP Management Panel',
    'system_settings' => 'System Settings',
    'user_management' => 'User Management',
    'debug_page' => 'Debug - Config System',
    
    // Menu
    'menu' => [
        'dashboard' => 'Dashboard',
        'users' => 'Users',
        'settings' => 'Settings',
        'languages' => 'Languages',
        'debug' => 'Debug',
        'api_test' => 'API Test',
        'logout' => 'Logout'
    ],
    
    // Home Page
    'home' => [
        'welcome_message' => 'SMAPP config system is working successfully.',
        'how_to_use' => 'How to Use?',
        'admin_route_change' => 'Admin Route Change: You can change the admin panel URL from the settings page.',
        'config_file' => 'Config File: You can also manually change settings from the {file} file.',
        'security' => 'Security: You can increase security by changing the admin route.',
        'api_access' => 'API: You can access system APIs from {url}.',
        'current_config' => 'Current Configuration',
        'admin_route' => 'Admin Route',
        'admin_url' => 'Admin URL',
        'config_loaded' => 'Config File',
        'system_time' => 'System Time',
        'version_info' => 'SMAPP Config System v1.0 - CodeIgniter {version}'
    ],
    
    // Settings
    'settings' => [
        'title' => 'System Settings',
        'description' => 'You can manage system settings from here',
        'back_to_admin' => 'Back to Admin Panel',
        'current_config' => 'Current Configuration',
        'admin_route' => 'Admin Panel Route',
        'admin_route_help' => 'You can only use letters, numbers, hyphens (-) and underscores (_).',
        'admin_route_examples' => 'Examples: "admin", "panel", "management", "my-admin" etc.',
        'current_value' => 'Current value',
        'force_https' => 'Force HTTPS',
        'force_https_help' => 'When enabled, all HTTP requests are automatically redirected to HTTPS.',
        'force_https_warning' => 'Warning: Make sure your SSL certificate is active.',
        'force_www' => 'Force WWW',
        'force_www_help' => 'When enabled, all requests are redirected with www subdomain.',
        'force_www_example' => 'Example: domain.com → www.domain.com',
        'default_language' => 'Default Language',
        'default_language_help' => 'The default language of the system. New visitors will be greeted in this language.',
        'force_language_route' => 'Force Language Route',
        'force_language_route_help' => 'When enabled, language code becomes mandatory in URL even for default language.',
        'force_language_route_example' => 'Enabled: domain.com/en/page | Disabled: domain.com/page (for default language)',
        'language_settings' => 'Language Settings',
        'environment_settings' => 'Environment Settings',
        'save_settings' => 'Save Settings',
        'warning_title' => 'Warning',
        'warning_message' => 'After changing the admin route, you will need to access through the new URL. Your current session will end and you may need to log in again from the new address.',
        'future_features' => 'Future Features',
        'future_features_desc' => 'Other settings to be added to this config system:',
        'future_list' => [
            'Site title and description',
            'Email settings',
            'Database settings',
            'Cache settings',
            'Security settings',
            'API settings'
        ]
    ],
    
    // Labels
    'label' => [
        'admin_route'        => 'Admin Route',
        'force_https'        => 'Force HTTPS',
        'force_www'          => 'Force WWW',
        'default_language'   => 'Default Language',
        'force_language_route' => 'Force Language Route',
        'ci_environment'      => 'CI Environment',
        'app_baseURL'         => 'Base URL',
        'logger_threshold'    => 'Logger Threshold'
    ],
    
    // Buttons
    'button' => [
        'save' => 'Save Settings'
    ],
    
    // Messages
    'messages' => [
        'success' => 'Operation completed successfully!',
        'error' => 'An error occurred!',
        'warning' => 'Warning!',
        'info' => 'Info',
        'admin_route_updated' => 'Admin route updated successfully!',
        'settings_updated' => 'Settings updated successfully!',
        'no_changes' => 'No changes were made.',
        'config_update_error' => 'Error occurred while updating config',
        'admin_route_empty' => 'Admin route cannot be empty!',
        'admin_route_invalid' => 'Admin route can only contain letters, numbers, hyphens and underscores!',
        'new_admin_url' => 'New Admin URL',
        'post_request_received' => 'POST request received!'
    ],
    
    // Debug
    'debug' => [
        'title' => 'SMAPP Config Debug',
        'config_file_status' => 'Config File Status',
        'file_path' => 'File Path',
        'file_exists' => 'File Exists',
        'file_writable' => 'Writable',
        'file_readable' => 'Readable',
        'file_size' => 'File Size',
        'last_modified' => 'Last Modified',
        'config_functions' => 'Config Functions',
        'function_exists' => 'function',
        'available' => 'Available',
        'not_available' => 'Not Available',
        'defined' => 'Defined',
        'not_defined' => 'Not Defined',
        'current_values' => 'Current Config Values',
        'functions_not_loaded' => 'Config functions not loaded!',
        'config_file_content' => 'Config File Content',
        'file_not_found' => 'Config file not found!',
        'test_config_update' => 'Test: Config Update',
        'test_button' => 'Run Config Update Test',
        'test_successful' => 'Test Successful! Config file updated.',
        'regex_error' => 'Regex Error! Pattern did not match.',
        'pattern_used' => 'Pattern Used',
        'original_content_read' => 'Original Content Read',
        'regex_matched' => 'Regex Matched',
        'file_written' => 'File Written',
        'bytes' => 'bytes'
    ],
    
    // Form
    'form' => [
        'required' => 'This field is required',
        'invalid_format' => 'Invalid format',
        'confirm_change' => 'Are you sure you want to make this change?',
        'confirm_admin_route_change' => 'Are you sure you want to change the admin route to "{new_route}"?\n\nNew address after change: {new_url}\n\nYour current session may end after this operation.'
    ],
    
    // API
    'api' => [
        'unauthorized' => 'Unauthorized access. Please log in.',
        'invalid_data' => 'Invalid data format',
        'stats_error' => 'Error occurred while getting statistics',
        'save_error' => 'Error occurred while saving settings',
        'admin_route_empty' => 'Admin route cannot be empty',
        'admin_route_invalid' => 'Admin route can only contain letters, numbers, hyphens and underscores',
        'config_not_found' => 'No setting found to update',
        'config_file_not_found' => 'Config file not found',
        'config_update_failed' => 'Config file could not be updated',
        'config_write_failed' => 'Config file could not be written'
    ],
    
    // Login
    'login' => [
        'title' => 'Admin Login',
        'username' => 'Username',
        'password' => 'Password',
        'login_button' => 'Login',
        'invalid_credentials' => 'Invalid username or password!',
        'logout_success' => 'Successfully logged out.'
    ]
];