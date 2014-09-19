<!--<link rel="stylesheet" href="<?php echo base_url('assets/admin/plugins/bootstrap-datatables/bootstrap-datatables.css') ?>"/>-->

<form method="POST">
<div class="row">
    <div class="col-lg-12">
        <div class="pull-right" style="margin:20px 0;">
            <input class="btn btn-default" type="submit" name="action" value="Clear"/>
            <input class="btn btn-default" type="submit" name="action" value="Delete"/>
        </div>
        <h2 style="display:inline-block">Log</h2>
    </div>
    <!-- /.col-lg-12 -->
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="log-data-table">
                    <thead>
                        <tr>
                            <th class="sorting" ><input id="logs" type="checkbox" name="logs[]" value="all"></th>
                            <th width="">Time</th>
                            <th width="75%">Message</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $log):?>
							<tr class="odd gradeX">
								<td><input type="checkbox" name="logs[]" value="<?php echo $log->id; ?>"></td>
								<td><?php echo date('d/m/Y H:m:s', $log->time); ?></td>
								<td><?php echo $log->message; ?></a></td>
							</tr>
						<?php endforeach;?>
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
<!-- /.row -->
</form>



<!--<script type="text/javascript" src="<?php echo base_url('assets/admin/plugins/jquery-check-all/jquery-check-all.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/admin/plugins/datatables/media/js/jquery.dataTables.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/admin/plugins/bootstrap-datatables/bootstrap-datatables.js')?>"></script>-->