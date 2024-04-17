<?php

namespace Veronalabs\Onboarding;
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
        $this->data = $this->getData();
        if (key_exists('status', $this->data) && $this->data['status'] === 'COMPLETED') {
            return;
        }

        $this->currentStep = $this->getCurrentStep();
        $this->nextStep = $this->getNextStep();
        add_action('admin_menu', array($this, "registerAdminPage"));
    }

    public function callbackHandler()
    {
        if ($_POST && wp_verify_nonce($_POST['_wpnonce'])) {
            $this->data['current_step'] = $this->currentStep['slug'];
            $this->saveCurrentStep(self::sanitizeMaybeArray($_POST));
            if (!$this->nextStep) {
                $this->data['status'] = "COMPLETED";
            }
        }

        if ($this->currentStep) {
            if ($this->config['template_path']) {
                echo self::loadTemplate($this->config['template_path'], ["currentStep" => $this->currentStep, "config"  =>  $this->config]);
            } else {
                echo self::loadTemplate(dirname(__FILE__, 1) . '/templates/onboarding.php', ["currentStep" => $this->currentStep, "config"  => $this->config]);
            }
        }
        $this->saveData();
    }

    public function registerAdminPage()
    {
        if ($this->config == []) return;
        if (isset($this->config['parent'])  && $this->config['parent']) {
            add_submenu_page(
                $this->config['parent'],
                __($this->config['title'], 'veronalabs-onboarding'),
                __($this->config["title"], 'veronalabs-onboarding'),
                (isset($this->config['capability']) && $this->config['capability'] == '') ? $this->config['capability'] : 'manage_options',
                $this->config['slug'],
                function () {
                    $this->adminCallback();
                }
            );
        } else {
            add_menu_page(
                __($this->config["title"], 'veronalabs-onboarding'),
                __($this->config["title"], 'veronalabs-onboarding'),
                (isset($this->config['capability']) && $this->config['capability'] == '') ? $this->config['capability'] : 'manage_options',
                $this->config['slug'],
                function () {
                    $this->adminCallback();
                },
                isset($this->config['admin_icon']) ? $this->config['admin_icon'] : "dashicons-info"
            );
        }
    }

    private function adminCallback()
    {
        do_action('before_handle_onboarding_' . $this->config['slug']);
        if (array_key_exists("callback", $this->config) && $this->config['callback'] !== "") {
            if (is_array($this->config['callback'])) {
                $class = new $this->config['callback'][0];
                return $class->{$this->config['callback'][1]}();
            }

            if (is_callable($this->config['callback'])) {
                return $this->config['callback']();
            }

            if (is_string($this->config['callback'])) {
                return call_user_func($this->config['callback']);
            }
        }

        $this->callbackHandler();

        do_action('after_handle_step_' . $this->config['slug']);
    }

    private function saveCurrentStep($data)
    {

        if (key_exists('settings_name', $this->config) && $this->config['settings_name'] != "") {
            $dbData = get_option($this->config['settings_name'], true);
            foreach ($data as $key => $value) {
                $dbData[$key] = $value;
            }

            return update_option($this->config['settings_name'], $dbData);
        }

        foreach ($data as $key => $value) {
            update_option($key, $value);
        }
        return;
    }
    private function saveConfig()
    {
        if ($this->config != []) {
            $key = $this->optionKey("config", "veronalabs_onboarding_config");
            update_option($key, $this->config);
        }
    }
    private function saveSteps()
    {
        if ($this->steps != []) {
            $key = $this->optionKey("steps", "veronalabs_onboarding_steps");
            update_option($key, $this->steps);
        }
    }
    private function saveData()
    {
        if ($this->data) {
            $key = $this->optionKey("data", "veronalabs_onboarding_data");
            update_option($key, $this->data);
        }
    }

    private function getData($force = false)
    {
        if ($this->data == [] || $force) {
            $key = $this->optionKey("data", "veronalabs_onboarding_data");
            $this->data = get_option($key);
        }
        return $this->data;
    }

    private function getCurrentStep()
    {
        $reqStep = isset($_GET['step']) ? sanitize_text_field($_GET['step']) : null;
        if ($reqStep) {
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
        if ($field['type'] == 'template') {
            echo self::loadTemplate($field['template_path']);
        }
        if ($field['type'] == 'text') {
            $default = $field['default'] ? $field['default'] : '';
            $isRequired = $field['required'] == true ? 'required' : '';
            echo "<input type='text' name='{$field['option_name']}' placeholder='{$field['label']}' value='$default' $isRequired />";
        }
    }

    public static function sanitizeMaybeArray($data)
    {

        if (is_array($data)) {
            $final = [];
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $final[$key] = self::sanitizeMaybeArray($value);
                } else {
                    $final[$key] = sanitize_text_field($value);
                }
            }
            return $final;
        }

        return sanitize_text_field($data);
    }
}
