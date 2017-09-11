<?php $this->load->view('part/header'); ?>
<div class="login-page inset">
	<div class="row cf">
		<div class="col c12">
			<a href="<?php echo admin_url('books_categories/create'); ?>" class="button medium right">Add New</a>
			<h1 class="page-title">Books Categories - <?php echo $total_records; ?></h1>
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
					<th>Category Name</th>
					<th width="150"></th>
				</tr>
				<?php
				if(!empty($bookcategories))
				{
					$i=1;
					foreach($bookcategories as $category)
					{
						?>
						<tr class="tale-row">
							<td><?php echo ($skipped_records + $i); ?></td>
							<td>
								<a href="<?php echo admin_url('books_categories/edit/'.$category->id); ?>">
									<?php echo !empty($category->name) ? $category->name : "--"; ?>
								</a>
							</td>
							<td class="action">
								<div class="action-buttons right">
									<?php icon_link('edit', 'admin/books_categories/edit/'.$category->id, 'Edit'); ?>
									<?php icon_link('delete', 'admin/books_categories/delete/'.$category->id, 'Delete'); ?>
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