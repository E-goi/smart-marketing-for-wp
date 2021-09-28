<?php
/**
 * email footer
 *
 * @version 1.0
 * @since 1.4
 * @package WordPress Social Invitations
 * @author Timersys
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$border_radius   = $settings['template'] == 'boxed' ? '6px' : '0px';
$template_footer = '
	border-top:1px solid #E2E2E2;
	background: ' . $settings['footer_bg'] . ";
	-webkit-border-radius:0px 0px $border_radius $border_radius;
	-o-border-radius:0px 0px $border_radius $border_radius;
	-moz-border-radius:0px 0px $border_radius $border_radius;
    border-radius:0px 0px $border_radius $border_radius;
    max-width: " . ( $settings['template'] == 'boxed' ? $settings['body_size'] . 'px' : '100%' ) . ';
';

$credit = '
	border:0;
	color: ' . $settings['footer_text_color'] . ';
	font-family: Arial;
	font-size: ' . $settings['footer_text_size'] . 'px;
	line-height:125%;
	text-align:' . $settings['footer_aligment'] . ';
';
?>


				</div>
	<!-- End Content -->
			</td>
		</tr>
	</table>
	<!-- End Body -->
		</td>
	</tr>
	<tr>
		<td align="center" valign="top">
			<!-- Footer -->
			<table border="0" cellpadding="10" cellspacing="0" width="100%" id="template_footer" style="<?php echo $template_footer; ?>">
				<tr>
					<td valign="top">
						<table border="0" cellpadding="10" cellspacing="0" width="100%">
							<tr>
								<td colspan="2" valign="middle" id="credit" style="<?php echo $credit; ?>">                           
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<!-- End Footer -->
		</td>
	</tr>
			</table>
		</td>
	</tr>
</table>
		</div>
	</body>
</html>
