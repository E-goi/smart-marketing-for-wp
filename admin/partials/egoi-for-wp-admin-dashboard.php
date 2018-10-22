<?php if ( ! defined( 'ABSPATH' ) ) { die();} ?>
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
<div class="smsnf-dashboard">
    <div class="container">
        <div class="columns">

            <!-- Notifications -->
            <div class="column col-12">
                <div class="smsnf-dashboard-notifications notice is-dismissible">
                    <div class="smsnf-dashboard-notifications__img">
                        <figure class="avatar avatar-xl smsnf-dashboard-notifications__img--upgrade"></figure>
                    </div>
                    <div class="smsnf-dashboard-notifications__copy">
                        <h3>Upgrade da Conta</h3>
                        <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt</p>
                    </div>
                </div>
            </div>

            <!-- Global title -->
            <div class="column col-12">
                <h2 class="smsnf-dashboard-maintitle">Estatísticas dos subscritores</h2>
            </div>

            <!-- Registrations made today -->
            <div class="column col-4 col-md-12 col-xs-12">
                <div class="smsnf-dashboard-subs-stats">
                    <div class="smsnf-dashboard-subs-stats__icon--today"><!-- Icon --></div>
                    <div class="smsnf-dashboard-subs-stats__content">
                        <h3>Registos efetuados hoje</h3>
                        <span class="smsnf-dashboard-subs-stats__content--result">123</span>
                    </div>
                </div>
            </div>

            <!-- Total Subscribers -->
            <div class="column col-4 col-md-12 col-xs-12">
                <div class="smsnf-dashboard-subs-stats">
                    <div class="smsnf-dashboard-subs-stats__icon--total"><!-- Icon --></div>
                    <div class="smsnf-dashboard-subs-stats__content">
                        <h3>Registos efetuados hoje</h3>
                        <span class="smsnf-dashboard-subs-stats__content--result">123</span>
                    </div>
                </div>
            </div>

            <!-- Best day -->
            <div class="column col-4 col-md-12 col-xs-12">
                <div class="smsnf-dashboard-subs-stats">
                    <div class="smsnf-dashboard-subs-stats__icon--bestday"><!-- Icon --></div>
                    <div class="smsnf-dashboard-subs-stats__content">
                        <h3 class="d-inline-block">Melhor dia</h3>
                            <span class="e-goi-tooltip">
                                <span class="dashicons dashicons-editor-help"></span>
                                <span class="e-goi-tooltiptext e-goi-tooltiptext--active">
                                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy
                                </span>
                            </span>
                         <span class="smsnf-dashboard-subs-stats__content--result">8 de Agosto de 2018</span>
                         <p>Total de 23 Subscrições</p> 
                    </div>
                </div>
            </div>

            <!-- Last Subscribers table / Subscribers by List / Last Email Campaign -->
            <div class="column col-8 col-md-12 col-xs-12">
                <!-- Last Subscribers table -->
                <div class="smsnf-dashboard-last-subscribers mt-3">
                    <div class="smsnf-dashboard-last-subscribers__title">
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
                <!-- Subscribers by List -->
                <div class="smsnf-dashboard-subscribers-by-lists mt-3">
                    <div class="smsnf-dashboard-subscribers-by-lists__title">
                        Subscritores por Listas
                    </div>
                    <div class="smsnf-dashboard-subscribers-by-lists__chart">
                        <div class="smsnf-dashboard-subscribers-by-lists__content">
                            <p>Total de Subscritores: <span>1223</span></p>
                            <div>
                                <select>
                                    <option value="-- Select the list --">-- Select the list --</option>
                                    <option value="Lista A">Lista A</option>
                                    <option value="Lista B">Lista B</option>
                                    <option value="Lista C">Lista C</option>
                                </select>
                            </div>
                        </div>
                        <canvas id="smsnf-dsbl__lineChart" height="120"></canvas>
                    </div>
                </div>
                
                <div class="columns">
                    <div class="column col-6 col-ml-auto col-xs-12 col-md-12">
                        <!-- Last Email Campaign --> 
                        <div class="smsnf-dashboard-last-email-campaign mt-3">
                            <div class="smsnf-dashboard-last-email-campaign__title">
                                Última campanha de Email Enviada
                            </div>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>Nome</td>
                                        <td>Curso de HTML Avançado</td>
                                    </tr>
                                    <tr>
                                        <td>ID</td>
                                        <td>20192</td>
                                    </tr>
                                    <tr>
                                        <td>Total de Envios</td>
                                        <td>2312</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="smsnf-dashboard-last-email-campaign__chart">
                                <canvas id="smsnf-dlec__doughnutChart" height="120"></canvas>
                            </div>
                        </div>
                        <!-- Last SMS Campaign --> 
                        <div class="smsnf-dashboard-last-sms-campaign mt-3">
                            <div class="smsnf-dashboard-last-sms-campaign__title">
                                Última campanha de SMS Enviada
                            </div>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>Nome</td>
                                        <td>Curso de HTML Avançado</td>
                                    </tr>
                                    <tr>
                                        <td>ID</td>
                                        <td>1231</td>
                                    </tr>
                                    <tr>
                                        <td>Total de Envios</td>
                                        <td>321</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="smsnf-dashboard-last-sms-campaign__chart">
                                <canvas id="smsnf-dlsc__doughnutChart" height="120"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Subscribers by Form and Blog Post's -->
            <div class="column col-4 col-md-12 col-xs-12">
                <!-- Subscribers by Form -->
                <div class="smsnf-dashboard-last-subscribers-by-form mt-3">
                    <div class="smsnf-dashboard-last-subscribers-by-form__title">
                        Subscritores por Formulário
                    </div>
                    <div class="smsnf-dashboard-last-subscribers-by-form__table">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Formulário ABC</td>
                                    <td>56756743</td>
                                </tr>
                                <tr>
                                    <td>Formulário SDFA</td>
                                    <td>1231231</td>
                                </tr>
                                <tr>
                                    <td>Formulário TRY</td>
                                    <td>345345</td>
                                </tr>
                                <tr>
                                    <td>Formulário WERW</td>
                                    <td>123123</td>
                                </tr>
                                <tr>
                                    <td>Formulário WERW</td>
                                    <td>12321</td>
                                </tr>
                                <tr>
                                    <td>Formulário WERW</td>
                                    <td>123123</td>
                                </tr>
                                <tr>
                                    <td>Formulário WERW</td>
                                    <td>12321</td>
                                </tr>
                                <tr>
                                    <td>Formulário WERW</td>
                                    <td>123123</td>
                                </tr>
                                <tr>
                                    <td>Formulário WERW</td>
                                    <td>12321</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Blog Post's -->
                <div class="smsnf-dashboard-blog-last-post mt-3">
                    <div class="smsnf-dashboard-blog-last-post__title">Últimos Post's do Blog</div>
                    <div class="smsnf-dashboard-blog-last-post__content">
                        <div>
                            <div>21-12-2018</div>
                            <a href=""><small>CAPTAR</small></a>
                        </div>
                        <a href="">
                            <h4 class="smsnf-dashboard-blog-last-post__content__title">
                            Conheça as 4 principais tendências de marketing digital para 2019
                            </h4>
                        </a>
                        <p class="smsnf-dashboard-blog-last-post__content__description">
                            Será que o seu negócio está atento às tendências do marketing digital para inovar e sair na frente da concorrência? Com o início do ano que se aproxima...
                        </p>
                        <hr>
                    </div>
                    <div class="smsnf-dashboard-blog-last-post__content">
                        <div>
                            <div>21-12-2018</div>
                            <a href=""><small>CAPTAR</small></a>
                        </div>
                        <a href="">
                            <h4 class="smsnf-dashboard-blog-last-post__content__title">
                            Conheça as 4 principais tendências de marketing digital para 2019
                            </h4>
                        </a>
                        <p class="smsnf-dashboard-blog-last-post__content__description">
                            Será que o seu negócio está atento às tendências do marketing digital para inovar e sair na frente da concorrência? Com o início do ano que se aproxima...
                        </p>
                    </div>
                </div>
            </div>
            
        </div><!-- / Columns -->
    </div><!-- / Container -->
