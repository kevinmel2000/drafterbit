<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $title ?> | <?php echo $siteName.' Administrator'; ?></title>
        <!-- Core CSS - Include with every page -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400' rel='stylesheet' type='text/css'>

        <?php $this->css(':fontawesome', ':fontawesome'); ?>
        <?php $this->css('
          :bootstrap_css,
          :notify_css,
          @system/css/overrides-bootstrap.css,
          @system/css/overrides-datatables.css,
          @system/css/style.css,
          @system/css/style-desktop.css,
          @system/css/style-mobile.css,
          @system/css/themes/customizer.css
        ') ?>
        
        <?php echo $this->block('css'); ?>
    </head>

    <body>
        <div id="iframe-container">
            <iframe name="preview" width="100%" height="100%" src="<?php echo base_url(); ?>">
            </iframe>
        </div>
        <div id="customizer">
            <div class="col-container">
                <div class="col">
                    <form action="<?php echo admin_url('setting/themes/custom-preview?csrf='.csrf_token()); ?>" method="post" id="customizer-form" class="customizer-ajax-form">
                    <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>"/>
                    <input type="hidden" name="url" value="<?php echo base_url(); ?>"/>
                    <div class="section">
                        <a href="javascript:close();" class="btn btn-default btn-xs">Close</a>
                        <button type="submit" name="action" value="save" class="btn btn-primary btn-xs pull-right">Save</button>
                        <button type="submit" name="action" value="update-preview" class="btn btn-primary btn-xs pull-right" style="margin-right:5px;">Update Preview</button>
                    </div>

                    <div class="section customizer-input" id="general-section">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent=".customizer-input" href="#collapseOne">
                                      Title &amp; Tagline
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse in">
                              <div class="panel-body">
                                <div class="form-group">
                                  <label>Title</label>
                                  <input class="form-control input-sm" type="text" name="general[title]" value="<?php echo $siteName; ?>"/>
                                </div>
                                <div class="form-group">
                                  <label>Tagline</label>
                                  <textarea class="form-control input-sm" type="text" name="general[tagline]"><?php echo $tagLine; ?></textarea>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                              <h4 class="panel-title">
                                <a class="menus-section" href="#">
                                  Navigation <i class="fa fa-angle-right pull-right"></i>
                                </a>
                              </h4>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                              <h4 class="panel-title">
                                <a class="widget-section" href="#">
                                  Widget <i class="fa fa-angle-right pull-right"></i>
                                </a>
                              </h4>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="col">
                    <div class="section">
                        <a href="#" class="btn btn-default btn-xs widget-section-back">Back</a>
                    </div>
                    <div class="section" id="menus-section">
                        <h2>Navigation</h2>
                        <?php foreach ($menuPositions as $pos): ?>
                             <div class="panel panel-default">
                                <div class="panel-heading">
                                  <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#menus-section" href="#<?php echo $pos ?>-menu-position">
                                      <?php echo $pos ?>
                                    </a>
                                  </h4>
                                </div>
                                <div id="<?php echo $pos ?>-menu-position" class="panel-collapse collapse">
                                  <div class="panel-body <?php echo $pos ?>-menu-container">
                                    <?php foreach ($menus[$pos] as $menu): ?>
                                         <div class="panel panel-default menu-item-container">
                                            <div class="panel-heading">
                                              <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent=".<?php echo $pos ?>-menu-container" href="#menu-<?php echo $menu->id ?>">
                                                  <?php echo $menu->label; ?>
                                                </a>
                                              </h4>
                                            </div>
                                            <div id="menu-<?php echo $menu->id ?>" class="panel-collapse collapse">
                                              <form action="<?php echo admin_url('setting/themes/menus/save'); ?>" class="menu-form" method="POST">
                                              <div class="panel-body">
                                                
                                                <div class="form-group">
                                                  <label>Label</label>
                                                  <input class="form-control input-sm menu-label" type="text" name="label" value="<?php echo $menu->label; ?>"/>
                                                </div>

                                                <div class="form-group">
                                                  <label>Type</label>
                                                  <select name="type" class="form-control input-sm menu-type">
                                                    <option value="1" <?php echo selected('type', 1, $menu->type == 1); ?>>Custom Link</option>
                                                    <option value="2" <?php echo selected('type', 2, $menu->type == 2); ?>>Page</option>
                                                  </select>
                                                </div>

                                                <div class="form-group menu-type-link" style="<?php echo hide('menu-type', 1, $menu->type == 1); ?>">
                                                  <label>Link</label>
                                                  <input class="form-control input-sm" type="text" name="link" value="<?php echo $menu->link; ?>"/>
                                                </div>
                                                
                                                <div class="form-group menu-type-page" style="<?php echo hide('menu-type', 2, $menu->type == 2); ?>">
                                                  <label>Page</label>
                                                  <select class="form-control input-sm" name="page">
                                                    <?php foreach ($pageOptions as $v => $label): ?>
                                                      <option <?php echo selected('page', $v, $menu->page == $v) ?> value="<?php echo $v ?>"><?php echo $label ?></option>
                                                    <?php endforeach;?>
                                                  </select>
                                                </div>

                                                <input type="hidden" name="id" value="<?php echo $menu->id; ?>">
                                                <input type="hidden" name="position" value="<?php echo $pos; ?>">
                                                <input type="hidden" name="theme" value="<?php echo $theme; ?>">
                                                <div class="form-group">
                                                  <button class="btn btn-xs btn-primary" type="submit">Save</button>
                                                  <a class="btn btn-xs delete-menu-item" href="#">Remove</a>
                                                </div>

                                              </div>
                                            </form>
                                            </div>
                                        </div>
                                    <?php endforeach ?>
                                    <div class="well well-sm" style="margin-top:5px;">
                                        <a data-position="<?php echo $pos; ?>" data-theme="<?php echo $theme; ?>" href="#" class="menu-adder">add menu item</a>
                                    </div>
                                  </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                    <div class="section" id="widget-section">
                        <h2>Widgets</h2>
                        <?php foreach ($widgetPositions as $pos): ?>
                             <div class="panel panel-default">
                                <div class="panel-heading">
                                  <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#widget-section" href="#<?php echo $pos ?>-widget-position">
                                      <?php echo $pos ?>
                                    </a>
                                  </h4>
                                </div>
                                <div id="<?php echo $pos ?>-widget-position" class="panel-collapse collapse">
                                  <div class="panel-body <?php echo $pos ?>-widget-container">
                                    <?php foreach ($widgets[$pos] as $widget): ?>
                                         <div class="panel panel-default">
                                            <div class="panel-heading">
                                              <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent=".<?php echo $pos ?>-widget-container" href="#widget-<?php echo $widget->id ?>">
                                                  <?php echo $widget->name; ?>
                                                </a>
                                              </h4>
                                            </div>
                                            <div id="widget-<?php echo $widget->id ?>" class="panel-collapse collapse">
                                              <div class="panel-body">
                                                  <?php echo $widget->ui; ?>
                                              </div>
                                            </div>
                                        </div>
                                    <?php endforeach ?>
                                    <div>
                                    </div>
                                    <div class="well well-sm" style="margin-top:5px;">
                                        <a data-toggle="modal" class="open-widget-dialog" href="#available-widget-dialog">add widget</a>
                                    </div>
                                  </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </div> <!--/customizer-->

        <div class="modal fade" id="available-widget-dialog">
          <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <?php foreach ($availableWidget as $widget): ?>
                        <div class="widget-item">
                            <h4><?php echo $widget->getName(); ?></h4>
                            <p>Test Description</p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
          </div>
        </div>

        <!-- script -->
        <script src="<?php echo asset_url('@vendor/jquery/dist/jquery.min.js'); ?>" /></script>
        <script src="<?php echo admin_url('system/drafterbit.js'); ?>" /></script>
        <?php $this->js(':bootstrap_js, :notify_js, :jquery_form, @system/js/layout.js, @system/js/customizer.js, :handlebars'); ?>
        <?php echo $this->block('js'); ?>

        <script>
        drafTerbit.initAjaxForm();

        <?php if(isset($messages)): ?>
                <?php foreach( $messages as $message ): ?>
                    msg = "<?php echo $this->escape($message['text'], 'js'); ?>";
                    $.notify(msg, "<?php echo $message['type'] == 'error' ? 'danger' : $message['type']; ?>");
                <?php endforeach; ?>
        <?php endif;?>
        </script>

        <script id="menu-item-template" type="text/x-handlebars-template">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
              <a href="#new-menu-{{id}}" data-parent=".main-menu-container" data-toggle="collapse" aria-expanded="false">
                unlabeled
              </a>
              </h4>
            </div>
            <div class="panel-collapse collapse" id="new-menu-{{id}}" aria-expanded="false">
                <form method="POST" class="menu-form" action="{{formAction}}">
                <div class="panel-body">
                  <div class="form-group">
                    <label>Label</label>
                    <input type="text" value="" name="label" class="form-control input-sm menu-label">
                  </div>

                  <div class="form-group">
                    <label>Type</label>
                    <select class="form-control input-sm menu-type" name="type">
                      <option value="1">Custom Link</option>
                      <option selected="selected" value="2">Page</option>
                    </select>
                  </div>

                  <div style="display:none" class="form-group menu-type-link">
                    <label>Link</label>
                    <input type="text" value="" name="link" class="form-control input-sm">
                  </div>
                  
                  <div style="display:block" class="form-group menu-type-page">
                    <label>Page</label>
                    <select name="page" class="form-control input-sm">
                        <?php foreach ($pageOptions as $v => $label): ?>
                          <option <?php echo selected('page', $v, $menu->page == $v) ?> value="<?php echo $v ?>"><?php echo $label ?></option>
                        <?php endforeach;?>
                    </select>
                  </div>

                  <input type="hidden" value="0" name="id">
                  <input type="hidden" value="{{position}}" name="position">
                  <input type="hidden" value="{{theme}}" name="theme">
                  <div class="form-group">
                    <button class="btn btn-xs btn-primary">Save</button>
                  </div>

                </div>
              </form>
              </div>
          </div>
        </script>
    </body>
</html>