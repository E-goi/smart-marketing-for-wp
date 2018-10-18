<?php
if ( ! defined( 'ABSPATH' ) ) {
    die();
}
?>

<!-- head -->
<div class="container">
  <div class="columns">
    <div class="column">
        <h1 class="logo">Smart Marketing - <?php _e( 'Dashboard', 'egoi-for-wp' ); ?></h1>
            <div class="breadcrumbs">
                <span class="prefix"><?php echo __( 'You are here: ', 'egoi-for-wp' ); ?></span>
                    <strong>Smart Marketing</a> &rsaquo;
                        <span class="current-crumb"><?php _e( 'Dashboard', 'egoi-for-wp' ); ?>
                    </strong>
                </span>
            </div>
        <hr/>
    </div>
  </div>
</div><!-- /head -->

<!-- Notifications -->
<div class="container">
    <div class="columns">
        <div class="column">
            <div class="toast dash-notifications">
                <div class="dash-notifications__img">
                    <figure class="avatar avatar-xl dash-notifications__icon-upgrade"></figure>
                </div>
                <div class="dash-notifications__text" >
                    <h3 class="text-dark">Upgrade da Conta</h3>
                    <p class="text-dark">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt</p>
                </div>
                <div class="dash-notifications__close">
                    <button class="btn btn-clear"></button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Notifications -->


<!-- Subscriber Statistics -->
<!-- Registrations made today -->
<div class="container">
  <div class="columns subscriber-statistics">
    <div class="column col-4 col-md-12 col-xs-12 mt-2">
        <div class="subscriber-statistics__element">
            <div class="subscriber-statistics__element__today"></div>
        </div>
        <div class="subscriber-statistics__element__regists">
            <p class="subscriber-statistics__element__regists__title">Registos efetuados hoje</p>
            <p class="subscriber-statistics__element__regists__result">123</p>
        </div>
    </div>
    <div class="column col-4 col-md-12 col-xs-12 mt-2">
        <div class="subscriber-statistics__element">
            <div class="subscriber-statistics__element__today"></div>
        </div>
        <div class="subscriber-statistics__element__regists">
            <p class="subscriber-statistics__element__regists__title">Total de Subscritores</p>
            <p class="subscriber-statistics__element__regists__result">123</p>
        </div>
    </div>
    <div class="column col-4 col-md-12 col-xs-12 mt-2">
        <div class="subscriber-statistics__element">
            <div class="subscriber-statistics__element__today"></div>
        </div>
        <div class="subscriber-statistics__element__regists">
            <p class="subscriber-statistics__element__regists__title">Melhor dia 
                <span class="e-goi-tooltip ">
                    <span class="dashicons dashicons-editor-help"></span>
                    <span class="e-goi-tooltiptext e-goi-tooltiptext--active">
                        Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy
                    </span>
                </span>
            </p>
            <p class="subscriber-statistics__element__regists__result">123</p>
            <p class="subscriber-statistics__element__regists__subscribers">Total de 23 Subscrições</p>
        </div>
    </div>
  </div>
</div>