</div><!-- / Wrap -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>

<!-- Total of subscribers Chart JS -->
<script>
    const CHART = document.getElementById("smsnf-dsbl__lineChart");
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

<!-- Last Campaign Email Chart JS -->
<script>
    Chart.defaults.global.legend.labels.usePointStyle = true;
    var ctx = document.getElementById("smsnf-dlec__doughnutChart").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ["Abertura", "Cliques", "Bounces", "Remoções", "Queixas"],
            datasets: [{
                label: '# of Votes',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            legend: {
                display: true,
                position: 'right',
                labels: {
                    fontColor: '#333',
                }
            },
            layout: {
                padding: {
                    left: 0,
                    right: 0,
                    top: 0,
                    bottom: 0
                }
            }
        }
    });
</script>

<!-- Last Campaign SMS Chart JS -->
<script>
    Chart.defaults.global.legend.labels.usePointStyle = true;
    var ctx = document.getElementById("smsnf-dlsc__doughnutChart").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ["Entregues", "Não Entregues"],
            datasets: [{
                label: '# of Votes',
                data: [12, 19],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            legend: {
                display: true,
                position: 'right',
                labels: {
                    fontColor: '#333',
                }
            },
            layout: {
                padding: {
                    left: 0,
                    right: 0,
                    top: 0,
                    bottom: 0
                }
            }
        }
    });
</script>





