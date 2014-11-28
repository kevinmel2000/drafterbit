<?php $this->extend('@system/main-edit'); ?>

<?php $this->start('action'); ?>
<button class="btn btn-sm btn-success" type="submit" name="action" value="save">
	<i class="fa fa-check"></i> Save
</button>
<?php $this->end(); ?>

<div class="row row-content">
	<div class="col-md-6 content-full">
		<div class="form-group">
		    <label for="site-name" class="control-label">Sitename</label>
		      <input name="site-name" type="text" class="form-control" placeholder="Site Name" value="<?php echo value('site-name', $siteName); ?>">
		 </div>
		 <div class="form-group">
		    <label for="site-tagline" class="control-label">Tag Line</label>
		      <input name="site-tagline" type="text" class="form-control" placeholder="Tag Line" value="<?php echo value('site-tagline', $tagLine); ?>">
		      <span class="help-block">In a few words, explain what this site is about.</span>
		 </div>
		 <div class="form-group">
		    <label for="format-time" class="control-label">Front Page</label>
		      <select class="form-control" name="homepage">
		      	<?php foreach($pageOptions as $value => $label): ?>
		      		<option <?php echo selected('homepage', $value, $homepage == $value ); ?> value="<?php echo $value ?>"><?php echo $label ?></option>
	   			<?php endforeach; ?>
		      </select>
		 </div>
		<div class="form-group">
		    <label for="email" class="control-label">Email</label>
		      <input type="text" name="email" class="form-control" placeholder="Email" value="<?php echo value('email', $adminEmail); ?>">
		      <span class="help-block">This address is used for admin purposes, like new user notification.</span>
		 </div>
	</div>
	<div class="col-md-6 content-full">
		<div class="form-group">
		    <label for="language" class="control-label">Language</label>
		      <input name="language" type="text" class="form-control" placeholder="Language" value="<?php echo value('language', $language) ?>">
		 </div>
		 <div class="form-group">
		    <label for="timezone" class="control-label">Timezone	</label>
		      <input name="timezone" type="text" class="form-control" placeholder="Timezone" value="<?php echo value('timezone', $timezone) ?>">
		 </div>
		 <div class="form-group">
		    <label for="format-date" class="control-label">Date Format</label>
		      <input type="text" class="form-control" name="format-date" value="<?php echo value('format-date', $dateFormat) ?>">
		 </div>
		 <div class="form-group">
		    <label for="format-time" class="control-label">Time Format	</label>
		      <input type="text" class="form-control" name="format-time" value="<?php echo value('format-time', $timeFormat) ?>">
		 </div>
	</div>
</div>
