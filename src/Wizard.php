<?php

namespace Veronalabs\Onboarding;

use ReflectionClass;

class Wizard
{

    const COMPLETED = 'completed';
    const EXITED    = 'exited';
    private $config = [];
    private $steps = [];
    private $currentStep = null;
    private $nextStep = null;
    private $prevStep = null;
    private $data = [];
    private $withWrapper = false;
    public function __construct()
    {

        $this->config = [
            "title"         =>  __("Onboarding Wizard", 'veronalabs-onboarding'),
            "slug"          =>  "veronalabs-onboarding-wizard",
            "options"       =>  [
                "config"    =>  "",
                "steps"     =>  "",
                "data"      =>  ""
            ],
        ];

        add_action('admin_init', [$this, 'maybeSkippedStep']);
        add_action('admin_init', [$this, 'maybeExitedWizard']);
        add_action('admin_init', [$this, 'maybeJustStarted']);
        add_action('admin_init', [$this, 'saveUserData']);
        add_action('init', [$this, 'register']);
    }

    public function config(array $config)
    {
        $config = array_replace($this->config, $config);
        $this->config = $config;
        return $this;
    }

    public function step($slug, array $data)
    {
        if(!$slug){
            throw new \Exception("slug is required for constructing a step");
        }
        $this->steps[$slug] = array_merge($data, ['slug' =>  $slug]);
        return $this;
    }

    public function withWrapper()
    {
        return $this->withWrapper = true;
    }

    public function register()
    {
        $this->data = $this->fetchData();
        if ($this->areWeHere() && $this->getData('status') && in_array($this->getData('status'), array(Wizard::COMPLETED, Wizard::EXITED))) {
            $this->redirect();
        }

        $this->setCurrentStep();
        $this->setNextStep();
        $this->setPrevStep();

        add_action('admin_menu', [$this, 'registerAdminPage']);
        add_action('admin_head', function () {
            remove_submenu_page('index.php', $this->getConfig('slug'));
        });

        if ($this->withWrapper && $this->areWeHere()) {
            add_action('admin_head', [$this, 'onboardingModaljs']);
        }
    }

    public function saveUserData()
    {

        if ($this->maybeDataPosted()) {
            if (isset($_GET['step'])) {
                $this->data['current_step'] =  $this->currentStep['slug'];
                $this->saveCurrentStep(self::sanitizeMaybeArray($_POST));
                if (sanitize_text_field($_GET['step']) == "") {
                    $this->data['status'] = Wizard::COMPLETED;
                    $this->saveData();
                    $this->redirect();
                }
            }
        }
    }

    public function callbackHandler()
    {
        if ($this->currentStep) {
            if ($this->getConfig('template_path')) {
                echo self::loadTemplate($this->getConfig('template_path'), ["wizard" => $this]);
            } else {
                echo self::loadTemplate(dirname(__FILE__, 1) . '/../templates/onboarding.php', ["wizard" => $this]);
            }
        }
        if (get_option("wizard_" . $this->getConfig('slug') . "_just_started")) {
            delete_option("wizard_" . $this->getConfig('slug') . "_just_started");
        }
        $this->saveData();
    }

    public function registerAdminPage()
    {
        if ($this->config == []) return;
        add_dashboard_page(
            __($this->getConfig('title', 'Veronalabs Onboarding'), 'veronalabs-onboarding'),
            __($this->getConfig('title', 'Veronalabs Onboarding'), 'veronalabs-onboarding'),
            $this->getConfig('capability', 'manage_options'),
            $this->getConfig('slug'),
            function () {
                $this->adminCallback();
            }
        );
    }

    public function maybeSkippedStep()
    {
        if ($this->maybeDataPosted() && isset($_POST['skip'])) {
            $skip     = sanitize_text_field($_POST['skip']);
            $location = remove_query_arg('skip');
            if ($skip == "next") {
                $this->data['current_step'] = $this->nextStep['slug'];
                $location                   = add_query_arg(['step' => $this->nextStep['slug']], $location);
            }
            if ($skip == "prev") {
                $this->data['current_step'] = $this->prevStep['slug'];
                $location                   = add_query_arg(['step' => $this->prevStep['slug']], $location);
            }
            $this->saveData();
            $this->redirect($location);
        }
    }

