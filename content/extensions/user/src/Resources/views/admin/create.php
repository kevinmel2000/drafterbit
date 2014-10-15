<?php $this->extend('@system/main-edit') ?>

<?php $this->css(':chosen_css', ':chosen_css'); ?>
<?php $this->css(':chosen_bootstrap_css'); ?>

<div class="row">
	<div class="col-md-4">
		<div class="form-group">
		    <label for="email" class="control-label">Email</label>
		    <input name="email" type="email" class="form-control" placeholder="Email" value="<?php echo value('email'); ?>">
		</div>
		<div class="form-group">
		    <label for="password" class="control-label">Password</label>
		    <input name="password" autocomplete="off" type="password" class="form-control">
		</div>
		<div class="form-group">
		    <label for="password" class="control-label">Repeat Password</label>
		    <input name="password-confirm" type="password" class="form-control">
		</div>
	</div>
	<div class="col-md-4">
		 <div class="form-group">
		    <label for="groups" class="control-label">Group</label>
	      	<select name="groups[]" multiple id="user-group" class="form-control" data-placeholder="Select Group">
	      		<?php foreach ($groupOptions as $option):?>
	      		<option <?php echo selected('groups', $option->id); ?> value="<?php echo $option->id ?>"><?php echo $option->label; ?></option>
		      	<?php endforeach?>
	      	</select>
		</div>
		 <div class="form-group">
		      <div class="checkbox">
		        <label>
		          <input name="send-password" value="1" type="checkbox" <?php echo checked('send-password'); ?>> Send password by email
		        </label>
		      </div>
		</div>
		<div class="form-group">
		      <textarea name="mail-message" class="form-control" placeholder="mail message"><?php echo value('mail-massage'); ?></textarea>
		      <span class="help-block">Use '%s'(percent and 's') in your message to include the password. e.g: Your password is %s</span>
		 </div>
		<div class="form-group">
			<input type="submit" class="btn btn-primary pull-right" name="action" value="Submit"/>
		 </div>
	</div>
</div>

<?php $this->js(':chosen_js, @user/js/create.js'); ?>