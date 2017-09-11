<?php
$this->load->view('part/header');

$countryArr = array();
if(!empty($countries))
{
	foreach($countries as $country)
	{
		$countryArr[$country->id] = $country->name;
	}
}
$stateArr = array();
if(!empty($states))
{
	foreach($states as $state)
	{
		$stateArr[$state->id] = $state->name;
	}
}
?>
<div class="login-page inset">
	<div class="row cf">
		<div class="col c12">
			<a href="<?php echo admin_url('pages/addcity'); ?>" class="button medium right">Add New</a>
			<h1 class="page-title">
				<?php
				if($total_cities == $count_cities)
				{
					echo "All Cities - ".$total_cities;
				}
				else
				{
					echo "Cities of '".$stateArr[$state_id]."' - ".$count_cities;
				}
				?>
			</h1>
		</div>
		<div class="toolbar cf">
			<div class="sort-order left">
				<!-- <div class="sort">
					<strong>Sort By: </strong>
					<?php $params = isset($uri_params['order']) ? '?order='.$uri_params['order'].'&sort=' : '?sort='; ?>
					<a class="<?php echo @$uri_params['sort'] == 'topic' ? 'active':''; ?>" href="<?php echo seturl_param('sort', 'topic'); ?>">Topic</a>, 
					<a class="<?php echo (empty($uri_params['sort']) || $uri_params['sort'] == 'event_date') ? 'active':''; ?>" href="<?php echo seturl_param('sort', 'event_date'); ?>">Event Date</a>
				</div> -->
				<!-- <div class="order">
					<strong>Order: </strong>
					<?php $params = isset($uri_params['sort']) ? '?sort='.$uri_params['sort'].'&order=' : '?order='; ?>
					<a class="<?php echo @$uri_params['order'] == 'ASC' ? 'active':''; ?>" href="<?php echo seturl_param('order', 'ASC'); ?>">Ascending</a>, 
					<a class="<?php echo (empty($uri_params['order']) || $uri_params['order'] == 'DESC') ? 'active':''; ?>" href="<?php echo seturl_param('order', 'DESC'); ?>">Descending</a>
				</div> -->
			</div>
			<div class="paginate right"><?php echo $pagination; ?></div>
		</div>
	</div>
	<div class="row cf">
		<div class="col c12">
			<table class="events-table">
				<tr class="row-head">
					<th width="20">S.No.</th>
					<th>Country Name</th>
					<th>State Name</th>
					<th>City Name</th>
					<th width="150"></th>
				</tr>
				<pre>
				<?php
				if(!empty($cities))
				{
					$i=1;
					foreach($cities as $city)
					{
						?>
						<tr class="tale-row">
							<td><?php echo ($skipped_records + $i); ?></td>
							<td>
								<?php if(empty($countryArr[$city->country_id])){ echo "--"; }else{ echo $countryArr[$city->country_id]; } ?>
							</td>
							<td>
								<?php if(empty($stateArr[$city->state_id])){ echo "--"; }else{ echo $stateArr[$city->state_id]; } ?>
							</td>
							<td>
								<!-- <a href="<?php echo admin_url('pages/page/'.$city->id); ?>"> -->
									<?php echo $city->name; ?>
								<!-- </a> -->
							</td>
							<td class="action">
								<div class="action-buttons right">
									<?php icon_link('edit', 'admin/pages/editcity?id='.$city->id, 'Edit'); ?>
									<?php //icon_link('block', 'admin/pages/block/'.$city->id, 'Unpublish'); ?>
									<?php icon_link('delete', 'admin/pages/deletecity?id='.$city->id, 'Delete'); ?>
								</div>
							</td>
						</tr>
						<?php
						$i++;
					}
				}
				?>
			</table>
		</div>
		<div class="footer toolbar cf">
			<div class="sort-order left">
				<!-- <div class="sort">
					<strong>Sort By: </strong>
					<?php $params = isset($uri_params['order']) ? '?order='.$uri_params['order'].'&sort=' : '?sort='; ?>
					<a class="<?php echo @$uri_params['sort'] == 'topic' ? 'active':''; ?>" href="<?php echo seturl_param('sort', 'topic'); ?>">Topic</a>, 
					<a class="<?php echo (empty($uri_params['sort']) || $uri_params['sort'] == 'event_date') ? 'active':''; ?>" href="<?php echo seturl_param('sort', 'event_date'); ?>">Event Date</a>
				</div> -->
				<!-- <div class="order">
					<strong>Order: </strong>
					<?php $params = isset($uri_params['sort']) ? '?sort='.$uri_params['sort'].'&order=' : '?order='; ?>
					<a class="<?php echo @$uri_params['order'] == 'ASC' ? 'active':''; ?>" href="<?php echo seturl_param('order', 'ASC'); ?>">Ascending</a>, 
					<a class="<?php echo (empty($uri_params['order']) || $uri_params['order'] == 'DESC') ? 'active':''; ?>" href="<?php echo seturl_param('order', 'DESC'); ?>">Descending</a>
				</div> -->
			</div>
			<div class="paginate right"><?php echo $pagination; ?></div>
		</div>
	</div>	
</div>
<?php $this->load->view('part/header'); ?>