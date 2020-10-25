<div class="email-customizer-change-theme-popup">

	<div>

		<label style="display: block;">

			<div>
				<strong><?php esc_html_e( 'Select Template', 'email-customizer' ); ?></strong>
			</div> 

			<select class="regular-text">
				<option value="default" selected="selected"><?php esc_html_e( 'Default', 'email-customizer' ); ?></option>
				<option value="flat"><?php esc_html_e( 'Flat', 'email-customizer' ); ?></option>
				<option value="dark"><?php esc_html_e( 'Dark', 'email-customizer' ); ?></option>
				<option value="hero_image"><?php esc_html_e( 'Hero Image', 'email-customizer' ); ?></option>
			</select>

		</label>

		<input type="submit" class="button button-primary email-customizer-change-theme-popup-submit" value="<?php esc_attr_e( 'Use Template', 'email-customizer' ); ?>" style="margin-top: 10px;">
	</div>

	<span class="email-customizer-popup-close">
		<span class="dashicons dashicons-no" title="<?php esc_attr_e( 'Close', 'email-customizer' ); ?>" style="font-size: 2.5em;cursor: pointer"></span>
	</span>
</div>
