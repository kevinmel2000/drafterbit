<table class="table table-hover" id="<?php echo $id ?>-data-table">
    <thead>
        <tr>
            <th class="sorting" width="15">
                <input id="<?php echo $id ?>-checkall" type="checkbox" name="<?php echo $name ?>[]" value="all">
            </th>
            <?php foreach ($thead as $th): ?>
                <th width="<?php echo $th->width; ?>" align="<?php echo $th->align; ?>"><?php echo $th->label; ?></th>
            <?php 
endforeach; ?>
        </tr>
    </thead>
</table>