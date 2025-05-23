<?php

if ( ! defined( 'ABSPATH' ) ) {
	die();}

$lists = $this->smsnf_get_form_subscriber_total_by( 'list' );

$listsTitle = $this->getLists();

$listsObject = [];

foreach ($lists as $item1) {
    foreach ($listsTitle as $item2) {
        if ($item1->list_id == $item2['list_id']) {
            $listsObject[$item1->list_id] = $item2['internal_name'];
            break; 
        }
    }
}

$lists_chart  = $this->smsnf_get_form_subscribers_list( null, 12 );
$chart_months = '"' . implode( '","', $lists_chart['months'] ) . '"';

if ( ! isset( $this->options_list['list'] ) || $this->options_list['list'] == '' ) {
	$options_list = $lists[0]->list_id;
} else {
	$options_list = $this->options_list['list'];
}
require_once plugin_dir_path( __FILE__ ) . 'egoi-for-wp-common.php';

$last_subscribers = $this->smsnf_get_form_subscribers_last( 5 );

$forms = $this->smsnf_get_form_subscriber_total_by( 'form' );
$page  = array(
	'home' => ! isset( $_GET['subpage'] ),
);
?>

<!-- Wrap -->
<div class="smsnf">
	<div class="smsnf-modal-bg"></div>
	<!-- Header -->
	<header>
		<div class="wrapper-loader-egoi">
			<h1>Smart Marketing > <b><?php _e( 'Dashboard', 'egoi-for-wp' ); ?></b></h1>
			<?php echo getLoader( 'egoi-loader', false ); ?>
		</div>
		<nav>
			<ul>
				<li><a class="home <?php echo $page['home'] ? '-select' : ''; ?>" href="?page=egoi-4-wp-dashboard"><?php _e( 'General', 'egoi-for-wp' ); ?></a></li>
			</ul>
		</nav>
	</header>
	<!-- / Header -->
	<!-- Content -->
	<main style="grid-template-columns: 1fr !important;">
		<!-- Content -->
		<section class="smsnf-content">
			<div class="smsnf-dashboard">
				<div class="container">
					<div class="columns">
						<!-- Column Left Start -->
						<!-- Last Subscribers table / Subscribers by List / Subscribers by Form / Last Email Campaign /  Last SMS Campaign  -->
						<div class="column col-12 col-md-8 col-xs-8 mt-3">
							<div class="columns">
								<!-- Registrations made today -->
								<div class="column col-4 col-md-4 col-xs-4">
									<div class="smsnf-dashboard-subs-stats">
										<div class="smsnf-dashboard-subs-stats__content">
											<h3>
												<?php _e( 'Today\'s<br>subscribers', 'egoi-for-wp' ); ?>
												<button class="smsnf-dashboard-subs-stats__content--result btn">
													<?php echo $this->smsnf_get_form_susbcribers_total( 'today' )->total; ?>
												</button>
											</h3>
										</div>
										<div>
											<img src="<?php echo plugins_url( '../img/subscribers-today.png', __FILE__ ); ?>"/>
										</div>
									</div>
								</div>

								<!-- Total Subscribers -->
								<div class="column col-4 col-md-4 col-xs-4">
									<div class="smsnf-dashboard-subs-stats">
										<div class="smsnf-dashboard-subs-stats__content">
											<h3>
												<?php _e( 'Total<br>Subscribers', 'egoi-for-wp' ); ?>
												<?php $string = __( "Total subscribers registered\ndirectly in the plugin", 'egoi-for-wp' ); ?>
												<button class="smsnf-dashboard-subs-stats__content--result btn eg_tooltip" data-tooltip="<?php echo $string; ?>">
														<?php echo $this->smsnf_get_form_susbcribers_total( 'ever' )->total; ?>
												</button>
											</h3>
										</div>

										<div>
											<img src="<?php echo plugins_url( '../img/total-subscribers.png', __FILE__ ); ?>"/>
										</div>
									</div>
								</div>

								<!-- Best day -->
								<div class="column col-4 col-md-4 col-xs-4">
									<div class="smsnf-dashboard-subs-stats">
										<div class="smsnf-dashboard-subs-stats__content">
											<h3>
												<?php _e( 'Best<br>day', 'egoi-for-wp' ); ?>
												<?php $string = __( "Best day of subscribers registered\ndirectly in the plugin", 'egoi-for-wp' ); ?>
												<button class="smsnf-dashboard-subs-stats__content--result btn eg_tooltip" data-tooltip="<?php echo $string; ?>">
												<?php
													$best_day_data = $this->smsnf_get_form_subscribers_best_day();
													$best_day = isset($best_day_data->date) ? $best_day_data->date : null;
													echo $best_day ? esc_html( date( 'd M Y', strtotime( $best_day ) ) ) : '-';
												?>

												</button>
											</h3>
										</div>
										<div>
											<img src="<?php echo plugins_url( '../img/bestday.png', __FILE__ ); ?>"/>
										</div>
									</div>
								</div>
							</div>
							<!-- Last Subscribers table -->
							<div class="smsnf-dashboard-last-subscribers mt-3">
								<div class="smsnf-dashboard-last-subscribers__title">
									<?php _e( 'Latest Subscribers', 'egoi-for-wp' ); ?>
								</div>
								<div class="smsnf-dashboard-last-subscribers__empty <?php echo count( $last_subscribers ) > 0 ? 'd-none' : null; ?>">
									 <p><?php _e( 'You have no subscribers yet', 'egoi-for-wp' ); ?></p>
									 <div></div>
								</div>
								<table class="table <?php echo count( $last_subscribers ) == 0 ? 'd-none' : null; ?>">
									<thead>
										<tr>
											<th class="hide-xs"><?php _e( 'Email', 'egoi-for-wp' ); ?></th>
											<th class="hide-xs"><?php _e( 'Form ID', 'egoi-for-wp' ); ?></th>
											<th class="hide-xs"><?php _e( 'Form', 'egoi-for-wp' ); ?></th>
											<th class="hide-xs"><?php _e( 'Date', 'egoi-for-wp' ); ?></th>
											<th><?php _e( 'List', 'egoi-for-wp' ); ?></th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ( $last_subscribers as $subscriber ) { ?>
										<tr>
											<td class="hide-xs">
												<?php echo !empty($subscriber->subscriber_email) ? esc_html($subscriber->subscriber_email) : ''; ?>
											</td>
											<td class="hide-xs">
												<?php echo !empty($subscriber->form_id) ? esc_html($subscriber->form_id) : ''; ?>
											</td>
											<td class="hide-xs">
												<?php echo !empty($subscriber->form_title) ? esc_html($subscriber->form_title) : ''; ?>
											</td>
											<td class="hide-xs">
												<?php echo !empty($subscriber->created_at) ? esc_html(date('Y/m/d H\hi', strtotime($subscriber->created_at))) : ''; ?>
											</td>
											<td>
												<?php
												if (!empty($subscriber->list_id) && isset($listsObject[$subscriber->list_id])) {
													echo esc_html('(ID - ' . $subscriber->list_id . ') ' . $listsObject[$subscriber->list_id]);
												} else {
													echo esc_html('(ID - ' . ($subscriber->list_id ?? 'N/A') . ') Lista desconhecida');
												}
												?>
											</td>
										</tr>
									<?php } ?>
									</tbody>
								</table>
							</div>
							<div class="columns">
								<div class="column col-12 col-xl-6 col-xs-6">
									<!-- Subscribers by List -->
									<div class="smsnf-dashboard-subscribers-by-lists mt-3">
										<div class="smsnf-dashboard-subscribers-by-lists__title">
											<?php _e( 'Subscribers by Lists', 'egoi-for-wp' ); ?>
										</div>
										<div class="smsnf-dashboard-subscribers-by-lists__empty <?php echo count( $lists ) > 0 ? 'd-none' : null; ?>">
											<p><?php _e( 'You have no subscribers yet', 'egoi-for-wp' ); ?></p>
										</div>
										<div class="smsnf-dashboard-subscribers-by-lists__chart <?php echo count( $lists ) == 0 ? 'd-none' : null; ?>">
											<div class="smsnf-dashboard-subscribers-by-lists__content">

												<div>
													<!-- <p style="font-size: 11px; margin: 0;">Seleccione a Lista</p> -->
													<select id="chart_list">
													<?php foreach ( $lists as $list ) { 
														$list_id = $list->list_id;

														$value = isset($lists_chart[$list_id]['totals'])
															? esc_attr(implode(',', $lists_chart[$list_id]['totals']))
															: 'NaN';

														$label = isset($listsObject[$list_id])
															? esc_html('(ID - ' . $list_id . ') ' . $listsObject[$list_id])
															: esc_html('(ID - ' . $list_id . ') Lista desconhecida');
													?>
														<option value="<?php echo $value; ?>" <?php selected( $list_id, $options_list ); ?>>
															<?php echo $label; ?>
														</option>
													<?php } ?>

													</select>
												</div>

												<div>Total:
													<span class="smsnf-dashboard-subscribers-by-lists__content--total" id="list_subscribers_total">
													<?php
														$total = 0;

														if (!empty($lists) && is_array($lists)) {
															foreach ($lists as $list) {
																if (isset($list->list_id) && $list->list_id == $options_list) {
																	$total = isset($list->total) ? $list->total : 0;
																	break;
																}
															}

															// Se $total continuar 0, tenta usar o primeiro total, se existir
															if ($total == 0 && isset($lists[0]) && isset($lists[0]->total)) {
																$total = $lists[0]->total;
															}
														}

														echo esc_html($total);
													?>

													</span>
												</div>

											</div>
											<canvas id="smsnf-dsbl__lineChart" height="120"></canvas>
										</div>
									</div>
									<!-- Last SMS Campaign -->
									<div class="smsnf-dashboard-last-sms-campaign mt-3">
										<div class="smsnf-dashboard-last-sms-campaign__title">
											<?php _e( 'Last Sent SMS Campaign', 'egoi-for-wp' ); ?>
										</div>
										<div class="loading loading-lg" style="padding: 40px;" id="last_sms_campaign_loading"></div>
									</div>
								</div>
								<div class="column col-12 col-xl-6 col-xs-6">
									<!-- Subscribers by Form -->
									<div class="smsnf-dashboard-last-subscribers-by-form mt-3">
										<div class="smsnf-dashboard-last-subscribers-by-form__title">
											<?php _e( 'Subscribers by Form', 'egoi-for-wp' ); ?>
										</div>
										<div class="smsnf-dashboard-last-subscribers-by-form__empty <?php echo count( $forms ) > 0 ? 'd-none' : null; ?>">
											<p><?php _e( 'You have no subscribers yet', 'egoi-for-wp' ); ?></p>
										</div>
										<div class="smsnf-dashboard-last-subscribers-by-form__table <?php echo count( $forms ) == 0 ? 'd-none' : null; ?>">
											<table class="table">
												<tbody>
													<th><?php _e( 'Form ID', 'egoi-for-wp' ); ?></th>
													<th><?php _e( 'Form Name', 'egoi-for-wp' ); ?></th>
													<th><?php _e( 'Nº Subscribers', 'egoi-for-wp' ); ?></th>
												<?php foreach ( $forms as $form ) { ?>
													<tr>
														<td class="smsnf-dashboard-last-subscribers-by-form__table__ltd"><?php echo esc_html( $form->form_id ); ?></td>
														<td class="smsnf-dashboard-last-subscribers-by-form__table__ltd"><?php echo esc_html( $form->title ); ?></td>
														<td class="smsnf-dashboard-last-subscribers-by-form__table__rtd"><?php echo esc_html( $form->total ); ?></td>
													</tr>
												<?php } ?>
												</tbody>
											</table>
										</div>
									</div>
									<!-- Last Email Campaign -->
									<div class="smsnf-dashboard-last-email-campaign mt-3">
										<div class="smsnf-dashboard-last-email-campaign__title">
											<?php _e( 'Last Sent Email Campaign', 'egoi-for-wp' ); ?>
										</div>
										<div class="loading loading-lg" style="padding: 40px;" id="last_email_campaign_loading"></div>
									</div>
								</div>
							</div><!-- /columns -->
						</div> <!-- /col-8 -->

						<!-- Column Right Start -->
						<div class="column col-12 col-md-4 col-xs-4 mt-3">
							<!-- Account -->
							<div class="smsnf-dashboard-account">
								<!-- If the sms addon is active -->
								<div style="display: inherit;">
									<div class="smsnf-dashboard-account__title">
										<div><?php _e( 'Your account', 'egoi-for-wp' ); ?></div>
										<div class="smsnf-dashboard-account__title__cta">
											<a href="https://login.egoiapp.com/#/login/?action=login&from=%2F%3Faction%3Ddados_cliente&menu=sec" target="_blank"><?php _e( 'Update account information', 'egoi-for-wp' ); ?><span class="dashicons dashicons-external"></span></a>
										</div>
									</div>
									<div class="smsnf-dashboard-account__content p-0">
										<div class="smsnf-dashboard-account__content__table">
											<div class="loading loading-lg" style="padding: 40px;" id="account_content_loading"></div>
										</div>
									</div>
								</div>
							</div><!-- /Account -->

							<!-- Blog Post's -->
							<div class="smsnf-dashboard-blog-last-post mt-3">
								<div class="smsnf-dashboard-blog-last-post__title"><?php _e( 'Latest Blog Entries', 'egoi-for-wp' ); ?></div>
									<div class="loading loading-lg" style="padding: 40px;" id="blog_posts_content_loading"></div>
							</div><!-- /Blog Post's -->
						</div>
					</div><!-- / Columns -->
				</div><!-- / Container -->
			</div><!-- / Wrap -->
		</section>
	</main>
