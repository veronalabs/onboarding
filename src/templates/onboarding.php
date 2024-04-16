<?php 
use Veronalabs\Onboarding\Onboarding;

?>
<div class="wrap">
    <h1><?php echo $currentStep['label'] ?></h1>
    <form method="post" action="<?php echo add_query_arg("step", $currentStep['next']); ?>">

        <?php
            wp_nonce_field("onboarding_step_posted");
            foreach ($currentStep['fields'] as $field) {
                Onboarding::renderField($field);
            }
        ?>

        <button type="submit">submit</button>
    </form>
</div>