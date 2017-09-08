<?php $this->load->view('part/header'); ?>
<?php
$books_cats = array();
foreach ($bookcategories as $key => $category)
{
	$books_cats[$category->id] = $category->name;
}
?>
<div class="login-page inset">
	<div class="row cf">
		<div class="col c12">
			<a href="<?php echo admin_url('stores/create'); ?>" class="button medium right">Add New</a>
			<a href="<?php echo base_url().'shop/email_login_form'; ?>" target="_shopReg" class="button medium right" style="margin-right:5px;">Add Shop (Without Login)</a>
			<div class="admin_filters">
				<b>Status: </b><select name="status" id="status" onchange="window.location.href='?status='+this.value;">
					<option value="">[-- All --]</option>
				<option value="2" <?php if(isset($_GET['status']) && trim($_GET['status']) == '2'){ echo "selected='selected'"; }?>>Edited</option>
					<option value="0" <?php if(isset($_GET['status']) && trim($_GET['status']) == '0'){ echo "selected='selected'"; }?>>Drafted</option>
					<option value="1" <?php if(isset($_GET['status']) && trim($_GET['status']) == '1'){ echo "selected='selected'"; }?>>Published</option>
				</select>
			</div>
			<h1 class="page-title">Stores - <?php echo $total_records; ?></h1>
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
					<th>Store Name</th>
					<th>Deals-in</th>
					<th>Books Categories</th>
					<th>Street</th>
					<!-- <th>Working Hours</th> -->
					<th>Latitude, Longitude</th>
					<th>Website</th>
					<th>Status</th>
					<th width="150"></th>
				</tr>
				<?php
				if(!empty($stores))
				{
					$i=1;
					foreach($stores as $store)
					{
						?>
						<tr class="tale-row">
							<td><?php echo ($skipped_records + $i); ?></td>
							<td>
								<a href="<?php echo admin_url('stores/edit/'.$store->id); ?>">
									<?php echo !empty($store->name) ? $store->name : "--"; ?>
								</a>
							</td>
							<td>
								<?php
								$empty_deals_in = 0;
								foreach ($this->deals_in as $deals_key => $deals_value)
								{
									if($store->$deals_key)
									{
										echo $deals_value."<br>";
									}
									else
									{
										$empty_deals_in++;
									}
								}
								if($empty_deals_in == count($this->deals_in))
								{
									echo "--";
								}
								?>
							</td>
							<td>
								<?php
								$store_book_cats = explode(":", $store->books_category_ids);
								$store_book_cats = array_unique(array_filter($store_book_cats));

								if(is_array($store_book_cats) && count($store_book_cats) > 0)
								{
									foreach ($store_book_cats as $bc_key => $bc_value)
									{
										echo $books_cats[$bc_value]."<br>";
									}
								}
								else
								{
									echo "--";
								}
								?>
							</td>
							<td>
								<div><?php echo $store->address; ?></div>
								<div><?php echo $store->address_2; ?></div>
							</td>
							<!-- <td><?php echo nl2br($store->working_hours); ?></td> -->
							<td><?php echo $store->latitude.", ".$store->longitude; ?></td>
							<td style="word-break: break-all;">
								<?php
								$website = $store->website;
								if(!empty($website))
								{
									$website1 = $website;
									if(strpos($website, "http") === false || strpos($website, "https") === false)
									{
										$website1 = "http://".$website;
									}
									echo "<a href='".$website1."' target='_blank'>".$website."</a>";
								}
								else
								{
									echo "--";
								}
								?>
							</td>
							<td>
								<?php
								
								if($store->status== 2)
								{
									echo "Edited";
								}
								if($store->status== 1)
								{
									echo "Publish";
								}
								if($store->status== 0)
								{
									echo "Draft";
								}
								/* else
								{
									echo "Draft";
								} */
								?>
							</td>
							<td class="action">
								<div class="action-buttons right">
									<?php icon_link('edit', 'admin/stores/edit/'.$store->id, 'Edit'); ?>
									<?php icon_link('delete', 'admin/stores/delete/'.$store->id, 'Delete'); ?>
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
<?php $this->load->view('part/footer'); ?>