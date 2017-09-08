<?php echo form_open("app/api/reg_token"); ?>

<label>current_token</label>
<input type="text" class="text" name="current_token" value=""  /><br />

<label>platform</label>
<input type="text" class="text" name="platform" value=""  /><br />

<label>prev_token</label>
<input type="text" class="text" name="prev_token" value=""  /><br />

<input  class="button primary" type="submit" value="Submit" />

<?php echo form_close();?>