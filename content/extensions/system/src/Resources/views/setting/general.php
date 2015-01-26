<?php $this->extend('@system/main-edit'); ?>

<?php $this->start('action'); ?>
<button class="btn btn-sm btn-success" type="submit" name="action" value="save">
    <i class="fa fa-check"></i> Save
</button>
<?php $this->end(); ?>

<div role="tabpanel">

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#general-setting" aria-controls="general" role="tab" data-toggle="tab">General</a></li>
    <li role="presentation"><a href="#mail-server" aria-controls="mail-server" role="tab" data-toggle="tab">Mail Server</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content" style="padding:20px 10px;">
    <div role="tabpanel" class="tab-pane active" id="general-setting">
      <div class="row">
        <div class="col-md-4">
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
                        <?php foreach ($pageOptions as $value => $label) : ?>
                          <option <?php echo selected('homepage', $value, $homepage == $value); ?> value="<?php echo $value ?>"><?php echo $label ?></option>
                        <?php endforeach; ?>
                  </select>
             </div>
            <div class="form-group">
                <label for="email" class="control-label">Email</label>
                  <input type="text" name="email" class="form-control" placeholder="Email" value="<?php echo value('email', $adminEmail); ?>">
                  <span class="help-block">This address is used for admin purposes, like new user notification.</span>
             </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="language" class="control-label">Language</label>
                <select name="language" class="form-control">
                  <?php foreach($languageList as $lang): ?>
                    <option <?php echo selected('language', $lang, $language == $lang); ?> value="<?php echo $lang ?>"> <?php echo $lang; ?></option>
                  <?php endforeach; ?>
                </select>
             </div>
             <div class="form-group">
                <label for="timezone" class="control-label">Timezone    </label>
                  <select name="timezone" class="form-control">
                    <?php foreach ($timezoneIdList as $key) : ?>
                      <option <?php echo selected('timezone', $key, $timezone == $key); ?> value="<?php echo $key ?>"><?php echo $key ?></option>
                    <?php endforeach; ?>
                  </select>
             </div>
             <div class="form-group">
                <label for="format-date" class="control-label">Date Format</label>
                  <input type="text" class="form-control" name="format-date" value="<?php echo value('format-date', $dateFormat) ?>">
             </div>
             <div class="form-group">
                <label for="format-time" class="control-label">Time Format    </label>
                  <input type="text" class="form-control" name="format-time" value="<?php echo value('format-time', $timeFormat) ?>">
             </div>
        </div>
      </div>

    </div>
    <div role="tabpanel" class="tab-pane" id="mail-server">
      
         <div class="col-md-4 content-full">
            <div class="form-group">
                <label for="smtp-host" class="control-label">SMTP Host</label>
                  <input name="smtp-host" type="text" class="form-control" value="<?php echo $smtpHost ?>">
             </div>
             <div class="form-group">
                <label for="smtp-port" class="control-label">SMTP Port</label>
                  <input name="smtp-port" type="text" class="form-control" value="<?php echo $smtpPort ?>">
             </div>
             <div class="form-group">
                <label for="smtp-user" class="control-label">SMTP User</label>
                  <input type="text" class="form-control" name="smtp-user" value="<?php echo $smtpUser ?>">
             </div>
             <div class="form-group">
                <label for="smtp-pass" class="control-label">SMTP Password</label>
                  <input type="password" class="form-control" name="smtp-pass" value="<?php echo $smtpPass ?>">
             </div>
        </div>
      </div>

    </div>
  </div>

</div>