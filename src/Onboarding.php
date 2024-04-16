<?php 

namespace Veronalabs\Onboarding;

use Reflection;
use ReflectionClass;
use Veronalabs\Onboarding\Contracts\OnboardingContract;
use Veronalabs\Onboarding\Contracts\ResourceContract;
use VeronaLabs\Onboarding\Contracts\RouteContract;
use Veronalabs\Onboarding\Contracts\SourceContract;
use Veronalabs\Onboarding\Controllers\RestController;
use Veronalabs\Onboarding\Resources\Option;
use Veronalabs\Onboarding\Routes\Route;
use WP_REST_Request;

class Onboarding
{

    private $config = [];
    private $steps = [];
    private $currentStep = "";
    private $nextStep = "";

    private $data = [];

    public function config(array $config)
    {
        $this->config = $config;
        return $this;
    }

    public function step($step, array $data)
    {
        $this->steps[$step] = $data;
        return $this;
    }

    public function register()
    {   

        $this->saveConfig();
        // $this->saveSteps();
        
        $this->data = $this->getData();
        $this->currentStep = $this->getCurrentStep();
        $this->nextStep = $this->getNextStep();
        add_action( 'admin_menu', array($this, "registerAdminPage") );
        
    }

    public function callbackHandler()
    {   
        if($_POST){
            $this->saveCurrentStep();   
            $this->data['current_step'] = $this->currentStep['slug'];
            $this->saveData();
        }
        if($this->currentStep){
            echo self::loadTemplate(dirname(__FILE__, 1) . '/templates/onboarding.php', ["currentStep" => $this->currentStep]);
        }else
        {
            echo "end of the story";
        }
    }

    public function registerAdminPage()
    {
        if($this->config == []) return;

        add_menu_page(
            __( $this->config["title"], 'veronalabs-onboarding' ),
            $this->config["title"],
            'manage_options',
            $this->config['slug']."-onboarding",
            function(){
                do_action('before_handle_onboarding_' . $this->config['slug']);
                if( array_key_exists("callback", $this->config) && $this->config['callback'] !== "" )
                {
                    if(is_array($this->config['callback']))
                    {
                        $class = new $this->config['callback'][0];
                        return $class->{$this->config['callback'][1]}();
                    }

                    if(is_callable($this->config['callback'])){
                        return $this->config['callback']();
                    }
                    
                    if(is_string($this->config['callback']))
                    {
                        return call_user_func($this->config['callback']);
                    }
                }
                
                $this->callbackHandler();
                
                do_action('after_handle_step_' . $this->config['slug']);
            },
            plugins_url( 'myplugin/images/icon.png' ),
            6
        );
    }

    private function saveCurrentStep()
    {
        
    }

    private function saveConfig()
    {
        if( $this->config != [])
        {
            $key = $this->optionKey("config", "veronalabs_onboarding_config");
            update_option($key, $this->config);
        }
    }
    
    private function saveSteps()
    {
        if( $this->steps != [])
        {
            $key = $this->optionKey("steps", "veronalabs_onboarding_steps");
            update_option($key, $this->steps);
        }
    }
    
    private function saveData()
    {
        if($this->data)
        {
            $key = $this->optionKey("data", "veronalabs_onboarding_data");
            update_option($key, $this->data);
        }
    }

    private function getData($force = false)
    {
        if( $this->data == [] || $force )
        {
            $key = $this->optionKey("data", "veronalabs_onboarding_data");
            $this->data = get_option($key);
        }
        return $this->data;
    }

    private function getCurrentStep()
    {
        $reqStep = isset($_GET['step']) ? sanitize_text_field($_GET['step']) : null;  
        if($reqStep){
            return $this->steps[$reqStep];
        }

        return (isset($this->data['current_step']) && $this->data['current_step'] !== "") ? $this->steps[$this->data['current_step']] :  reset($this->steps);  
        
    }

    private function getNextStep()
    {
        return array_key_exists('next', $this->currentStep) ? $this->steps[$this->currentStep['next']] : null;
    }

    private function optionKey($option, $default)
    {
        return (array_key_exists($option, $this->config['options']) && $this->config['options'][$option] != "") ? $this->config['options'][$option] : $default;
    }

    public static function loadTemplate($path, $parameters = [])
    {

        if (file_exists($path)) {   
            ob_start();

            extract($parameters);
            require $path;

            return ob_get_clean();
        }
    }

    public static function renderField($field)
    {
        if($field['type'] == 'template')
        {
            echo self::loadTemplate($field['template_path']);
        }
        if($field['type'] == 'text')
        {
            $default = $field['default'] ? $field['default'] : ''; 
            $isRequired = $field['required'] == true ? 'required' : '';
            echo "<input type='text' name='{$field['option_name']}' placeholder='{$field['label']}' value='$default' $isRequired />";
        }
    }


}