</div>
<!-- Total of subscribers Chart JS -->
<script>

	let listChartLabels = [<?php echo $chart_months; ?>];
	let listChartData = [<?php echo isset($lists_chart[ $options_list ]) ? implode( ',', $lists_chart[ $options_list ]['totals']) : 0 ?>];

	myListChartParams = {
		type: 'bar',
		data: {
			labels: listChartLabels,
			datasets: [
				{
					label: "Nº de Subscritores",
					backgroundColor: [
						"rgba(0, 174, 218, 0.4)",
						"rgba(0, 174, 218, 0.4)",
						"rgba(0, 174, 218, 0.4)",
						"rgba(0, 174, 218, 0.4)",
						"rgba(0, 174, 218, 0.4)",
						"rgba(0, 174, 218, 0.4)",
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
						"rgba(0, 174, 218, 0.5)",
						"rgba(0, 174, 218, 0.5)",
						"rgba(0, 174, 218, 0.5)",
						"rgba(0, 174, 218, 0.5)",
						"rgba(0, 174, 218, 0.5)",
						"rgba(0, 174, 218, 0.5)",
						"rgba(0, 174, 218, 0.5)"
					],
					data: listChartData,
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
				y:{
					ticks: {
						userCallback: function(label, index, labels) {
							if (Math.floor(label) === label) {
							return label;
							}
						},
					}
				},
				x: {
					barPercentage: 1,
					categoryPercentage: 1
				}
			}
		}
	};

	var ctx = document.getElementById('smsnf-dsbl__lineChart');
	var myListChart = new Chart(ctx, myListChartParams);

	var list = document.getElementById("chart_list");
	list.addEventListener("change", changeChartData);

	function changeChartData() {
		var data = list.value.split(",");
		myListChartParams.data.datasets[0].data = data;

		var sum = 0;
		data.forEach(function (e) {
			sum = sum + parseInt(e);
		});

		myListChart.update(1000);
		document.getElementById("list_subscribers_total").innerHTML = sum;
	}
</script>





