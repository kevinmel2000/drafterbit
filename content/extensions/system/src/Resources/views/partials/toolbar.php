<div class="container-fluid sticky-toolbar-wrapper" id="sticky-toolbar">
    <div class="container">
            <div class="row row-sticky-toolbar" style="margin:10px 0;">
                <div class="pull-right">

                    <?php if ($search) : ?>
                    <div style="display:inline-block; float:right;margin-left:5px;">
                        <input type="search" class="form-control input-sm" placeholder="Search">
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($filters) : ?>
                    <?php foreach ($filters as $filter) : ?>
                    <div style="display:inline-block; float:right;margin-left:5px;">
                    <select name="filter" class="form-control input-sm">
                        <?php foreach ($filter as $key => $value) : ?>
                            <option><?php echo $value ?></option>
                        <?php endforeach ?>
                    </select>
                    </div>
                    <?php endforeach ?>
                    <?php endif; ?>

                    <?php foreach ($toolbars['right'] as $btn) : ?>
                        <?php if ($btn->type == 'a') : ?>
                            <a href="<?php echo $btn->href ?>" class="btn btn-<?php echo $btn->classType ?> btn-sm">
                                <?php if ($btn->faClass) : ?>
                                    <i class="fa <?php echo $btn->faClass ?>" style="<?php echo isset($btn->faStyle) ? $btn->faStyle : '' ?>"></i>
                                <?php endif;?>
                                <?php echo $btn->label ?>
                            </a>
                        <?php elseif ($btn->type == 'submit') : ?>
                            <button class="btn btn-<?php echo $btn->classType ?> btn-sm" type="submit" name="<?php echo $btn->name ?>" value="<?php echo $btn->value; ?>">
                                <?php if ($btn->faClass) : ?>
                                    <i class="fa <?php echo $btn->faClass ?>"></i>
                                <?php endif;?>
                                <?php echo $btn->label ?>
                            </button>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <?php foreach ($toolbars['left'] as $btn) : ?>
                    <?php if ($btn->type == 'a') : ?>
                        <a href="<?php echo $btn->href ?>" class="btn btn-<?php echo $btn->classType ?> btn-sm">
                            <?php if ($btn->faClass) : ?>
                                <i class="fa <?php echo $btn->faClass ?>" style="<?php echo isset($btn->faStyle) ? $btn->faStyle : '' ?>"></i>
                            <?php endif;?>
                            <?php echo $btn->label ?> </a>
                    <?php elseif ($btn->type == 'submit') : ?>
                        <button class="btn btn-<?php echo $btn->classType ?> btn-sm" type="submit" name="<?php echo $btn->name ?>" value="<?php echo $btn->value; ?>">
                            <?php if ($btn->faClass) : ?>
                                <i class="fa <?php echo $btn->faClass ?>"></i>
                            <?php endif;?>
                            <?php echo $btn->label ?>
                        </button>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
    </div>
</div>