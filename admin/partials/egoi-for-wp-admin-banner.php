<?php
if ( ! defined( 'ABSPATH' ) ) {
    die();
}

add_thickbox();

?>

<div class="egoi-box" style="background-color: transparent; padding: 0;width:250px;">
    <?php 
        require_once dirname(__DIR__).'/partials/egoi-for-wp-admin-alert.php';
        if(!empty($alert))
            echo $alert;
    ?>
    <iframe id="iframe" src="https://eg.e-goi.com/pluginbanners/wp-iframe.php?type=v&lang=<?php echo get_locale(); ?>" height="480px" style="max-width: 100%;" ></iframe>
</div>


