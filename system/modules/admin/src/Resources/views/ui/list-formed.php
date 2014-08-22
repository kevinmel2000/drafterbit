<form method="POST" action="<?php echo $formAction; ?>">
    <?php echo $toolbars ?>

    <div class="container-fluid">
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
                        <?php echo $table; ?>
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