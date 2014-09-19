<div class="container-fluid" style="border-bottom:1px solid #E5E5E5;">

<div class="row row-content">
    <div class="col-lg-12 content-full">
        <ol class="breadcrumb">
            <li><a href="/admin/files"><i class="fa fa-cloud"></i></a></li>
            <?php foreach($subpaths as $path): ?>
            <li><a href="<?php echo $path->path ?>"><?php echo $path->name ?></a></li>
            <?php endforeach; ?>
        </ol>

        <div class="panel panel-default">
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="files-data-table">
                    <thead>
                        <tr>
                            <th class="sorting" ><input id="files" type="checkbox"></th>
                            <th width="70%">File Name</th>
                            <th width="10%">Type</th>
                            <th width="20%">Last Modified</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($files): ?>
                        <?php foreach ($files as $file):?>
                            <tr>
                                <td><input type="checkbox" name="files[]" value="<?php echo $file->name; ?>"></td>
                                <td class="<?php echo $file->isDir ? 'folder' : 'file' ?> item" data-toggle="context" data-target="<?php echo $file->isDir ? '#folder-context' : '#file-context' ?>" >
                                        <a href="<?php echo $file->isDir ? '/admin/files/index/'.$file->path : files_url($file->path); ?>">
                                        <i class="fa fa-<?php echo $file->isDir ? 'folder-o' : 'file-o' ?>"></i>
                                        <?php echo $file->name; ?>
                                    </a>
                                </td>
                                <td><?php echo $file->type; ?></td>
                                <td><?php echo date("d/m/ Y H:i:s", $file->mTime); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- /.table-responsive -->
        </div>
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>

<div id="file-context">
    <ul class="dropdown-menu" role="menu">
        <li><a tabindex="-1" id="rename" href="#">Rename</a></li>
        <li><a tabindex="-1" id="delete" href="#">Delete&hellip;</a></li>
        <li><a tabindex="-1" id="move" href="#">Move&hellip;</a></li>
        <li><a tabindex="-1" id="Copy" href="#">Copy&hellip;</a></li>
        <li><a tabindex="-1" id="download" href="#">Download</a></li>
    </ul>
</div>

<div id="folder-context">
    <ul class="dropdown-menu" role="menu">
        <li><a tabindex="-1" id="rename" href="#">Rename</a></li>
        <li><a tabindex="-1" id="delete" href="#">Delete&hellip;</a></li>
        <li><a tabindex="-1" id="move" href="#">Move&hellip;</a></li>
        <li><a tabindex="-1" id="Copy" href="#">Copy&hellip;</a></li>
        <li><a tabindex="-1" id="download" href="#">Download</a></li>
    </ul>
</div>

</div>

<style>
/* @todo styling upload progress */
.progress { position:relative; width:400px; border: 1px solid #ddd; padding: 1px; border-radius: 3px; }
.bar { width:0%; height:20px; border-radius: 3px; }
.percent { position:absolute; display:inline-block; top:3px; left:48%; }
</style>
<!-- Modal -->
<div class="modal fade" id="upload-modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    <form id="upload-form" action="<?php echo base_url('admin/files/upload') ?>" method="post" enctype="multipart/form-data">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Upload file(s)</h4>
      </div>
      <div class="modal-body">
        <input type="file" name="files[]" multiple>
        <input type="hidden" name="rel-path" value="<?php echo $relPath; ?>">
        <div class="progress">
            <div class="bar bg-success"></div >
            <div class="percent">0%</div >
        </div>
        
        <div id="status"></div>
      </div>
      <div class="modal-footer">
        <a href="#" data-dismiss="modal">Cancel</a> or 
        <input type="submit" class="btn btn-primary" value="Submit"/>
      </div>
    </form>
    </div>
  </div>
</div>