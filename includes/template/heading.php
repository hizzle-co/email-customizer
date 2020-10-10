<tbody class="components__item components__header">
	<tr>
		<td>
		    <table cellspacing="0" cellpadding="0" border="0" role="presentation" class="component">
				<tr>
					<td class="heading">

						<!--[if (gte mso 9)|(IE)]><table width="100%" border="0" cellspacing="0" cellpadding="0" role="presentation"><tr><td width="<?php echo esc_attr( $header_left_width ); ?>" valign="middle"><![endif]-->
						<table cellpadding="0" cellspacing="0" border="0" role="presentation" class="sm-col xs-col xs-center xs-mb-20 heading__left">
							<tbody>
								<tr>
                                    <td align="" class="heading__left-title">

                                        <div class="heading__left-title-div">
                                            <?php if ( ! empty( $logo ) ) : ?>
                                                <a href="<?php echo esc_url( home_url() ); ?>">

                                                    <!--[if gte mso 9]>
                                                        <img src="<?php echo esc_url( $logo ); ?>" width="110" height="25" alt="<?php echo esc_attr( get_option( 'blogname' ) ); ?>">
                                                    <![endif]-->

                                                    <!--[if !gte mso 9]><!-->
                                                        <img width="110" src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_attr( get_option( 'blogname' ) ); ?>">
                                                    <!--<![endif]-->
                                                </a>
                                            <?php endif; ?>

                                            &nbsp;
                                            <?php echo wp_kses_post( $header_1 ); ?>
                                        </div>

                                    </td>
								</tr>
							</tbody>
                        </table>


						<!--[if (gte mso 9)|(IE)]></td><td width="0" valign="middle"><![endif]-->
						<table cellpadding="0" cellspacing="0" border="0" role="presentation" class="wrapper sm-col xs-col xs-center heading__right" style="margin: 0px;">
							<tbody>
								<tr>
								    <td align="center">
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
			</table><!-- END COMPONENT -->
		</td>
    </tr>

	<tr class="card-spacing" style="font-size: 0px;">
		&nbsp;
    </tr>

</tbody>
