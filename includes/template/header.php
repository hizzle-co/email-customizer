<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
	xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo get_bloginfo('charset');?>">
	<!--[if !mso]><!-->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!--<![endif]-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="format-detection" content="telephone=no">
	<meta name="x-apple-disable-message-reformatting">
    <title><?php echo get_bloginfo('name'); ?></title>
    <?php do_action( 'wp_head' ); ?>
	<style>
		body {
			margin: 0;
			padding: 0;
			width: 100% !important;
            mso-line-height-rule: exactly;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            background-color: <?php echo sanitize_hex_color( $bg_color ); ?>;
            background-image: url('<?php echo esc_url( $bg_image ); ?>');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            font-family: Arial, Helvetica, sans-serif;
		}

        table,
		td {
			mso-table-lspace: 0;
			mso-table-rspace: 0;
		}

		table,
		tr,
		td {
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
            width: <?php echo sanitize_text_field( $container_width ); ?>;
			margin: 0 auto;
            border-collapse: collapse;
        }

        .components,
        .component {
            border-collapse: collapse;
            width: 100%;
        }

        .components__header .heading {
            padding: 30px 35px;
            background-color: <?php echo sanitize_hex_color( $header_bg ); ?>;
            font-size: <?php echo sanitize_text_field( $header_font_size ); ?>;
            color: <?php echo sanitize_hex_color( $header_text_color ); ?>;
            border-radius: 5px;
        }

        .components__header .content p,
        .components__header .content div {
            font-size: <?php echo sanitize_text_field( $header_font_size ); ?>;
            color: <?php echo sanitize_hex_color( $header_text_color ); ?>;
        }

        .components__header .heading a,
        .components__header .heading a[x-apple-data-detectors] {
            color: <?php echo sanitize_hex_color( $header_link_color ); ?>;
            text-decoration: none;
        }

        .components__header .heading__left {
            display: inline-table;
            vertical-align: top;
            float: left;
            width: <?php echo sanitize_text_field( $header_left_width ); ?>;
        }

        .heading__left-title img {
            vertical-align: top;
            max-width: 100%;
            width: 110px;
        }

        .heading__right-title {
            padding-top: 3px;
            padding-right: 20px;
        }

        .hero-image {
            vertical-align: top;
            max-width: 100%;
            width: <?php echo sanitize_text_field( $container_width ); ?>;
        }

        .card-spacing {
            height: <?php echo sanitize_text_field( $row_spacing ); ?>;
        }

        .components__content {
            background-color: <?php echo sanitize_hex_color( $content_bg ); ?>;
            font-size: <?php echo sanitize_text_field( $content_font_size ); ?>;
            color: <?php echo sanitize_hex_color( $content_text_color ); ?>;
            border-radius: 5px;
        }

        .components__content p,
        .components__content div{
            font-size: <?php echo sanitize_text_field( $content_font_size ); ?>;
            color: <?php echo sanitize_hex_color( $content_text_color ); ?>;
        }

        .components__content .content {
            padding: 30px 35px;
        }

        .components__content .hero-section p {
            padding: 20px 35px;
        }

        .components__content .hero-section a,
        .components__content .hero-section [x-apple-data-detectors],
        .components__content .content a,
        .components__content .content a[x-apple-data-detectors] {
            color: <?php echo sanitize_hex_color( $content_link_color ); ?>;
            text-decoration: none;
        }

        .components__content .hero-section i {
            font-size: 12px;
            font-style: italic;
            opacity: 0.7;
        }

		.gmail-fix {
			display: none !important;
		}

		.sm-right {
			text-align: right;
			margin-left: auto;
		}

		.sm-center {
			text-align: center;
		}

		.sm-padding-left-30 {
			padding-left: 30px;
		}

		.sm-padding-right-20 {
			padding-right: 20px;
		}

		.post-col-left {
			padding-right: 10px;
		}

		.post-col-right {
			padding-left: 10px;
		}

		.sm-col-25 {
			width: 25%;
		}

		.sm-col-33 {
			width: 33%;
		}

		.sm-col-50 {
			width: 50%;
		}

		@media screen and (max-width:620px) {
			table.template-container {
				width: 320px !important;
				margin: 0 auto;
				white-space: normal;
			}

			.xs-col {
				width: 100% !important;
			}

			.xs-spacing {
				margin: 10px 0 !important;
			}

			.xs-mb-10 {
				margin-bottom: 10px;
			}

			.xs-mb-20 {
				margin-bottom: 20px;
			}

			.xs-center {
				text-align: center;
			}

			.xs-table-center {
				text-align: center;
				margin: 0 auto;
			}

			.sm-padding-left-30 {
				padding-left: 0;
			}

			.sm-padding-right-20 {
				padding-right: 0;
			}

			.post-col-left {
				padding-right: 0;
			}

			.post-col-right {
				padding-left: 0;
			}
		}
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

<body>
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