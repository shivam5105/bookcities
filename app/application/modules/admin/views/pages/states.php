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
?>
<div class="login-page inset">
	<div class="row cf">
		<div class="col c12">
			<!-- <a href="<?php echo admin_url('pages/states/create'); ?>" class="button medium right">Add New</a> -->
			<h1 class="page-title">
				<?php
				if($total_states == $count_states)
				{
					echo "All States - ".$total_states;
				}
				else
				{
					echo "States of '".$countryArr[$country_id]."' - ".$count_states;
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
					<th width="300">Country Name</th>
					<th>State Name</th>
					<!-- <th width="150"></th> -->
				</tr>
				<pre>
				<?php
				if(!empty($states))
				{
					$i=1;
					foreach($states as $state)
					{
						?>
						<tr class="tale-row">
							<td><?php echo ($skipped_records + $i); ?></td>
							<td>
								<?php if(empty($countryArr[$state->country_id])){ echo "--"; }else{ echo $countryArr[$state->country_id]; } ?>
							</td>
							<td>
								<a href="<?php echo admin_url('pages/cities/'.$state->id); ?>">
									<?php echo $state->name; ?>
								</a>
							</td>
							<!-- <td class="action">
								<div class="action-buttons right">
									<?php icon_link('edit', 'admin/pages/page/'.$state->id, 'Edit'); ?>
									<?php icon_link('block', 'admin/pages/block/'.$state->id, 'Unpublish'); ?>
									<?php icon_link('delete', 'admin/pages/delete/'.$state->id, 'Delete'); ?>
								</div>
							</td> -->
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
<?php
$this->load->view('part/footer');
?>