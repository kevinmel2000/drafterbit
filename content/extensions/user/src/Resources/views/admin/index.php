<form method="POST">
<div class="row">
    <div class="col-lg-12">
        <div class="pull-right" style="margin:20px 0;">
            <input class="btn btn-default" type="submit" name="action" value="Delete"/>
            <a href="<?php echo base_url('admin/user/create'); ?>" class="btn btn-primary">Add New</a>
        </div>
        <h2 style="display:inline-block">Users <small>User management <sup><a href="javascript:alert('soon...');"><i class="fa fa-question-circle"></a></i></sup></small></h2>
    </div>
    <!-- /.col-lg-12 -->
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="user-data-table">
                    <thead>
                        <tr>
                            <th class="sorting"><input id="users" type="checkbox" name="users[]" value="all"></th>
                            <th width="50%">Name</th>
                            <th>Email</th>
                            <th>Group</th>
                        </tr>
                    </thead>
                    <?php if($users): ?>
                    <tbody>
                        <?php foreach ($users as $user):?>
							<tr class="odd gradeX">
								<td><input <?php echo checked('users', $user->id); ?> type="checkbox" name="users[]" value="<?php echo $user->id; ?>"></td>
								<td><a href="<?php echo base_url("admin/user/edit/{$user->id}"); ?>"><?php echo $user->real_name?:$user->email; ?></td>
                                <td><?php echo $user->email; ?></td>
								<td>
                                    <?php foreach ($user->groups as $group): ?>
                                        <span class="label label-default"><?php echo $group->label; ?></span>
                                    <?php endforeach;?>
                                </td>
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
</form>
<!-- /.row -->