<?php if ( ! defined( 'ABSPATH' ) ) { die();} ?>
<!-- Header -->
<div class="container">
  <div class="columns">
    <div class="column">
        <div class="smsnf-header">
            <span class="smsnf-header__logo"></span>
            <h1>Smart Marketing - <?php _e( 'Dashboard', 'egoi-for-wp' ); ?></h1>
        </div>
        <div class="smsnf-header__breadcrumbs">
            <span class="prefix">
                <?php echo __( 'You are here: ', 'egoi-for-wp' ); ?>
            </span>    
                <strong>Smart Marketing</a> &rsaquo;
                    <a href="#">
                        <span class="current-crumb">
                        <?php _e( 'Dashboard', 'egoi-for-wp' ); ?>
                    </a>
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

            <!-- Notifications | Upgrade Account -->
            <div class="column col-12">
                <div class="smsnf-dashboard-notifications notice is-dismissible">
                    <div class="smsnf-dashboard-notifications__img">
                        <figure class="avatar avatar-xl smsnf-dashboard-notifications__img--upgrade"></figure>
                    </div>
                    <div class="smsnf-dashboard-notifications__copy">
                        <h3>Upgrade da Conta</h3>
                        <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt</p>
                    </div>
                    <div class="smsnf-dashboard-notifications__btn">
                        <a class="hide-sm hide-xs" type="button" id=""> 
						    <?php echo __('Hide notification', 'egoi-for-wp');?>
						</a>
                        <a type="button" id="" class="button-smsnf-primary"> 
						    <?php echo __('Upgrade', 'egoi-for-wp');?>
						</a>
                    </div>
                </div>
            </div>
            
            <!-- Notifications | Account -->
            <div class="column col-12">
                <div class="smsnf-dashboard-notifications notice is-dismissible">
                    <div class="smsnf-dashboard-notifications__img">
                        <figure class="avatar avatar-xl smsnf-dashboard-notifications__img--limit"></figure>
                    </div>
                    <div class="smsnf-dashboard-notifications__copy">
                        <h3>Já atingiu 80% do seu saldo disponível</h3>
                        <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt</p>
                    </div>
                    <div class="smsnf-dashboard-notifications__btn">
                        <a class="hide-sm hide-xs" type="button" id=""> 
						    <?php echo __('Hide notification', 'egoi-for-wp');?>
						</a>
                        <a type="button" id="" class="button-smsnf-primary"> 
						    <?php echo __('Upgrade', 'egoi-for-wp');?>
						</a>
                    </div>
                </div>
            </div>

            <!-- Column Left Start -->
            <!-- Last Subscribers table / Subscribers by List / Account / Subscribers by Form / Last Email Campaign -->
            <div class="column col-8 col-md-12 col-xs-12 mt-3">
                <div class="columns">
                    <!-- Registrations made today -->
                    <div class="column col-4 col-md-12 col-xs-12">
                        <div class="smsnf-dashboard-subs-stats">
                            <div class="smsnf-dashboard-subs-stats__icon--today"><!-- Icon --></div>
                            <div class="smsnf-dashboard-subs-stats__content">
                                <h3>Registos de hoje</h3>
                                <span class="smsnf-dashboard-subs-stats__content--result1">123</span>
                            </div>
                        </div>
                    </div>

                    <!-- Total Subscribers -->
                    <div class="column col-4 col-md-12 col-xs-12">
                        <div class="smsnf-dashboard-subs-stats">
                            <div class="smsnf-dashboard-subs-stats__icon--total"><!-- Icon --></div>
                            <div class="smsnf-dashboard-subs-stats__content">
                                <h3>Total de Subscritores 
                                    <span class="e-goi-tooltip">
                                        <span class="dashicons dashicons-editor-help"></span>
                                        <span class="e-goi-tooltiptext e-goi-tooltiptext--active">
                                            Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy
                                        </span>
                                    </span>
                                </h3>
                                <span class="smsnf-dashboard-subs-stats__content--result2">12312</span>
                            </div>
                        </div>
                    </div>

                    <!-- Best day -->
                    <div class="column col-4 col-md-12 col-xs-12">
                        <div class="smsnf-dashboard-subs-stats">
                            <div class="smsnf-dashboard-subs-stats__icon--bestday"><!-- Icon --></div>
                            <div class="smsnf-dashboard-subs-stats__content">
                                <h3 class="d-inline-block">Melhor dia 
                                    <span class="e-goi-tooltip">
                                        <span class="dashicons dashicons-editor-help"></span>
                                        <span class="e-goi-tooltiptext e-goi-tooltiptext--active">
                                            Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy
                                        </span>
                                    </span>
                                </h3>
                                <!-- <p>Total de 23 Subscrições</p>  -->
                                <span class="smsnf-dashboard-subs-stats__content--result3">8 Ago 2018</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Last Subscribers table -->
                <div class="smsnf-dashboard-last-subscribers mt-3">
                    <div class="smsnf-dashboard-last-subscribers__title">
                        Últimos 5 subscritores
                    </div>
                    <div class="smsnf-dashboard-last-subscribers__empty">
                         <p>Ainda não tem subscritores</p>
                         <div></div>
                    </div>
                    <table class="table d-none">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th class="hide-xs">Email</th>
                                <th>Formulário</th>
                                <th class="hide-xs">Data</th>
                                <th>Lista</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Maria Almeida</td>
                                <td class="hide-xs">mariaalmeida@e-goi.com</td>
                                <td>Captação de Leads</td>
                                <td class="hide-xs">2018/12/20 12h00</td>
                                <td>Marketing</td>
                            </tr>
                            <tr>
                                <td>Maria Almeida</td>
                                <td class="hide-xs">mariaalmeida@e-goi.com</td>
                                <td>Captação de Leads</td>
                                <td class="hide-xs">2018/12/20 12h00</td>
                                <td>Marketing</td>
                            </tr>
                            <tr>
                                <td>Maria Almeida</td>
                                <td class="hide-xs">mariaalmeida@e-goi.com</td>
                                <td>Captação de Leads</td>
                                <td class="hide-xs">2018/12/20 12h00</td>
                                <td>Marketing</td>
                            </tr>
                            <tr>
                                <td>Maria Almeida</td>
                                <td class="hide-xs">mariaalmeida@e-goi.com</td>
                                <td>Captação de Leads</td>
                                <td class="hide-xs">2018/12/20 12h00</td>
                                <td>Marketing</td>
                            </tr>
                            <tr>
                                <td>Maria Almeida</td>
                                <td class="hide-xs">mariaalmeida@e-goi.com</td>
                                <td>Captação de Leads</td>
                                <td class="hide-xs">2018/12/20 12h00</td>
                                <td>Marketing</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="columns">
                    <div class="column col-6 col-xl-12 col-xs-12">
                        <!-- Subscribers by List -->
                        <div class="smsnf-dashboard-subscribers-by-lists mt-3">
                            <div class="smsnf-dashboard-subscribers-by-lists__title">
                                Subscritores por Listas
                            </div>
                            <div class="smsnf-dashboard-subscribers-by-lists__empty">
                                <p>Ainda não tem registos</p>
                            </div>
                            <div class="smsnf-dashboard-subscribers-by-lists__chart d-none">
                                <div class="smsnf-dashboard-subscribers-by-lists__content">
                                    <p>Total 
                                        <span class="hide-xs hide-md">de Subscritores:</span>
                                        <span>1223</span>
                                    </p>
                                    <div>
                                        <select>
                                            <option value="Lista A">Lista A</option>
                                            <option value="Lista B">Lista B</option>
                                            <option value="Lista C">Lista C</option>
                                        </select>
                                    </div>
                                </div>
                                <canvas id="smsnf-dsbl__lineChart" height="120"></canvas>
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
                                        <td class="smsnf-dashboard-last-sms-campaign__totalsend">321</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="smsnf-dashboard-last-sms-campaign__chart">
                                <canvas id="smsnf-dlsc__doughnutChart" height="120"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="column col-6 col-xl-12 col-xs-12">
                        <!-- Subscribers by Form -->
                        <div class="smsnf-dashboard-last-subscribers-by-form mt-3">
                            <div class="smsnf-dashboard-last-subscribers-by-form__title">
                                Subscritores por Formulário
                            </div>
                            <div class="smsnf-dashboard-last-subscribers-by-form__empty">
                                <p>Ainda não tem registos</p>
                            </div>
                            <div class="smsnf-dashboard-last-subscribers-by-form__table d-none">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td class="smsnf-dashboard-last-subscribers-by-form__table__ltd">Formulário ABCFGHHGJ</td>
                                            <td class="smsnf-dashboard-last-subscribers-by-form__table__rtd">56756743</td>
                                        </tr>
                                        <tr>
                                            <td class="smsnf-dashboard-last-subscribers-by-form__table__ltd">Formulário SDFA</td>
                                            <td class="smsnf-dashboard-last-subscribers-by-form__table__rtd">1231231</td>
                                        </tr>
                                        <tr>
                                            <td class="smsnf-dashboard-last-subscribers-by-form__table__ltd">Formulário TRY</td>
                                            <td class="smsnf-dashboard-last-subscribers-by-form__table__rtd">345345</td>
                                        </tr>
                                        <tr>
                                            <td class="smsnf-dashboard-last-subscribers-by-form__table__ltd">Formulário WERW</td>
                                            <td class="smsnf-dashboard-last-subscribers-by-form__table__rtd">123123</td>
                                        </tr>
                                        <tr>
                                            <td class="smsnf-dashboard-last-subscribers-by-form__table__ltd">Formulário WERW</td>
                                            <td class="smsnf-dashboard-last-subscribers-by-form__table__rtd">12321</td>
                                        </tr>
                                        <tr>
                                            <td class="smsnf-dashboard-last-subscribers-by-form__table__ltd">Formulário WERW</td>
                                            <td class="smsnf-dashboard-last-subscribers-by-form__table__rtd">123123</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
                                        <td class="smsnf-dashboard-last-email-campaign__totalsend">2312</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="smsnf-dashboard-last-email-campaign__chart">
                                <canvas id="smsnf-dlec__doughnutChart" height="120"></canvas>
                            </div>
                        </div>
                    </div>
                </div><!-- /columns -->
            </div> <!-- /col-8 -->

            <!-- Column Right Start -->
            <div class="column col-4 col-md-12 col-xs-12 mt-3">
                <!-- Account -->
                <div class="smsnf-dashboard-account">
                    <!-- If the sms addon is active -->
                    <div style="display: inherit;">
                        <div class="smsnf-dashboard-account__title">
                            <div>A sua conta</div>
                            <div class="smsnf-dashboard-account__title__cta">
                                <a href="#" target="_blank">Atualizar dados da conta<span class="dashicons dashicons-external"></span></a>
                            </div>
                        </div>
                        <div class="smsnf-dashboard-account__content p-0">
                            <div class="smsnf-dashboard-account__content__table">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td><span class="smsnf-dashboard-account__content__table--total">Saldo Atual</span></td>
                                            <td><span class="smsnf-dashboard-account__content__table--cash">201</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <hr>
                                <p class="smsnf-dashboard-account__content__table--subtitle">O seu plano atual inclui</p>
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>Email</td>
                                            <td><span class="">2120</span></td>
                                        </tr>
                                        <tr>
                                            <td>SMS</td>
                                            <td><span class="">123</span></td>
                                        </tr>
                                        <tr>
                                            <td>Push</td>
                                            <td><span class="">29</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <hr>
                                <p class="smsnf-dashboard-account__content__table--subtitle">Total de envios efetuados</p>
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>Email</td>
                                            <td><span class="">20121</span></td>
                                        </tr>
                                        <tr>
                                            <td>Push</td>
                                            <td><span class="">123</span></td>
                                        </tr>
                                        <tr>
                                            <td>SMS Transacional</td>
                                            <td><span class="">29</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
                    </div>
                    <!-- If sms addon is NOT active -->
                    <div style="display: none;">
                        <div class="smsnf-dashboard-account__title">
                            <div>A sua conta</div>
                        </div>
                        <div class="smsnf-dashboard-account__content">
                            <div class="smsnf-dashboard-account__install-addon"></div>
                            <p class="smsnf-dashboard-account__install-addon__text">Envie notificações SMS aos seus clientes e 
                            administradores por cada alteração ao estado da encomenda no
                            seu wooCommerce</p>
                            <a type="button" id="" class="button-smsnf-primary"> 
                                <?php echo __('Install', 'egoi-for-wp');?>
                            </a>
                        </div>
                    </div>
                </div><!-- /Account -->

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
                        <a href="">
                            <p class="smsnf-dashboard-blog-last-post__content__description">
                                Será que o seu negócio está atento às tendências do marketing digital para inovar e sair na frente da concorrência? Com o início do ano que se aproxima...
                            </p>
                        </a>
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
                        <a href="">
                            <p class="smsnf-dashboard-blog-last-post__content__description">
                                Será que o seu negócio está atento às tendências do marketing digital para inovar e sair na frente da concorrência? Com o início do ano que se aproxima...
                            </p>
                        </a>
                    </div>
                </div><!-- /Blog Post's -->
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
                backgroundColor: [
                    "rgba(0, 174, 218, 0.4)", 
                    "rgba(0, 174, 218, 0.4)",
                    "rgba(0, 174, 218, 0.4)",
                    "rgba(0, 174, 218, 0.4)",
                    "rgba(0, 174, 218, 0.4)",
                    "rgba(0, 174, 218, 0.4)"
                ],
                hoverBackgroundColor: [
                    "rgba(0, 174, 218, 0.5)", 
                    "rgba(0, 174, 218, 0.5)",
                    "rgba(0, 174, 218, 0.5)",
                    "rgba(0, 174, 218, 0.5)",
                    "rgba(0, 174, 218, 0.5)",
                    "rgba(0, 174, 218, 0.5)"
                ],
                data: [300,800,600,200,120,150],
                borderWidth: 0,
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
                data: [12, 19, 3, 5, 2],
                backgroundColor: [
                    'rgba(0, 174, 218, 0.4)',
                    'rgba(147, 189, 77, 0.3)',
                    'rgba(246, 116, 73, 0.3)',
                    'rgba(250, 70, 19, 0.4)',
                    'rgba(237, 60, 47, 0.6)'
                ],
                borderColor: [
                    'rgba(0, 174, 218, 0.5)',
                    'rgba(147, 189, 77, 0.4)',
                    'rgba(246, 116, 73, 0.4)',
                    'rgba(242, 91, 41, 0.5)',
                    'rgba(237, 60, 47, 0.7)'
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
                data: [1020, 100],
                backgroundColor: [
                    'rgba(147, 189, 77, 0.3)',
                    'rgba(250, 70, 19, 0.4)'
                ],
                borderColor: [
                    'rgba(147, 189, 77, 0.4)',
                    'rgba(250, 70, 19, 0.5)'
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





