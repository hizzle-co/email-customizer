<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo esc_attr( get_bloginfo( 'charset' ) ); ?>">
	<!--[if !mso]><!-->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!--<![endif]-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="format-detection" content="telephone=no">
	<meta name="x-apple-disable-message-reformatting">
    <title><?php echo esc_html( get_bloginfo( 'name' ) ); ?></title>
	<?php if ( is_customize_preview() ) : ?>
		<?php do_action( 'wp_head' ); ?>
	<?php endif; ?>

	<style>
		body {
			margin: 0;
			padding: 0;
			width: 100% !important;
            mso-line-height-rule: exactly;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
			font-family: Arial, Helvetica, sans-serif;

			<?php if ( is_customize_preview() ) : ?>
				min-height: 100vh;
			<?php endif; ?>
		}

        table,
		td {
			mso-table-lspace: 0;
			mso-table-rspace: 0;
			border-collapse: collapse;
		}

		tr {
			border-collapse: collapse;
        }

        img {
			border: 0;
			outline: none;
			line-height: 100%;
			text-decoration: none;
			-ms-interpolation-mode: bicubic;
		}

		body,
		td,
		th,
		p,
		div,
		li,
		a,
		span {
			-webkit-text-size-adjust: 100%;
			-ms-text-size-adjust: 100%;
			mso-line-height-rule: exactly;
		}

		p:first-of-type {
			margin-top: 0 !important;
        }

		a,
		a[x-apple-data-detectors] {
			text-decoration: none;
		}

        .preview-text {
            color: transparent;
            display: none;
            height: 0;
            max-height: 0;
            max-width: 0;
            opacity: 0;
            overflow: hidden;
            mso-hide: all;
            visibility: hidden;
            width: 0;
        }

        .template__body {
            padding: 50px 0px;
            table-layout: fixed;
        }

        .template__container {
			margin: 0 auto;
            border-collapse: collapse;
        }

        .components,
        .component {
            border-collapse: collapse;
            width: 100%;
		}

		.styling-table {
			white-space: normal;
			border-collapse: collapse;
			width: 100%;
        }

		.components__header h1,
		.components__header h2,
		.components__header h3,
		.components__header h4,
		.components__header h5,
		.components__header h6 {
			margin: 0;
			display: inline-block;
		}

        .components__header .heading__left {
            display: inline-table;
            vertical-align: top;
            float: left;
        }

        .heading__left-title .logo {
            vertical-align: top;
            max-width: 100%;
            width: 110px;
        }

        .hero-image {
            vertical-align: top;
            max-width: 100%;
        }

        .card-spacing {
            font-size: 0px;
        }

        .components__content .hero-section i {
            font-size: 12px;
            font-style: italic;
            opacity: 0.7;
        }

		.gmail-fix {
			display: none !important;
			white-space: nowrap;
			font: 15px courier;
			line-height: 0;
		}

		/*** Customizations */
		body {
			background-color: <?php echo sanitize_hex_color( $bg_color ); ?>;

			<?php if ( ! empty( $bg_image ) || is_customize_preview() ) : ?>
			background-image: url('<?php echo esc_url( $bg_image ); ?>');
			background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
			<?php endif; ?>

		}

		.template__container,
		.hero-image {
			width: <?php echo esc_html( $container_width ); ?>;
		}

		.components__header .heading__left {
			width: <?php echo esc_html( $header_left_width ); ?>;
		}

		.card-spacing {
			height: <?php echo esc_html( $spacing ); ?>;
		}

		.components__header .components__inner {
			background-color: <?php echo esc_html( sanitize_hex_color( $header_bg ) ); ?>;
            font-size: <?php echo esc_html( $header_font_size ); ?>;
            color: <?php echo esc_html( sanitize_hex_color( $header_text_color ) ); ?>;
		}

		.components__header p,
		.components__header div {
			font-size: <?php echo esc_html( $header_font_size ); ?>;
            color: <?php echo esc_html( sanitize_hex_color( $header_text_color ) ); ?>;
		}

		.components__header a,
		.components__header a[x-apple-data-detectors] {
			font-size: <?php echo esc_html( $header_font_size ); ?>;
            color: <?php echo esc_html( sanitize_hex_color( $header_link_color ) ); ?>;
		}

		.components__content .components__inner {
			background-color: <?php echo esc_html( sanitize_hex_color( $content_bg ) ); ?>;
            font-size: <?php echo esc_html( $content_font_size ); ?>;
            color: <?php echo esc_html( sanitize_hex_color( $content_text_color ) ); ?>;
		}

		.components__content p,
		.components__content div {
			font-size: <?php echo esc_html( $content_font_size ); ?>;
            color: <?php echo esc_html( sanitize_hex_color( $content_text_color ) ); ?>;
		}

		.components__content a,
		.components__content a[x-apple-data-detectors] {
			font-size: <?php echo esc_html( $content_font_size ); ?>;
            color: <?php echo esc_html( sanitize_hex_color( $content_link_color ) ); ?>;
		}

		.components__footer .components__inner {
			background-color: <?php echo esc_html( sanitize_hex_color( $footer_bg ) ); ?>;
            font-size: <?php echo esc_html( $footer_font_size ); ?>;
            color: <?php echo esc_html( sanitize_hex_color( $footer_text_color ) ); ?>;
		}

		.components__footer p,
		.components__footer div {
			font-size: <?php echo esc_html( $footer_font_size ); ?>;
            color: <?php echo esc_html( sanitize_hex_color( $footer_text_color ) ); ?>;
		}

		.components__footer a,
		.components__footer a[x-apple-data-detectors] {
			font-size: <?php echo esc_html( $footer_font_size ); ?>;
            color: <?php echo esc_html( sanitize_hex_color( $footer_link_color ) ); ?>;
		}

	</style>

	<style id="email-customizer-preview-custom-css">
		<?php echo wp_strip_all_tags( $custom_css ); /* Note that esc_html() cannot be used because `div &gt; span` is not interpreted properly. */ ?>
	</style>

	<!--[if gte mso 9]>
	<style type="text/css">
		.pc-font {
			font-family: Helvetica, Arial, sans-serif !important;
		}
		.mso-col-100 {
			width: 100% !important;
		}
	</style>
	<![endif]-->
</head>
<!--[if gte mso 9]><xml><o:OfficeDocumentSettings><o:AllowPNG/><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml><![endif]-->

<body class="email-body">
    <span class="preview-text"><?php echo wp_kses_post( $preview_text ); ?></span>
    <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0" role="presentation" class="template__body">
        <tbody>
			<tr>
                <td style="vertical-align: top;">
                    <!--[if gte mso 9]>
                        <v:background xmlns:v="urn:schemas-microsoft-com:vml" fill="t">
                            <v:fill type="tile" src="" color="<?php echo sanitize_hex_color( $bg_color ); ?>"></v:fill>
                        </v:background>
                    <![endif]-->
                    <!--[if (gte mso 9)|(IE)]><table width="600" align="center" border="0" cellspacing="0" cellpadding="0" role="presentation"><tr><td width="620" align="center" valign="top"><![endif]-->
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation" class="template__container">
						<tbody>
							<tr>
                                <td class="template__inner">
									<table class="components" cellpadding="0" cellspasing="0" role="presentation">
