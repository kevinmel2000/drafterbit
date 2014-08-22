<div class="container">
	<div class="row row-content">
		<div class="col-md-6 content-full">
			<div class="form-group">
			    <label for="site-name" class="col-md-3 control-label">Sitename</label>
			    <div class="col-md-9">
			      <input name="site-name" type="text" class="form-control" placeholder="Site Name" value="<?php echo value('site-name', $siteName); ?>">
			    </div>
			 </div>
			 <div class="form-group">
			    <label for="site-tagline" class="col-md-3 control-label">Tag Line</label>
			    <div class="col-md-9">
			      <input name="site-tagline" type="text" class="form-control" placeholder="Tag Line" value="<?php echo value('site-tagline', $tagLine); ?>">
			      <span class="help-block">In a few words, explain what this site is about.</span>
			    </div>
			 </div>
			 <div class="form-group">
			    <div class="col-sm-offset-3 col-sm-9">
			      <div class="checkbox">
			        <label>
			          <input name="offline" type="checkbox" value="1" <?php echo checked('offline', 1, $offline) ?>> Offline
			        </label>
			      </div>
			    </div>
			</div>
			<div class="form-group">
			    <div class="col-sm-offset-3 col-md-9">
			      <textarea name="offline-message" class="form-control" placeholder="Offline message"><?php echo value('offline-message', $offlineMessage) ?></textarea>
			    </div>
			 </div>
			 <div class="form-group">
			    <label for="email" class="col-md-3 control-label">Email</label>
			    <div class="col-md-9">
			      <input type="text" name="email" class="form-control" placeholder="Email" value="<?php echo value('email', $adminEmail); ?>">
			      <span class="help-block">This address is used for admin purposes, like new user notification.</span>
			    </div>
			 </div>
		</div>
		<div class="col-md-6 content-full">
			<div class="form-group">
			    <label for="site-address" class="col-md-3 control-label">Site Address</label>
			    <div class="col-md-9">
			      <input name="site-address" type="text" class="form-control" placeholder="http://" value="<?php echo value('address', $address) ?>">
			    </div>
			 </div>
			<div class="form-group">
			    <label for="language" class="col-md-3 control-label">Language</label>
			    <div class="col-md-9">
			      <input name="language" type="text" class="form-control" placeholder="Language" value="<?php echo value('language', $language) ?>">
			    </div>
			 </div>
			 <div class="form-group">
			    <label for="timezone" class="col-md-3 control-label">Timezone	</label>
			    <div class="col-md-9">
			      <input name="timezone" type="text" class="form-control" placeholder="Timezone" value="<?php echo value('timezone', $timezone) ?>">
			    </div>
			 </div>
			 <div class="form-group">
			    <label for="format-date" class="col-md-3 control-label">Date Format</label>
			    <div class="col-md-9">
			      <input type="text" class="form-control" name="format-date" value="<?php echo value('format-date', $dateFormat) ?>">
			    </div>
			 </div>
			 <div class="form-group">
			    <label for="format-time" class="col-md-3 control-label">Time Format	</label>
			    <div class="col-md-9">
			      <input type="text" class="form-control" name="format-time" value="<?php echo value('format-time', $timeFormat) ?>">
			    </div>
			 </div>
		</div>
	</div>
</div>