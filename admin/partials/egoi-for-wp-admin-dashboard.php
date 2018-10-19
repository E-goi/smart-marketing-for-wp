<?php
if ( ! defined( 'ABSPATH' ) ) {
    die();
}
?>

<!-- Header -->
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
</div><!-- /header -->

<!-- Wrap -->
<div class="sm-dashboard">
    <!-- Notifications -->
    <div class="container">
        <div class="columns">
            <div class="column">
                <div class="sm-dashboard-notifications notice is-dismissible">
                    <div class="sm-dashboard-notifications__img">
                        <figure class="avatar avatar-xl sm-dashboard-notifications__img--upgrade"></figure>
                    </div>
                    <div class="sm-dashboard-notifications__copy">
                        <h3 class="text-dark">Upgrade da Conta</h3>
                        <p class="text-dark">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Notifications -->

    <!-- Global title -->
    <div class="container">
        <div class="columns">
            <div class="column">
                <h1 class="sm-dashboard-maintitle">Estatísticas dos subscritores</h1>
            </div>
        </div>
    </div>

    <!-- Registrations made today -->
    <div class="container">
        <div class="columns sm-dashboard-subs-stats">


            <div class="column col-4 col-md-12 col-xs-12 mt-2">

                <div class="sm-dashboard-subs-stats--today">
                    <div class="sm-dashboard-subs-stats__element">
                        <div class="sm-dashboard-subs-stats__element__today"></div>
                    </div>
                    <div class="sm-dashboard-subs-stats__element__regists">
                        <p class="sm-dashboard-subs-stats__element__regists__title">Registos efetuados hoje</p>
                        <p class="sm-dashboard-subs-stats__element__regists__result">123</p>
                    </div>
                </div>

            </div>


            <!-- <div class="column col-4 col-md-12 col-xs-12 mt-2">

                <div class="sm-dashboard-subs-stats--total">
                    <div class="sm-dashboard-subs-stats__element">
                        <div class="sm-dashboard-subs-stats__element__today">
                    </div>
                    <div class="sm-dashboard-subs-stats__element__regists">
                        <p class="sm-dashboard-subs-stats__element__regists__title">Total de Subscritores</p>
                        <p class="sm-dashboard-subs-stats__element__regists__result">123</p>
                    </div>
                </div>

            </div> -->

            <!-- <div class="column col-4 col-md-12 col-xs-12 mt-2">

                <div class="sm-dashboard-subs-stats--total">
                    <div class="sm-dashboard-subs-stats__element">
                        <div class="sm-dashboard-subs-stats__element__today">
                    </div>
                    <div class="sm-dashboard-subs-stats__element__regists">
                        <p class="sm-dashboard-subs-stats__element__regists__title">Total de Subscritores</p>
                        <p class="sm-dashboard-subs-stats__element__regists__result">123</p>
                    </div>
                </div>

            </div> -->


            <!-- <div class="column col-4 col-md-12 col-xs-12 mt-2">

                <div class="sm-dashboard-subs-stats--bestday">

                    <div class="sm-dashboard-subs-stats__element">
                        <div class="sm-dashboard-subs-stats__element__today">
                    </div>
                    <div class="sm-dashboard-subs-stats__element__regists">
                        <p class="sm-dashboard-subs-stats__element__regists__title">Melhor dia 
                            <span class="e-goi-tooltip">
                                <span class="dashicons dashicons-editor-help"></span>
                                <span class="e-goi-tooltiptext e-goi-tooltiptext--active">
                                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy
                                </span>
                            </span>
                        </p>
                        <p class="sm-dashboard-subs-stats__element__regists__result">123</p>
                        <p class="sm-dashboard-subs-stats__element__regists__subscribers">Total de 23 Subscrições</p>
                    </div>
                </div>

            </div> -->


        </div>
    </div>
    <!-- /Registrations Made Today -->

    <div class="container">
        <div class="columns subscriber-form mt-3">
            <div class="column col-8 col-md-12 col-xs-12">
                <!-- Last 5 subscribers -->
                <div class="subscriber-form__element">
                    <div class="subscriber-form__element__title">
                        Últimos 5 subscritores
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Formulário</th>
                                <th>Lista</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="active">
                                <td>Maria Almeida</td>
                                <td>mariaalmeida@e-goi.com</td>
                                <td>Captação de Leads</td>
                                <td>Marketing</td>
                            </tr>
                            <tr>
                                <td>Maria Almeida</td>
                                <td>mariaalmeida@e-goi.com</td>
                                <td>Captação de Leads</td>
                                <td>Marketing</td>
                            </tr>
                            <tr class="active">
                                <td>Maria Almeida</td>
                                <td>mariaalmeida@e-goi.com</td>
                                <td>Captação de Leads</td>
                                <td>Marketing</td>
                            </tr>
                            <tr>
                                <td>Maria Almeida</td>
                                <td>mariaalmeida@e-goi.com</td>
                                <td>Captação de Leads</td>
                                <td>Marketing</td>
                            </tr>
                            <tr class="active">
                                <td>Maria Almeida</td>
                                <td>mariaalmeida@e-goi.com</td>
                                <td>Captação de Leads</td>
                                <td>Marketing</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /Last 5 subscribers --> 
                
                <!-- Subscribers by List -->
                <div class="mt-3">
                    <div class="subscriber-form__element__title">
                        Subscritores por Listas
                    </div>
                    <!-- Start Chart JS -->
                    <div class="canvas-container">
                        <div class="subscribers-by-list">
                            <p>Total de Subscritores: <span>1223</span></p>
                            <div class="flex-item">
                                <select>
                                    <option value="-- Select the list --">-- Select the list --</option>
                                    <option value="Lista A">Lista A</option>
                                    <option value="Lista B">Lista B</option>
                                    <option value="Lista C">Lista C</option>
                                </select>
                            </div>
                        </div>
                        <canvas id="lineChart" height="120"></canvas>
                    </div>
                </div>
                <!-- End Char JS -->
            </div>
            
            <div class="column col-4 col-md-12 col-xs-12">
                <!-- Subscribers by Form -->
                <div class="subscriber-form__element">
                    <div class="subscriber-form__element__title">Subscritores por Formulário</div>
                </div>
                <table class="table subscriber-form-table">
                    <tbody>
                        <tr class="active">
                            <td>Formulário ABC</td>
                            <td>56756743</td>
                        </tr>
                        <tr>
                            <td>Formulário SDFA</td>
                            <td>1231231</td>
                        </tr>
                        <tr class="active">
                            <td>Formulário TRY</td>
                            <td>345345</td>
                        </tr>
                        <tr>
                            <td>Formulário WERW</td>
                            <td>123123</td>
                        </tr>
                        <tr class="active">
                            <td>Formulário WERW</td>
                            <td>12321</td>
                        </tr>
                        <tr>
                            <td>Formulário WERW</td>
                            <td>123123</td>
                        </tr>
                        <tr class="active">
                            <td>Formulário WERW</td>
                            <td>12321</td>
                        </tr>
                        <tr>
                            <td>Formulário WERW</td>
                            <td>123123</td>
                        </tr>
                        <tr class="active">
                            <td>Formulário WERW</td>
                            <td>12321</td>
                        </tr>
                    </tbody>
                </table>
                <!-- /Subscribers by Form -->
                
                <!-- Blog Post's -->
                <div class="blog-last-post mt-3">
                    <div class="blog-last-post__element">
                        <div class="blog-last-post__element__title">Últimos Post's do Blog</div>
                    </div>
                    <div class="blog-last-post__content">
                        <div>
                            <div>21-12-2018</div>
                            <a href=""><small>CAPTAR</small></a>
                        </div>
                        <a href="">
                            <h4 class="blog-last-post__content__title">
                            Conheça as 4 principais tendências de marketing digital para 2019
                            </h4>
                        </a>
                        <p class="blog-last-post__content__description">
                            Será que o seu negócio está atento às tendências do marketing digital para inovar e sair na frente da concorrência? Com o início do ano que se aproxima...
                        </p>
                        <hr>
                    </div>
                    <div class="blog-last-post__content">
                        <div>
                            <div>21-12-2018</div>
                            <a href=""><small>CAPTAR</small></a>
                        </div>
                        <a href="">
                            <h4 class="blog-last-post__content__title">
                            Conheça as 4 principais tendências de marketing digital para 2019
                            </h4>
                        </a>
                        <p class="blog-last-post__content__description">
                            Será que o seu negócio está atento às tendências do marketing digital para inovar e sair na frente da concorrência? Com o início do ano que se aproxima...
                        </p>
                    </div>
                </div>
                <!-- Blog Post's -->
            </div>
        </div>
    </div>
