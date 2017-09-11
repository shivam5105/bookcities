<?php $this->load->view('part/header'); ?>
<div class="login-page inset">
	<div class="row cf">
		<div class="col c12">
			<a href="<?php echo admin_url('admin_users/create'); ?>" class="button medium right">Add New</a>
			<h1 class="page-title">Users - <?php echo $total_records; ?></h1>
		</div>
		<div class="toolbar cf">
			<div class="sort-order left">
			</div>
			<div class="paginate right"><?php echo $pagination; ?></div>
		</div>
	</div>
	<div class="row cf">
		<div class="col c12">
			<table class="events-table">
				<tr class="row-head">
					<th width="20">S.No.</th>
					<th>First Name</th>
					<th>Last Name</th>
					<th>Email</th>
					<th>Status</th>
					<th width="150"></th>
				</tr>
				<?php
				if(!empty($users))
				{
					$i=1;
					foreach($users as $user)
					{
						?>
						<tr class="tale-row">
							<td><?php echo ($skipped_records + $i); ?></td>
							<td>
								<?php echo $user->first_name; ?>
							</td>
							<td>
								<?php echo $user->last_name; ?>
							</td>
							<td>
								<?php echo $user->email; ?>
							</td>
							<td>
								<?php
								if($user->status == 1)
								{
									echo "<span style='color:green;'>Active</span>";
								}
								else if($user->status == 0)
								{
									echo "<span style='color:red;'>In-Active</span>";
								}
								?>
							</td>
							<td class="action">
								<div class="action-buttons right">
									<?php icon_link('edit', 'admin/admin_users/edit/'.$user->id, 'Edit'); ?>
									<a class="icon-link change-password" href="<?php echo base_url('admin/admin_users/change-password/'.$user->id); ?>" title="Change Password">Change Password</a>
									<?php icon_link('delete', 'admin/admin_users/delete/'.$user->id, 'Delete'); ?>
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
			</div>
			<div class="paginate right"><?php echo $pagination; ?></div>
		</div>
	</div>	
</div>
<?php $this->load->view('part/footer'); ?>