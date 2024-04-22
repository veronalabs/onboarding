<?php

use Veronalabs\Onboarding\Wizard;

?>
<div class="onboarding-wrap">
    <div class="onboarding-form">
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


        <?php 
            Wizard::renderNextBtn(); 
            Wizard::renderPrevBtn(); 
            Wizard::renderExitBtn(); 
        ?>
    </div>
</div>

<style>
    .onboarding-wrap
    {
        width: 100%;
        height: 100%;
        position: fixed;
        z-index: 99999;
        left: 0px;
        top: 0;
        right: 0;
        bottom: 0;
        background: #ddd;
        margin: 0;
    }
    .onboarding-form
    {
        width: 50%;
        margin: 0 auto;
    }
</style>