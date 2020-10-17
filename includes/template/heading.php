<!-- START COMPONENT: HEADER -->
<tbody class="components__item components__header">
	<tr>
		<td>
		    <table cellspacing="0" cellpadding="0" border="0" role="presentation" class="component">
				<tr>
					<td class="components__inner">

						<!--[if (gte mso 9)|(IE)]><table width="100%" border="0" cellspacing="0" cellpadding="0" role="presentation"><tr><td width="<?php echo esc_attr( $header_left_width ); ?>" valign="middle"><![endif]-->
						<table cellpadding="0" cellspacing="0" border="0" role="presentation" class="heading__left">
							<tbody>
								<tr>
                                    <td class="heading__left-title">

                                        <div class="heading__left-title-div">
                                            <?php if ( ! empty( $logo ) || is_customize_preview() ) : ?>
                                                <a href="<?php echo esc_url( home_url() ); ?>">

                                                    <!--[if gte mso 9]>
                                                        <img src="<?php echo esc_url( $logo ); ?>" width="110" height="25" alt="<?php echo esc_attr( get_option( 'blogname' ) ); ?>">
                                                    <![endif]-->

                                                    <!--[if !gte mso 9]><!-->
                                                        <img class="logo" style="<?php echo empty( $logo ) ? 'display:none' : ''; ?>" src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_attr( get_option( 'blogname' ) ); ?>">
                                                    <!--<![endif]-->
                                                </a>
                                            <?php endif; ?>

											&nbsp;
											<span class="heading__left-title-text">
												<?php echo wp_kses_post( $header_1 ); ?>
											</span>

                                        </div>

                                    </td>
								</tr>
							</tbody>
                        </table>


						<!--[if (gte mso 9)|(IE)]></td><td width="0" valign="middle"><![endif]-->
						<table cellpadding="0" cellspacing="0" border="0" role="presentation" class="heading__right" style="margin: 0px;">
							<tbody>
								<tr>
								    <td align="right">
										<table cellpadding="0" cellspacing="0" border="0" role="presentation" class="wrapper">
											<tbody>
												<tr>
											        <td class="heading__right-title">
                                                        <?php echo wp_kses_post( $header_2 ); ?>
											        </td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
                        <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->


					</td>
				</tr>
			</table>
		</td>
    </tr>

	<tr class="card-spacing">
		&nbsp;
    </tr>

</tbody>
<!-- END COMPONENT -->