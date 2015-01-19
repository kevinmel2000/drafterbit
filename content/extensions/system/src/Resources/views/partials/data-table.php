<table class="table table-hover" id="<?php echo $id ?>-data-table">
    <thead>
        <tr>
            <th class="sorting" width="15">
                <input id="<?php echo $id ?>-checkall" type="checkbox">
            </th>
            <?php foreach ($thead as $th) :
?>
                <th width="<?php echo $th['width'];
?>" align="<?php echo $th['align'];
?>"><?php echo $th['label']; ?></th>
            <?php
endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $row) :
?>
            <tr class="odd gradeX">
                <td><input type="checkbox" name="<?php echo $id ?>[]" value="<?php echo $row['id']; ?>"></td>
                <?php foreach ($thead as $th) :
?>
                <td><?php echo $row['values'][$th['id']]; ?></td>
                <?php
endforeach; ?>
            </tr>
        <?php
endforeach;?>
    </tbody>
</table>