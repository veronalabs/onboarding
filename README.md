# onboarding

This package containes only Wizard class which follows singleton pattern, </br>
for making instance of the class you just need: 

<code>$wizard = Wizard::getInstance();</code>


### config() method
now we need register our config with <code> config($array) </code> method that accepts only one 
array type argument with the following consideration:

This array **MUST** include:

| Name          | purpose                                                                                               | Default                                                            |
|---------------|-------------------------------------------------------------------------------------------------------|--------------------------------------------------------------------|
| slug          | Unique Slug For this wizard                                                                           | veronalabs-onboarding-wizard                                       |
| settings_name | the name of the settings that will be saved as an array in options (every posted field will be a key) | if It's not passed every posted field will be a stand-alone option |

and **SHOULD**‌ include:

| Name         | purpose                                                            | Default                                                                                                                      |
|--------------|--------------------------------------------------------------------|------------------------------------------------------------------------------------------------------------------------------|
| name         | General name                                                       | Onboarding Wizard                                                                                                            |
| capability   | access controll                                                  | `manage_options`                                                                                                             |
| redirect_url | redirection url after exite/completed                              | `admin_url()`                                                                                                                |
| logo         | front-end usage                                                    |                                                                                                                              |
| css_url      | front-end usage                                                    |                                                                                                                              |
| options      | custom key for saving data-section such as `config` `steps` `data` | <p>config: `veronalabs_onboarding_config`</br> steps: `veronalabs_onboarding_steps` </br> data: `veronalabs_onboarding_data` |


#### example :
```
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
    "css_url"       =>  "",
]);
```


## step() method

For registering steps, you can use this method which accepts two argument as follows: 
-   string `key` (same as `args`'s slug recommended)  
-   array `args` that includes :


| Name        | purpose                                              | Default |
|-------------|------------------------------------------------------|---------|
| title       | General Title                                        |         |
| slug        | Unique slug                                          |         |
| label       | Label of this step                                   |         |
| next        | Next steps's key (last step dosn't include this one) |         |
| description | Description of this step                             |         |
| fields      | [step() method's fields](#text-field)  |         |


### step() method's fields  

> notice : any extra key can be added to the args array and will be passed to the template as `$config` variable

> also current step's data will be passed to the template as `$currentStep` variable 

#### Text Field: 

| Name        | purpose                               | required |
|-------------|---------------------------------------|----------|
| type        | text                                  | yes      |
| option_name | name of option or setting's key name  | yes      |
| label       | Label of this field                   | no       |
| default     | default value                         | no       |
| required    |                                       | no       |


#### Template Filed:

| Name          | purpose              | required |
|---------------|----------------------|----------|
| type          | template             | yes      |
| template_path | path of the template | yes      |
| label         | Label of this field  | no       |


#### example :
```
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
            'default'       => 'write your SMS‌ message here',
            'required'      =>  true
        ],
        [
            'type'          =>  'template',
            'template_path' =>  dirname(__DIR__, 1) . "/examples/templates/wp-sms/reciever-fields.php",  
        ],
    ]
]);
```



### Register() method

After passing rquired data to the above methods, add this code the main page of your plugin: 


```
add_action('init', function() use ($wizard){
    $wizard->register();
});
```

### Start the wizard immediately after installation

Use the static `startWizard` method of Wizard `Wizard::startWizard()` inside of the `register_activation_hook`'s callback function
