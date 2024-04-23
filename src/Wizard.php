<?php

namespace Veronalabs\Onboarding;

class Wizard
{
    private $config = [];
    private $steps = [];
    private $currentStep = null;
    private $nextStep = null;
    private $prevStep = null;
    private $data = [];

    private static $instance;

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }

        add_action('admin_init', [self::$instance, 'maybeSkippedStep']);
        add_action('admin_init', [self::$instance, 'maybeExitedWizard']);
        add_action('admin_init', [self::$instance, 'maybeJustStarted']);
        add_action('init', [self::$instance, 'register']);

        return self::$instance;
    }

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
        global $pagenow;
        $this->data = $this->getData();
        if (isset($this->data['status']) && in_array($this->data['status'], array('COMPLETED', 'EXITED'))) {
            return;
        }

        $this->setCurrentStep();
        $this->setNextStep();
        $this->setPrevStep();

        add_action('admin_menu', [self::$instance, 'registerAdminPage']);
        add_action('admin_head', function () {
            remove_submenu_page('index.php', $this->config['slug']);
        });

        if ($pagenow == "admin.php" && isset($_GET['page']) && $_GET['page'] == $this->config['slug']) {
            add_action('admin_head', [self::$instance, 'onboardingModaljs']);
        }
    }

    public function callbackHandler()
    {
        if ($_POST && wp_verify_nonce($_POST['_wpnonce'])) {
            if (isset($_GET['step']) && sanitize_text_field($_GET['step']) != "") {
                $this->data['current_step'] = $this->currentStep['slug'];
                $this->saveCurrentStep(self::sanitizeMaybeArray($_POST));
            } else {
                $this->data['status'] = "COMPLETED";
                $this->saveData();
                self::redirect();
            }
        }

        if ($this->currentStep) {
            if (isset($this->config['template_path'])) {
                echo self::loadTemplate($this->config['template_path'], ["currentStep" => $this->currentStep, "config" => $this->config]);
            } else {
                echo self::loadTemplate(dirname(__FILE__, 1) . '/../templates/onboarding.php', ["currentStep" => $this->currentStep, "config" => $this->config]);
            }
        }

        if (get_option("wizard_{$this->config['slug']}_just_started")) {
            delete_option("wizard_{$this->config['slug']}_just_started");
        }

        $this->saveData();
    }

    public function registerAdminPage()
    {
        if ($this->config == []) return;
        add_dashboard_page(
            __($this->config['title'], 'veronalabs-onboarding'),
            __($this->config["title"], 'veronalabs-onboarding'),
            (isset($this->config['capability']) && $this->config['capability'] == '') ? $this->config['capability'] : 'manage_options',
            $this->config['slug'],
            function () {
                $this->adminCallback();
            }
        );
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
    }

    public function maybeSkippedStep()
    {
        global $pagenow;
        if (isset($_POST['skip']) &&  wp_verify_nonce($_POST['_wpnonce']) && $pagenow == "admin.php" && isset($_GET['page']) && sanitize_text_field($_GET['page']) == $this->config['slug']) {
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
        global $pagenow;
        if (isset($_POST['exit']) &&  wp_verify_nonce($_POST['_wpnonce']) && $pagenow == "admin.php" && isset($_GET['page']) && sanitize_text_field($_GET['page']) == $this->config['slug']) {
            $this->data['status'] = "EXITED";
            $this->saveData();
            $this->redirect();
        }
    }

    public function maybeJustStarted()
    {
        global $pagenow;

        if (get_option("wizard_{$this->config['slug']}_just_started") && $pagenow == 'plugins.php' && $this->data == []) {
            exit(wp_redirect($this->startWizardUrl()));
        }
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

    public static function getSteps()
    {
        return self::$instance->steps;
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
            $key        = $this->optionKey("data", "veronalabs_onboarding_data");
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

        return (isset($this->data['current_step']) && $this->data['current_step'] !== "") ? $this->steps[$this->data['current_step']] : reset($this->steps);
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
        $this->nextStep = array_key_exists('next', $this->currentStep) ? $this->steps[$this->currentStep['next']] : null;
    }

    private function setPrevStep()
    {
        foreach ($this->steps as $key => $value) {
            if (isset($value['next']) && $value['next'] == $this->currentStep['slug']) {
                $this->prevStep            = $this->steps[$key];
                $this->currentStep['prev'] = $key;
                break;
            }
        }
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
            $label = isset($field['label']) ? $field['label'] : "";
            $default    = $field['default'] ? $field['default'] : '';
            $isRequired = $field['required'] == true ? 'required' : '';
            return "<label> " . $label . " </label>
            <input type='text' class='w-auto' name='{$field['option_name']}' placeholder='{$field['label']}' value='$default' $isRequired />"; // todo escape
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

    public static function startWizardUrl($slug = null)
    {
        $url = admin_url('admin.php');
        $url = add_query_arg(['page' => $slug ? $slug : self::$instance->config['slug']], $url);

        return $url;
    }

    public static function startWizard($name = "veronalabs_onboarding")
    {
        update_option("wizard_" . self::$instance->config['slug'] . "_just_started", true);
    }

    private function redirect($url = null)
    {
        if ($url) {
            exit(wp_redirect(wp_sanitize_redirect($url)));
        }

        $url = isset($this->config['redirect_url']) ? $this->config['redirect_url'] : admin_url();
        exit(wp_redirect(wp_sanitize_redirect($url)));
    }

    public static function formAction()
    {
        return add_query_arg("step", isset(self::$instance->currentStep['next']) ?  self::$instance->currentStep['next'] : "");
    }

    public static function renderNextBtn()
    {
        if (isset(self::$instance->currentStep['next'])) {
            return "<form action='' method='post'>
                " . wp_nonce_field() . "
                <button class='wizard-btn' name='skip' value='next' type='submit'>Next</button>
            </form>";
        }
    }

    public static function renderPrevBtn()
    {
        if (isset(self::$instance->currentStep['prev'])) {
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


    public static function onboardingModaljs()
    {
        $id = "onboarding-outer-" . self::$instance->config['slug'];
    ?>
        <div id="<?php echo  $id; ?>" style="
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
        <script type="text/javascript">
            let outer = document.getElementById(<?php echo  $id; ?>);
            console.log(outer);
        </script>

    <?php
    }

}
