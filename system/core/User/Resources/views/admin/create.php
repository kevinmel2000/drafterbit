<div class="row">
	<div class="col-md-6 col-md-offset-1">

		<div class="form-group">
		    <label for="email" class="col-md-3 control-label">Email</label>
		    <div class="col-md-9">
		      <input name="email" type="email" class="form-control" placeholder="Email" value="<?php echo value('email'); ?>">
		    </div>
		</div>
		<div class="form-group">
		    <label for="password" class="col-md-3 control-label">Password</label>
		    <div class="col-md-9">
		      <input name="password" type="password" class="form-control">
		    </div>
		</div>
		<div class="form-group">
		    <label for="password" class="col-md-3 control-label">Repeat Password</label>
		    <div class="col-md-9">
		      <input name="password-confirm" type="password" class="form-control">
		    </div>
		</div>
		 <div class="form-group">
		    <div class="col-sm-offset-3 col-sm-9">
		      <div class="checkbox">
		        <label>
		          <input name="send-password" value="1" type="checkbox" <?php echo checked('send-password'); ?>> Send password by email
		        </label>
		      </div>
		    </div>
		</div>
		<div class="form-group">
		    <div class="col-sm-offset-3 col-md-9">
		      <textarea name="mail-message" class="form-control" placeholder="mail message"><?php echo value('mail-massage'); ?></textarea>
		      <span class="help-block">Use '%s'(percent and 's') in your message to include the password. e.g: Your password is %s</span>
		    </div>
		 </div>
		 <div class="form-group">
		    <label for="groups" class="col-md-3 control-label">Group</label>
		    <div class="col-md-9">
		      	<select name="groups[]" multiple id="user-group" class="form-control" data-placeholder="Select Group">
		      		<?php foreach ($groupOptions as $option):?>
		      		<option <?php echo selected('groups', $option->id); ?> value="<?php echo $option->id ?>"><?php echo $option->label; ?></option>
			      	<?php endforeach?>
		      	</select>
		    </div>
		</div>
		<div class="form-group">
		    <div class="col-sm-offset-3 col-md-9">
		    	<?php if(isset($justSaved)): ?>
		            <a href="<?php echo base_url('admin/user/create'); ?>" class="btn btn-primary btn-sm pull-right">Start Over</a>
				<?php else: ?>
			    	<input type="submit" class="btn btn-primary btn-sm pull-right" name="action" value="Save User"/>
				<?php endif ?>
		    </div>
		 </div>
	</div>
</div>