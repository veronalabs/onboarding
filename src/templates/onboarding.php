<?php

use Veronalabs\Onboarding\Wizard;

?>
<div class="wrap">
    <h1><?php echo $currentStep['label'] ?></h1>
    <form method="post" action="<?php echo add_query_arg("step", isset($currentStep['next']) ?  $currentStep['next'] : ""); ?>">

        <?php
        wp_nonce_field();
        foreach ($currentStep['fields'] as $field) {
            Wizard::renderField($field);
        }
        ?>

        <button type="submit">submit</button>
    </form>

    <?php if(isset($currentStep['next'])) { ?>
    <a href="<?php echo add_query_arg("skip", "next"); ?>">Next</a>
    <?php } ?>
    <?php if(isset($currentStep['prev'])) { ?>
    <a href="<?php echo add_query_arg("skip", "prev"); ?>">prev</a>
    <?php } ?>
    <a href="<?php echo add_query_arg("exit", "true"); ?>">Exit</a>
</div>