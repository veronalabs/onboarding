<?php

use Veronalabs\Onboarding\Wizard;

require_once '../vendor/autoload.php';

$wizard = Wizard::getInstance();

$wizard->config([
    "title"         =>  "Onboarding",
    "slug"          =>  "wp-sms-onboarding",
    "settings_name" =>  "wpsms_settings",
    "capability"    =>  "manage_options",
    "redirect_url"  =>  "http://localhost:10009/wp-admin/admin.php?page=wp-sms-settings",
    "options"       =>  [
        "config"    =>  "",
        "steps"     =>  "",
        "data"      =>  ""
    ],
    "logo"          =>  "",
    "admin_icon"    =>  "dashicons-info",
    "css_url"       =>  "assets/css/onboarding.css",
]);

$wizard->step("content", [
    'title'         => 'Send SMS!',
    'slug'          => 'content',
    'label'         => 'Content',
    'next'          =>  'reciever',
    'description'   => 'An optional short description goes here.',
    'fields'        => [
        [
            'type'          => 'text',
            'option_name'   => 'admin_mobile_number',
            'label'         => 'Admin Number',
            'default'       => 'write your SMS message here',
            'required'      =>  true
        ],
    ]
]);

$wizard->step("reciever", [
    'title'         => 'Send SMS!',
    'slug'          => 'reciever',
    'label'         => 'Reciever',
    'next'          =>  'options',
    'description'   => 'An optional short description goes here.',
    'fields'        => [
        [
            'type'          => 'text',
            'option_name'   => 'admin_mobile_number',
            'label'         => 'FROM',
            'default'       => '0901000000',
            'required'      =>  true
        ],
        [
            'label'         =>  'FROM',
            'type'          =>  'template',
            'template_path' =>  dirname(__DIR__, 1) . "/examples/templates/wp-sms/reciever-fields.php",  
        ],
    ]
]);


$wizard->step("options", [
    'title'         => 'Send SMS!',
    'slug'          => 'options',
    'label'         => 'Options',
    'next'          =>  'send',
    'description'   => 'An optional short description goes here.',
    'fields'        => [
        [
            'label'         =>  'Check MMs MEDIA',
            'type'          =>  'template',
            'template_path' =>  dirname(__DIR__, 1) . "/examples/templates/wp-sms/options-fields.php",  
        ],
    ]
]);

$wizard->step("send", [
    'title'         => 'Send SMS!',
    'slug'          => 'send',
    'label'         => 'Send',
    'description'   => 'An optional short description goes here.',
    'fields'        => [
        [
            'label'         =>  '',
            'type'          =>  'template',
            'template_path' =>  dirname(__DIR__, 1) . "/examples/templates/wp-sms/send-fields.php",  
        ],
    ]
]);


add_action('init', function() use ($wizard){
    $wizard->register();
});

// function your_plugin_activation()
// {
//     Wizard::startWizard();
// } 
// register_activation_hook( __FILE__, 'your_plugin_activation' );