<?php

if ( ! defined( 'ABSPATH' ) ) {
    die();
}

$dir = plugin_dir_path(__FILE__) . 'capture/';
include_once $dir . '/functions.php';
require_once plugin_dir_path(__FILE__) . 'egoi-for-wp-common.php';

$page = array(
    'home' => !isset($_GET['sub']),
    'send-email-test' => $_GET['sub'] == 'send-email-test',
);

?>


<div class="smsnf">
    <div class="smsnf-modal-bg"></div>
    <!-- Header -->
    <header>
        <div class="wrapper-loader-egoi">
            <h1>Smart Marketing > <b><?php _e( 'Transactional Email', 'egoi-for-wp' ); ?></b></h1>
            <?=getLoader('egoi-loader',false)?>
        </div>
        <nav>
            <ul>
                <li><a class="home <?= $page['home'] ?'-select':'' ?>" href="?page=egoi-4-wp-transactional-email"><?php _e('Configuration', 'egoi-for-wp'); ?></a></li>
                <li><a class="<?= $page['send-email-test'] ?'-select':'' ?>" href="?page=egoi-4-wp-transactional-email&sub=send-email-test"><?php _e('Send Email Test', 'egoi-for-wp'); ?></a></li>
            </ul>
        </nav>
    </header>
    <!-- / Header -->
    <!-- Content -->
    <main style="grid-template-columns: 1fr !important;">
        <!-- Content -->
        <section class="smsnf-content">

            <?php
                if(isset($_GET['sub']) && $_GET['sub'] == 'send-email-test'){
                    require_once plugin_dir_path(__FILE__) . 'transactionalemail/send-email-test.php';
                }else{       
                    require_once plugin_dir_path(__FILE__) . 'transactionalemail/home.php';
                }
            ?>
        </section>
        <!-- / Content -->
    </main>
</div>