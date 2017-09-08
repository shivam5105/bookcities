<html>
	<head>
		<title>Book Cities<?php echo $title_for_layout ?></title>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<link rel="stylesheet" href="<?php echo base_url("assets/css/base.css?v=1.0"); ?>" type="text/css" />
		<link rel="stylesheet" href="<?php echo base_url("assets/css/main.css?v=1.0"); ?>" type="text/css" />
	</head>
	<body class="<?php echo page_class(); ?>">
		<?php if(!empty($flash['msg'])){ ?>
			<div class="note flash-msg inset">
				<div class="<?php echo $flash['status'];?>"><?php echo $flash['msg'];?></div>
			</div>
		<?php } ?>
		<?php echo $content_for_layout ?>
	</body>
</html>