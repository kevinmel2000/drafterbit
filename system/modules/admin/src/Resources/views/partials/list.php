    <!-- Header -->
    <div class="container-fluid row-header-container">
        <div class="row row-header">
            <div class="col-lg-12">
                <div class="pull-right" style="margin:20px 0;">
                    <a href="#" class="btn btn-default soon btn-sm"><i class="fa fa-gear"></i></a>
                </div>
                <h3 style="display:inline-block">
                    <?php echo $title; ?>
                    <small><?php echo $subTitle; ?> <sup><a href="#" class="soon"><i class="fa fa-question-circle"></i></a></sup></small>
                </h3>
            </div>
            <!-- /.col-lg-12 -->
        </div>
    </div>

<form method="POST" action="<?php echo $formAction; ?>">
    <div class="container-fluid sticky-toolbar-wrapper" id="sticky-toolbar">
        <div class="row row-sticky-toolbar" style="margin:10px 30px;">
            <div class="pull-right">
                <?php foreach ($toolbars as $btn): ?>
                    <?php if($btn->type == 'a'): ?>
                        <a href="<?php $btn->href ?>" class="btn btn-success btn-xs">
                            <?php if($btn->faClass): ?>
                                <i class="fa <?php echo $btn->faClass ?>"></i>
                            <?php endif;?>
                            <?php echo $btn->label ?>
                        </a>
                    <?php elseif($btn->type == 'submit'): ?>
                        <button class="btn btn-default btn-xs" type="submit" name="<?php echo $btn->name ?>" value="<?php echo $btn->value; ?>">
                            <?php if($btn->faClass): ?>
                                <i class="fa <?php echo $btn->faClass ?>"></i>
                            <?php endif;?>
                            <?php echo $btn->label ?>
                        </button>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <a href="#" class="btn btn-default btn-xs pull-left soon"><i class="fa fa-question-circle"></i> Help</a>
        </div>
    </div>

    <div class="container-fluid" style="border-bottom:1px solid #E5E5E5;">
        <div class="row row-content">
            <?php if($relatedLinks): ?>
                <div class="col-md-2 side-nav-container">
                    <ul class="nav nav-pills nav-stacked">
                        <?php foreach($relatedLinks as $link): ?>
                            <li> <a href="<?php echo $link->href ?>"><?php echo $link->label ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="col-md-10 content">
            <?php else: ?>
               <div class="col-md-12 content-full">
            <?php endif; ?>
                <div class="panel panel-default">
                <div class="panel-body">
                    <div class="table-responsive">
                        <?php echo $list; ?>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-12 -->
        </div>
    <!-- /.row -->
    </div>
<!-- /.container-fluid -->
</form>