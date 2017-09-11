<?php
$loggedin_data = $this->users->get_loggedin_data();
$loggedin_user_role = $loggedin_data['role'];
?>
<header id="header" class="header">
	<div class="inset cf">
		<div class="logo">
			<a href="<?php echo admin_url(); ?>">	
				<!-- <img src="<?php echo asset_url("images/logo.png"); ?>" height="40" /> -->
				Book Cities
			</a>
		</div>
		<nav class="navigation">
			<ul>
				<?php
				if($loggedin_user_role == 1)
				{
					?>
					<li class="<?php echo active_link('pages','index'); ?>"><a href="<?php echo admin_url(); ?>">Dashboard</a></li>

					<li class="<?php echo active_link('pages','appsettings'); ?>"><a href="<?php echo admin_url('pages/appsettings'); ?>">App Settings</a></li>

					<li class="<?php echo active_link('stores'); ?>"><a href="<?php echo admin_url('stores/'); ?>">Stores</a>
						<ul class="sub">
							<li class="<?php echo active_link('stores','emptylatlong'); ?>"><a href="<?php echo admin_url('stores/emptylatlong'); ?>">Empty Latitude & Longitude</a></li>
						</ul>
					</li>

					<li class="<?php echo active_link('books_categories'); ?>"><a href="<?php echo admin_url('books_categories/'); ?>">Books</a>
						<ul class="sub">
							<li class="<?php echo active_link('books_categories','manage'); ?>"><a href="<?php echo admin_url('books_categories/manage'); ?>">Books Categories</a></li>
						</ul>
					</li>

					<li class="<?php echo active_link('pages','countries'); echo active_link('pages','states'); echo active_link('pages','cities'); ?>"><a href="<?php echo admin_url('pages/cities'); ?>">Cities</a>
						<ul class="sub">
							<li class="<?php echo active_link('pages','countries'); ?>"><a href="<?php echo admin_url('pages/countries'); ?>">Countries</a></li>
							<li class="<?php echo active_link('pages','states'); ?>"><a href="<?php echo admin_url('pages/states'); ?>">States</a></li>
							<li class="<?php echo active_link('pages','cities'); ?>"><a href="<?php echo admin_url('pages/cities'); ?>">Cities</a></li>
						</ul>
					</li>
					<li class="<?php echo active_link('admin_users'); ?>"><a href="<?php echo admin_url('admin_users/'); ?>">Users</a>
					</li>
					<?php
				}
				else
				{
					?>
						<li class="<?php echo active_link('stores'); ?>"><a href="<?php echo admin_url('stores/'); ?>">Stores</a>
						<ul class="sub">
							<li class="<?php echo active_link('stores','emptylatlong'); ?>"><a href="<?php echo admin_url('stores/emptylatlong'); ?>">Empty Latitude & Longitude</a></li>
						</ul>
					</li>
					<li class="<?php echo active_link('stores','create'); ?>"><a href="<?php echo admin_url('stores/create'); ?>">Create Store</a>
					</li>
					<?php
				}
				?>
				<li><a href="<?php echo admin_url('auth/logout'); ?>">Logout</a></li>
			</ul>
		</nav>
	</div>
</header>