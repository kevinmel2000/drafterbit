<div class="container-fluid">
<div class="row row-header">
	<div class="col-md-6 content-full">
		<div class="form-group">
    		<label for="real-name" class="col-md-4 control-label">Real Name</label>
    		<div class="col-md-8">
     			<input name="real-name" type="text" class="form-control" placeholder="Real Name" value="<?php echo value('real-name', $realName); ?>">
    		</div>
		</div>
		<div class="form-group">
		    <label for="website" class="col-md-4 control-label">Website</label>
		    <div class="col-md-8">
		      <input name="website" type="text" class="form-control" placeholder="http://" value="<?php echo value('website', $website); ?>">
		    </div>
		</div>
		<div class="form-group">
		    <label for="website" class="col-md-4 control-label">Bio</label>
		    <div class="col-md-8">
		      <textarea name="bio" class="form-control"><?php echo value('bio', $bio); ?></textarea>
		      <span class="help-block">A little biographical information to fill out your profile. This may be shown publicly.</span>
		    </div>
		</div>
		<div class="form-group">
		    <label for="groups" class="col-md-4 control-label">Group</label>
		    <div class="col-md-8">
		      	<select name="groups[]" multiple id="user-group" class="form-control" data-placeholder="Select Group">
		      		<?php foreach ($groupOptions as $option):?>
		      		<option <?php echo selected('groups', $option->id, in_array($option->id, $groupIds)); ?> value="<?php echo $option->id ?>"><?php echo $option->label; ?></option>
			      	<?php endforeach?>
		      	</select>
		    </div>
		</div>
	</div>
	<div class="col-md-6 content-full">
		<div class="form-group">
		    <label for="email" class="col-md-4 control-label">Email</label>
		    <div class="col-md-8">
		      <input name="email" type="email" class="form-control" placeholder="Email" value="<?php echo value('email', $email); ?>">
		    </div>
		</div>
		<div class="form-group">
		    <label for="password" class="col-md-4 control-label">Password</label>
		    <div class="col-md-8">
		      <input name="password" type="password" class="form-control">
		      <span class="help-block">If you would like to change the password type a new one. Otherwise leave this blank.</span>
		    </div>
		</div>
		<div class="form-group">
		    <label for="password" class="col-md-4 control-label">Repeat Password</label>
		    <div class="col-md-8">
		      <input name="password-confirm" type="password" class="form-control">
		      <span class="help-block">Type your new password again</span>
		    </div>
		</div>
		<div class="form-group">
		    <div class="col-md-offset-3 col-md-10">
		      <div class="checkbox">
		        <label>
		          <input <?php echo checked('active', '0', ($active == 0)) ?> name="active" value="0" type="checkbox"> Nonactive
		        </label>
		      </div>
		    </div>
		</div>
	</div>
</div>
</div>
