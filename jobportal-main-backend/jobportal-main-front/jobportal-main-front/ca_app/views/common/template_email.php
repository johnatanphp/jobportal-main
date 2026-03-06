<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
		body {
			margin: 0;
			padding: 0;
		}

		.separator {
			height: 2px;
			min-width: 600px;
			border-bottom: 1px solid #ccc;
			margin:0 auto;
			margin-top: 25px;
		}

		.footer .support a {
			font-size: 15px;
		}

		.footer .support {
			padding: 12px 0px;
		}

		.footer .link-social a {
			max-width: 32px;
			padding: 1px 4px;
			text-decoration: none;
		}

		.footer .link-social :hover {
			text-decoration: none;
		}

	</style>
	<style type="text/css">
		<?php echo $style; ?>
	</style>
</head>
<body>
	<div id="content-main" style="background: #e0e0e0; padding: 20px;">
		<div class="email-content" style="border-radius: 5px; padding: 4px 7px; font-family: arial; max-width: 600px; margin: 0 auto; background: #fff;">
			<div class="header" style="padding: 20px 10px; border-bottom: 1px solid #ccc;">
				<img style="width: 180px;margin: 0 auto;display:block;" src="http://portalempleo.overall.pe/public/images/overall_blue.png">
			</div>
			<div class="body" style="padding: 15px 10px; font-size: 16px; color: #222; border-radius: 6px;">
				<?php echo isset($content_email) ? $content_email :  ''; ?>
			</div>
			<div class="footer" style="text-align:center;">
				<div class="support">
					<a href="<?php echo base_url(); ?>">Portal de empleo</a> | <a href="<?php echo base_url('contact-us'); ?>">Soporte Portal de empleo</a>  | <a href="http://www.overall.pe" class="#">Corporativo Overall</a>
				</div>
				<div class="link-social" style="padding: 10px;">
					<a href="https://www.facebook.com/overallbusiness">
						<img src="<?php echo base_url('public/images/facebook-grey.png'); ?>" width="32" height="32">
					</a>
					<a href="https://pe.linkedin.com/in/overallbusiness">
						<img src="<?php echo base_url('public/images/linkedin-grey.png'); ?>" width="32" height="32">
					</a>
					<a href="https://twitter.com/overallbusiness">
						<img src="<?php echo base_url('public/images/twitter-grey.png'); ?>" width="32" height="32">
					</a>
					<a href="https://www.youtube.com/user/CorpOverallBusiness/">
						<img src="<?php echo base_url('public/images/youtube-grey.png'); ?>" width="32" height="32">
					</a>
				</div>
				<div class="copyright" style="padding: 25px 7px; font-size: 13px; color: #777;">
					Copyright © <?php echo date('Y'); ?> Overall Perú. Todos los derechos reservados.
				</div>
			</div>
		</div>
	</div>
</body>
</html>



