<!doctype html>
<html>
<head>
	<title>Book Cities<?php echo $title_for_layout ?></title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" href="<?php echo asset_url("css/base.css?v=1.0"); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo asset_url("css/admin.css?v=1.0"); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo asset_url("css/chosen.css?v=1.0"); ?>" type="text/css" />
	
	<script>
		var site_url = "<?php echo base_url(); ?>admin/";
	</script>
	<script src="<?php echo asset_url("js/jquery.js"); ?>"></script>
	<script src="<?php echo asset_url("js/admin.js?v=1.0"); ?>"></script>
	<script src="<?php echo asset_url("js/chosen.jquery.js"); ?>"></script>
	<script src="<?php echo asset_url("js/scripts.js?v=1.0"); ?>"></script>
</head>
<body class="<?php echo page_class(); ?>">
	<div class="site-warp">
		<div class="main-container" id="main">
			<?php if(!empty($flash['msg'])){ ?>
				<div class="note flash-msg inset">
					<div class="<?php echo $flash['status'];?>"><?php echo $flash['msg'];?></div>
				</div>
			<?php } ?>
			<?php echo $content_for_layout ?>
		</div>
	</div>
</body>
</html>