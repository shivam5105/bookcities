<style type="text/css">
	table td
	{
		padding: 10px;
	}
	input,
	select
	{
		padding: 10px;
		width: 450px;
		font-size: 16px;
	}
	[type="submit"]
	{
		font-size: 22px;
		background: #fff;
		cursor: pointer;
		outline: 0;
		width: 100%;
	}
</style>
<?php echo form_open(); ?>
	<table border="0">
		<tr>
			<td align="right"><b>Store:<b></td>
			<td>
				<select name="store_id">
					<?php
					foreach ($stores as $key => $store)
					{
						$select = "";
						if($store_id == $store->id)
						{
							$select = "selected='selected'";
						}
						?>
						<option value="<?php echo $store->id; ?>" <?php echo $select; ?>><?php echo $store->name; ?></option>
						<?php
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td align="right"><b>Platform:<b></td>
			<td>
				<select name="platform_name">
					<option value="ios" <?php if($platform_name == 'ios') { echo "selected='selected'"; }?>>iOS</option>
				</select>
			</td>
		</tr>
		<tr>
			<td align="right"><b>Device Token:<b></td>
			<td>
				<input type="text" class="text" name="device_token" value="<?php echo $device_token; ?>" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="submit" value="Send" />
			</td>
		</tr>
	</table>
<?php echo form_close();?>