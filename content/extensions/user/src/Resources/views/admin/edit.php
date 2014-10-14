<div class="container">
<div class="row row-header">
	<div class="col-md-6 content-full">
		<div class="form-group">
    		<label for="real-name" class="control-label">Real Name</label>
     			<input name="real-name" type="text" class="form-control" placeholder="Real Name" value="<?php echo value('real-name', $realName); ?>">
		</div>
		<div class="form-group">
		    <label for="website" class="control-label">Website</label>
		      <input name="website" type="text" class="form-control" placeholder="http://" value="<?php echo value('website', $website); ?>">
		</div>
		<div class="form-group">
		    <label for="website" class="control-label">Bio</label>
		     <textarea name="bio" class="form-control"><?php echo value('bio', $bio); ?></textarea>
		     <span class="help-block">A little biographical information to fill out your profile. This may be shown publicly.</span>
		</div>
		<div class="form-group">
		    <label for="groups" class="control-label">Group</label>
	      	<select name="groups[]" multiple id="user-group" class="form-control" data-placeholder="Select Group">
	      		<?php foreach ($groupOptions as $option):?>
	      		<option <?php echo selected('groups', $option->id, in_array($option->id, $groupIds)); ?> value="<?php echo $option->id ?>"><?php echo $option->label; ?></option>
		      	<?php endforeach?>
	      	</select>
		</div>
	</div>
	<div class="col-md-6 content-full">
		<div class="form-group">
		    <label for="email" class="control-label">Email</label>
		    <input name="email" type="email" class="form-control" placeholder="Email" value="<?php echo value('email', $email); ?>">
		</div>
		<div class="form-group">
		    <label for="password" class="control-label">Password</label>
		    <input name="password" type="password" class="form-control" autocomplete="off">
		    <span class="help-block">If you would like to change the password type a new one. Otherwise leave this blank.</span>
		</div>
		<div class="form-group">
		    <label for="password" class="control-label">Password Again</label>
		    <input name="password-confirm" type="password" class="form-control">
		    <span class="help-block">Type your new password again</span>
		</div>
		<div class="form-group">
		      <div class="checkbox">
		        <label>
		          <input <?php echo checked('active', '0', ($active == 0)) ?> name="active" value="0" type="checkbox"> Nonactive
		        </label>
		      </div>
		</div>
	</div>
</div>
</div>
