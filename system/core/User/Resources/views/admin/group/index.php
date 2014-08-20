<form method="POST">
<div class="row">
    <div class="col-lg-12">
        <h2 style="display:inline-block">User Group <small>User Group and Role management <sup><a href="javascript:alert('soon...');"><i class="fa fa-question-circle"></a></i></sup></small></h2>
        <div class="pull-right" style="margin:20px 0;">
            <input class="btn btn-default" type="submit" name="action" value="Delete" />
            <a href="<?php echo base_url('admin/user/group/create'); ?>" class="btn btn-primary">Add New</a>
        </div>
    </div>
    <!-- /.col-lg-12 -->
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="group-data-table">
                    <thead>
                        <tr>
                            <th class="sorting"><input id="groups" type="checkbox" name="groups[]" value="all"></th>
                            <th>Group</th>
                            <th width="70%">Description</th>
                        </tr>
                    </thead>
                    <?php if($groups): ?>
                    <tbody>
                        <?php foreach ($groups as $group):?>
							<tr class="odd gradeX">
								<td><input type="checkbox" name="groups[]" value="<?php echo $group->id; ?>"></td>
								<td><a href="<?php echo base_url("admin/user/group/edit/{$group->id}"); ?>"><?php echo $group->label ?></td>
                                <td><?php echo $group->description ?></td>
							</tr>
						<?php endforeach;?>
                    </tbody>
                	<?php endif; ?>
                </table>
            </div>
            <!-- /.table-responsive -->
            </div>
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
</form>