    public function maybeExitedWizard()
    {
        if ($this->maybeDataPosted() && isset($_POST['exit'])) {
            $this->data['status'] = Wizard::EXITED;
            $this->saveData();
            $this->redirect();
        }
    }

    public function maybeJustStarted()
    {
        global $pagenow;
        if (get_option("wizard_" . $this->getConfig('slug') . "_just_started") && $pagenow == 'plugins.php' && $this->data == []) {
            exit(wp_redirect($this->startWizardUrl()));
        }
    }

    public function getSteps()
    {
        if ($this->steps == []) {
            $key        = $this->optionKey("steps", $this->getStepsKey());
            $this->steps = get_option($key);
        }
        return $this->steps;
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

    public function renderField($field)
    {
        if ($field['type'] == 'template') {
            echo self::loadTemplate($field['template_path']);
        }

        if ($field['type'] == 'text') {
            $label = isset($field['label']) ? esc_html($field['label']) : "";
            $default    = isset($field['default']) ? $field['default'] : '';
            $dbValue = $this->getOptionValue($field['option_name']);
            $value = $dbValue ? $dbValue : $default;
            $isRequired = $field['required'] == true ? 'required' : '';
            return "<label> " . $label . " </label>
            <input type='text' class='w-auto' name='" . esc_html($field['option_name']) . "' placeholder='" . $field['label'] . "' value='" . esc_html($value) . "' " . esc_html($isRequired) . " />";
        }
    }

    public function getOptionValue($key)
    {
        if ($this->getConfig('settings_name')) {
            $settings = get_option($this->getConfig('settings_name'));
            return isset($settings[$key]) ? $settings[$key] : "";
        }

        return get_option($key, true);
    }

    public function startWizardUrl($slug = null)
    {
        $url = admin_url('admin.php');
        $url = add_query_arg(['page' => $slug ? $slug : $this->getConfig('slug')], $url);
        return $url;
    }

    public function startWizard()
    {
        if ($this->getData('status') && in_array($this->getData('status'), array(Wizard::EXITED, Wizard::COMPLETED))) {
            return;
        }
        update_option("wizard_" . $this->getConfig('slug') . "_just_started", true);
    }

    public function formAction()
    {
        return add_query_arg("step", isset($this->currentStep['next']) ?  $this->currentStep['next'] : "");
    }

    public function renderNextBtn()
    {
        if (isset($this->currentStep['next'])) {
            return "<form action='' method='post'>
                " . wp_nonce_field() . "
                <button class='wizard-btn' name='skip' value='next' type='submit'>Next</button>
            </form>";
        }
    }

    public function renderPrevBtn()
    {
        if (isset($this->currentStep['prev'])) {
            return "<form method='post' action='" . add_query_arg("skip", "prev") . "'>
                " . wp_nonce_field() . "
                <button class='wizard-btn' name='skip' value='prev' type='submit'>Prev</button>
            </form>";
        }
    }

    public static function renderExitBtn()
    {
        return  "<form method='post' action=''>
            " . wp_nonce_field() . "
            <button class='wizard-btn exit-btn' name='exit' value='true' type='submit'>Exit</button>
        </form>";
    }

    public static function stepLink($slug)
    {
        return add_query_arg('step', $slug);
    }

    public function onboardingModaljs()
    {
        $id = "onboarding-outer-" . esc_attr($this->getConfig('slug'));
?>
        <div id="<?php echo esc_html($id); ?>" style="
            width: 100%;
            min-height: 100%;
            position: fixed;
            z-index: 999998;
            left: 0px;
            top: 0;
            right: 0;
            bottom: 0;
            background: #ddd;
            margin: 0;
            overflow: auto;
        "></div>
<?php
    }

    private function adminCallback()
    {
        do_action('before_handle_onboarding_' . $this->getConfig('slug'));
        if ($this->getConfig('callback')) {
            if (is_array($this->getConfig('callback'))) {
                $class = $this->getConfig('callback')[0];
                $ref = new ReflectionClass($class);
                $class = $ref->newInstance();
                return $class->{$this->getConfig('callback')[1]}();
            }

            if (is_callable($this->getConfig('callback'))) {
                return $this->getConfig('callback')();
            }

            if (is_string($this->getConfig('callback'))) {
                return call_user_func($this->getConfig('callback'));
            }
        }

        $this->callbackHandler();
        do_action('after_handle_onboarding_' . $this->getConfig('slug'));
    }

    private function saveCurrentStep($data)
    {
        if ($this->getConfig('settings_name')) {
            $dbData = get_option($this->getConfig('settings_name'), true);
            foreach ($data as $key => $value) {
                $dbData[$key] = $value;
            }

            return update_option($this->getConfig('settings_name'), $dbData);
        }

        foreach ($data as $key => $value) {
            update_option($key, $value);
        }
    }

    private function maybeDataPosted()
    {

        return $this->areWeHere() && $_POST &&  wp_verify_nonce($_POST['_wpnonce']);
    }

    private function areWeHere()
    {
        global $pagenow;
        return  $pagenow == "admin.php" && isset($_GET['page']) && sanitize_text_field($_GET['page']) == $this->getConfig('slug');
    }

    private function saveConfig()
    {
        if ($this->config != []) {
            $key = $this->optionKey("config", $this->getConfigKey());
            update_option($key, $this->config);
        }
    }

    private function saveSteps()
    {
        if ($this->steps != []) {
            $key = $this->optionKey("steps", $this->getStepsKey());
            update_option($key, $this->steps);
        }
    }

    private function saveData()
    {
        if ($this->data) {
            $key = $this->optionKey("data", $this->getDataKey());
            update_option($key, $this->data);
        }
    }

    private function fetchData($force = false)
    {
        if ($this->data == [] || $force) {
            $key        = $this->optionKey("data", $this->getDataKey());
            $this->data = get_option($key);
        }
        return $this->data;
    }

    private function getCurrentStep()
    {
        $reqStep = isset($_GET['step']) ? sanitize_text_field($_GET['step']) : null;
        if ($reqStep) {
            return $this->getStep($reqStep);
        }

        return $this->getData('current_step') ? $this->getStep($this->getData('current_step')) : reset($this->steps);
    }

    private function setCurrentStep()
    {
        global $pagenow;

        $this->currentStep = $this->getCurrentStep();
        if (!$this->data && $pagenow == "plugin.php") {
            $this->data['current_step'] = reset($this->steps)['slug'];
            $this->saveData();
        }
    }

    private function setNextStep()
    {
        $this->nextStep = $this->getStep($this->currentStep['next']) ? $this->getStep($this->currentStep['next']) : null;
    }

    private function setPrevStep()
    {
        foreach ($this->steps as $key => $value) {
            if (isset($value['next']) && $value['next'] == $this->currentStep['slug']) {
                $this->prevStep            = $this->getStep($key);
                $this->currentStep['prev'] = $key;
                break;
            }
        }
    }

    private function optionKey($option, $default)
    {
        return (isset($this->config['options'][$option]) && $this->config['options'][$option] != "") ? $this->config['options'][$option] : $default;
    }

    private static function sanitizeMaybeArray($data)
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

    private function redirect($url = null)
    {
        if ($url) {
            exit(wp_redirect(wp_sanitize_redirect($url)));
        }

        $url = $this->getConfig('redirect_url', admin_url());
        exit(wp_redirect(wp_sanitize_redirect($url)));
    }

    private function getConfig($key, $default = null)
    {
        if (isset($this->config[$key]) && $this->config[$key] != "") {
            return $this->config[$key];
        }
        return $default;
    }

    private function getData($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }
        return null;
    }

    private function getStep($key)
    {
        if (array_key_exists($key, $this->steps)) {
            return $this->steps[$key];
        }
        return null;
    }

    private function getConfigKey()
    {
        return  'veronalabs_onboarding_config_' . $this->getConfig('slug');
    }

    private function getStepsKey()
    {
        return  'veronalabs_onboarding_steps_' . $this->getConfig('slug');
    }
    
    private function getDataKey()
    {
        return  'veronalabs_onboarding_data_' . $this->getConfig('slug');
    }

    
    
}
