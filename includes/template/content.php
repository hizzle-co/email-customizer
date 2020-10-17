<!-- START COMPONENT: CONTENT -->
<tbody class="components__item components__content">
	<tr>
		<td>
			<table cellspacing="0" cellpadding="0" border="0" role="presentation" class="component">
				<tr>
					<td class="components__inner">

						<table cellpadding="0" cellspacing="0" role="presentation" border="0" class="styling-table">
							<tbody>
								<tr>
									<td class="hero-section">
										<?php echo wp_kses_post( $before_content ); ?>
										<!--[if gte mso 12]><p style="font-size: 0px; line-height: 0px; mso-line-height-rule:exactly;">&nbsp;</p><![endif]-->
									</td>
								</tr>
							</tbody>
						</table>

						<table cellpadding="0" cellspacing="0" role="presentation" border="0" class="styling-table">
							<tbody>
								<tr>
									<td class="content">
										<?php echo wp_kses_post( $content ); ?>
										<!--[if gte mso 12]><p style="font-size: 0px; line-height: 0px; mso-line-height-rule:exactly;">&nbsp;</p><![endif]-->
									</td>
								</tr>
							</tbody>
						</table>

					</td>
				</tr>
			</table>
		</td>
	</tr>

	<tr class="card-spacing">
		&nbsp;
	</tr>
</tbody>
<!-- END COMPONENT: CONTENT -->