<link rel="shortcut icon" href="<?php echo base_url('public/images/favicon.ico'); ?>">
<!-- Bootstrap -->
<link rel="stylesheet" type='text/css' href="https://fonts.googleapis.com/css?family=Raleway:400,600,700">
<link rel="stylesheet" type='text/css' href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/css/intlTelInput.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css"/>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css"/>
<link rel="stylesheet" type='text/css' href="<?php echo base_url('public/css/bootstrap.min.css'); ?>">
<link rel='stylesheet' type='text/css' href='<?php echo base_url('public/css/google-fonts.css'); ?>'>
<link rel='stylesheet' type='text/css' href='<?php echo base_url('public/css/flexslider.css'); ?>'>
<link rel='stylesheet' type='text/css' href="<?php echo base_url('public/css/toastr/toastr.min.css'); ?>"/>
<link rel='stylesheet' type='text/css' href="<?php echo base_url('public/css/font-awesome.css');?>" />
<link rel="stylesheet" type='text/css' href="<?php echo base_url('public/css/style.css?v=7'); ?>">
<link rel="stylesheet" type='text/css' href="<?php echo base_url('public/css/checkbox-styles.css?v=3'); ?>">
<link rel="stylesheet" type='text/css' href="<?php echo base_url('public/css/select2/select2.min.css'); ?>">
<link rel="stylesheet" type='text/css' href="<?php echo base_url('public/css/select2/customize-select2.css'); ?>">
<link rel="stylesheet" type='text/css' href="<?php echo base_url('public/fonts/iconfont/material-icons.css?v=1'); ?>">
<link rel="stylesheet" type='text/css' href="<?php echo base_url('public/css/app/validation-form.css'); ?>">
<link rel="stylesheet" type='text/css' href="<?php echo base_url('public/css/app/dataTable.css'); ?>">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
	<script src="<?php //echo base_url('public/js/html5shiv.js');?>"></script>
	<script src="<?php //echo base_url('public/js/respond.min.js');?>"></script>
<![endif]-->

<style type="text/css">
	html {
		overflow-x: hidden; 
	}
	
	.navbar-default {
		background-color: #fff;
	}
	
	.input-group-addon {
		text-align: left;
		background: transparent;
		border: 0;
	}

	.navbar-brand {
		padding: 10px 0 0 0;
	}
/*
	.searchlist {
	    padding: 10px 0;
	}

	.searchlist table td {
	    padding: 5px 8px;
	}
*/
	.table-search {
	    padding: 10px 0;
	}

	.table-search table td {
	    padding: 5px 8px;
	}

	.btn-search {
		box-shadow:inset 0px 1px 0px 0px #ffffff;
		background:linear-gradient(to bottom, #ededed 5%, #dfdfdf 100%);
		background-color:#ededed;
		border-radius:6px;
		border:1px solid #dcdcdc;
		display:inline-block;
		cursor:pointer;
		color:#777777;
		font-family:Arial;
		font-size:15px;
		font-weight:bold;
		padding:6px 24px;
		text-decoration:none;
		text-shadow:0px 1px 0px #ffffff;
	}

	.btn-search:hover {
		background:linear-gradient(to bottom, #dfdfdf 5%, #ededed 100%);
		background-color:#dfdfdf;
	}
	
	.btn-search:active {
		position:relative;
		top:1px;
	}

	.dashiconwrp {
		display: none;
	}

	@media (max-width: 767px) {
	    .table-responsive .dropdown-menu {
	        position: relative !important;
	    }
	}
	
	@media (min-width: 768px) {
	    .table-responsive {
	        overflow: visible;
	    }
	}

	@media (max-width: 991px) {
	    .hiringbtn {
	        display: none;
	    }
	}

	.label-check i,
	.label-radio i {
		color: #333;
		vertical-align:text-bottom;
		font-size: 20px;
	}

	.panel-filter {
		padding: 5px 0px;
	}
	.panel-filter label {
		display: block;
		font-size: 16px;
		font-weight: normal;
	}

	.panel-filter .filter-title {
		padding: 6px 0px;
		margin-bottom: 6px;
	}

	.panel-filter .filter-title h4 {
		font-size: 14px;
	} 

	.ref-content {
		text-align:center;
	}

	.ref-content p,.ref-content i  {
		display:inline-block;
	}
	.ref-content i {
		font-size: 18px;
	}

	.load {
		position: relative;
	}
      
	.load:before {
		position: absolute;
		top: 0;
		right: 0;
		bottom: 0;
		left: 0;
		content: " ";
		background-color: rgba(255,255,255,.5);
		z-index: 4;
	}

	.load-image {
		position: relative;
	}

	.load-image:after {
		position: absolute;
		top: 0;
		right: 0;
		bottom: 0;
		left: 0;
		content: " ";
		background: url(<?php echo img_loading_url(); ?>) no-repeat;
		background-position:center center;
		background-size: 24px 24px;
		z-index: 4;
	}

	.iti--inline-dropdown .iti__dropdown-content {
		z-index: 100;
	}
</style>

<?php if (isset($_GET['platform']) && $_GET['platform'] == 'mobile'): ?>
	<style>
		body {
			-webkit-user-select: none;
			-webkit-touch-callout: none;
			-moz-user-select: none;
			-ms-user-select: none;
			user-select: none;
		}
	</style>
<?php endif; ?>

<script>
	var baseUrl = '<?php echo base_url(); ?>';
</script>

<!-- Global site tag (gtag.js) - Google Analytics -->

<script async src="https://www.googletagmanager.com/gtag/js?id=UA-112561138-1"></script>

<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-112561138-1');
</script>
