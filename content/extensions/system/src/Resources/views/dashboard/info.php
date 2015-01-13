<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Info</h3>
        </div>
        <div class="panel-body">
            <ul class="list-group">
                <li class="list-group-item"><i class="fa fa-desktop"></i> OS <?php echo $os; ?></li>
                <li class="list-group-item"><i class="fa fa-gears"></i> PHP <?php echo phpversion(); ?></li>
                <li class="list-group-item"><i class="fa fa-database"></i> <?php echo $db; ?></li>
                <li class="list-group-item"><i class="fa fa-users"></i> User(s) : <?php echo $usersCount ?></li>
                <li class="list-group-item"><i class="fa fa-clock-o"></i> Time <?php echo date('H:i:s'); ?></li>
            </ul>
        </div>
    </div>
</div>