</div><!-- / Wrap -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
<script>
    const CHART = document.getElementById("lineChart");
    console.log(CHART);

    let lineChart = new Chart(CHART, {
        type: 'bar',
        data: {
            labels: ["Jul", "Ago", "Set", "Out", "Nov", "Dez"],
            datasets: [
                {
                label: "Nº de Subscritores",
                backgroundColor: ["#00aeea", "#19b6ec","#32beee","#4cc6f0","#66cef2", "#7fd6f4"],
                hoverBackgroundColor: ["#009cd2", "#009cd2","#009cd2","#009cd2","#009cd2","#009cd2"],
                data: [300,800,600,200,120,150],
                borderWidth: 2,
                hoverBorderWidth: 0
                }
            ]
            },
            options: {
            legend: { 
                display: false 
            },
            title: {
                fontColor: 'blue',
                fontFamily: "Helvetica, Arial, serif",
                fontSize: 16,
                position: "top",
                beginAtZero: true,
                min: 20,
                text: 'Nº total de subscritores',
                display: false,
            },
            scales: {
                yAxes:[{
                    ticks:{
                        fontSize: 12,
                        padding: 20,
                        beginAtZero: true,
                    }
                }],
                xAxes: [{
                    barPercentage: 1,
                    categoryPercentage: 1,
                    ticks: {
                        autoSkip: false,
                        beginAtZero: true,
                        maxRotation: 0,
                        minRotation: 0,
                        padding: 10,
                        fontFamily: "Helvetica, Arial, serif",
                        fontColor: "#aaaaaa",
                    }
                }]
            }
        }
    });
</script>
