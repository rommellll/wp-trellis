<?php

defined( 'ABSPATH' ) || exit;

if(is_user_logged_in() && $attr['showProfile']) { ?>
    <div class="wopb-my-account-profile-section">
        <div class="wopb-my-account-user-img">
            <img src="<?php echo esc_url($profileUrl)?>" alt="">
        </div>
        <div class="wopb-my-account-user-data">
            <span class="wopb-user-name"><?php echo $userData->display_name ?></span>
            <span class="wopb-user-email"><?php echo $userData->user_email ?></span>
        </div>
    </div>
<?php 
}
?>

<div class="wopb-my-account-container">
    <?php
        if(class_exists('WC_Shortcode_My_Account')) {
            WC_Shortcode_My_Account::output([]);
        }
    ?>
</div>