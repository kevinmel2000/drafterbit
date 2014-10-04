<?php echo $header; ?>

<form method="POST">
<div class="container-fluid sticky-toolbar-wrapper" id="sticky-toolbar">
    <div class="container">
        <div class="row row-sticky-toolbar" style="margin:10px 0;">
            <div class="pull-right">
                <div style="display:inline-block; float:right;margin-left:5px;">
                    <input type="search" class="form-control input-sm" placeholder="Search">
                </div>

                <div style="display:inline-block; float:right;margin-left:5px;">
                    <select name="filter" class="form-control input-sm pages-status-filter">
                            <option value="untrashed" <?php echo selected('status-filter', 'untrashed', $status == 'untrashed' ); ?> >- Status -</option>
                            <option value="unpublished" <?php echo selected('status-filter', 'unpublished', $status == 'unpublished'); ?> >Unpublished</option>
                            <option value="published" <?php echo selected('status-filter', 'published', $status == 'published'); ?> >Published</option>
                            <option value="trashed" <?php echo selected('status-filter', 'trashed', $status == 'trashed'); ?> >Trashed</option>
                    </select>
                </div>
            </div>

            <a href="<?php echo admin_url('pages/create') ?>" class="btn btn-success btn-sm">
                <i class="fa fa-plus" style=""></i> New Page
            </a>

            <button class="btn btn-default btn-sm uncreate-action" type="submit" name="action" value="trash">
                <i class="fa fa-trash-o"></i> Trash
            </button>
        </div>
    </div>
</div>
 <div class="container">
    <div class="row row-content">
       <div class="col-md-12 content-full">
            <div class="table-responsive">
                <?php echo $table; ?>
            </div>
        </div>
    </div>
</div>
</form>