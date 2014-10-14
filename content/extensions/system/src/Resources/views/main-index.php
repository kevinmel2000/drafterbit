<?php $this->extend('@system/main'); ?>

<form method="POST" id="<?php echo $id; ?>-index-form" action="">
    <div class="container-fluid sticky-toolbar-wrapper" id="sticky-toolbar">
        <div class="container">
            <div class="row row-sticky-toolbar" style="margin:10px 0;">
                <div class="pull-right">
                    <div style="display:inline-block; float:right;margin-left:5px;">
                        <input type="search" class="form-control input-sm" placeholder="Search">
                    </div>

                    <?php echo $this->block('filter'); ?>
                </div>

                    <?php echo $this->block('action'); ?>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="row row-content">
           <div class="col-md-12 content-full">
                <div class="table-responsive">
                    <?php echo $this->block('content'); ?>
                </div>
            </div>
        </div>
     </div>
</form>