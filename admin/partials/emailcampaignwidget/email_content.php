

<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top" style="width:100%; margin: auto;">   
			<table border="0" cellpadding="0" cellspacing="0" width="100%" style="width:100%; margin: auto;">

<?php if ( ! empty( $thumbnail ) ) { ?>

				<a href="<?php echo $blog_info['url']; ?>">
					<div style="text-align:center"><img <?php echo $thumbnail; ?> style="text-align:center; width: 100%; margin-bottom: 20px;"></div>
				</a><tr>

<?php } ?>

				<table border="0" cellpadding="0" cellspacing="0" style="padding: 0 1% 0 1%">
					<p><?php echo $content; ?></p>
				</table>
			</table>    
		</td>
	</tr>
</table>



