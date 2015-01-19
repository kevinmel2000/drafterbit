<div class="">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Info</h3>
        </div>
        <div class="panel-body">
            <ul class="list-group">
                <?php foreach ($stat as $k => $v) :
?>
                <li class="list-group-item"><strong><?php echo $k;
?> : </strong> <?php echo $v; ?></li>
                <?php
endforeach; ?>
            </ul>
        </div>
    </div>
</div>