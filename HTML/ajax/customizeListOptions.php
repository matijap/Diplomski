<div class="add-list-with-button-settings-panel">
<?php $widgetOptionID = $_GET['widgetOptionID'];
      $itemID         = $_GET['item'];
      $tempData       = isset($_GET['tempData']) ? $_GET['tempData'] : "";
      $decoded        = json_decode($tempData, true);
?>
    <input type="hidden" value="<?php echo $widgetOptionID; ?>" class="widgetOptionID">
    <input type="hidden" value="<?php echo $itemID; ?>" class="itemID">
    <div>
        <select class="float-left list-with-button-select">
            <option>Name</option>
            <option>Colours</option>
            <option>Founded</option>
            <option>From</option>
            <option>Date</option>
            <option>Venue</option>
            <option>Referees</option>
        </select>
        <a class="blue-button add-list-with-button-customize-option" href="javascript:void(0)">Add</a>
        <select class="float-left list-with-button-select-where-to">
            <option value="main">Main</option>
            <option value="additional">Additional</option>
        </select>
        <i class="fa fa-times float-right cursor-pointer close-list-with-button-settings-panel"></i>
    </div>
    <hr>
    <div class="list-with-button-settings-main">
        <h1 class="text-align-center">Main Settings</h1>
        <?php
        $count   = count($decoded['main']['left']);
        $arr     = array('left', 'right');
        foreach ($arr as $leftOrRight) {
        ?>
            <div class="bb lwbsm-<?php echo $leftOrRight; ?>">
                <?php
                    $counter    = 0;
                    $firstLabel = 'Name';
                    $firstName  = 'name_123456[]';
                    $firstVal   = '';
                    if ($count) {
                        $firstElem  = reset($decoded['main'][$leftOrRight]);
                        $firstLabel = $firstElem['label'];
                        $firstVal   = $firstElem['value'];
                        $firstName  = key($decoded['main'][$leftOrRight]);
                    }
                ?>
                <h2 class="text-align-center"><?php echo $firstVal ? $firstVal : 'Option Name'; ?></h2>
                <div class="list-with-button-settings-item to-be-removed">
                    <label class="truncate-string"><?php echo $firstLabel; ?></label>
                    <input type="text" class="list-with-button-settings-option-name" value="<?php echo $firstVal; ?>" name="<?php echo $firstName; ?>">
                </div>
                <div class="sortable-initialize insert-here">
                <?php
                if ($count > 1) {
                    foreach ($decoded['main'][$leftOrRight] as $key => $element) {
                        if ($key != $firstName) {
                            ?>
                            <div class="list-with-button-settings-item to-be-removed">
                                <label class="truncate-string"><?php echo $element['label']; ?></label>
                                <i data-closest="lwbsm-<?php echo $leftOrRight; ?>" data-item="item-56478" class="fa fa-times remove-item float-right m-l-5"></i>
                                <input type="text" value="<?php echo $element['value']; ?>" name="<?php echo $key; ?>">
                            </div>
                            <?php
                        }
                    }
                }
                ?>
                </div>
            </div>
        <?php
        }
        ?>
        <div class="clear"></div>
    </div>
    <div class="list-with-button-settings-additional">
        <div style="display:none;" class="to-be-removed"></div>
        <h1 class="text-align-center">Additional Settings</h1>
        <div class="bb p-10 sortable-initialize">
        <?php
        $count   = count($decoded['additional']);
        if ($count) {
            foreach ($decoded['additional'] as $key => $value) {
                ?>
                    <div class="list-with-button-settings-item to-be-removed">
                        <label class="truncate-string"><?php echo $value['label']; ?></label>
                        <i data-closest="list-with-button-settings-additional" class="fa fa-times remove-item float-right m-l-5"></i>
                        <input type="text" name="<?php echo $key; ?>" value="<?php echo $value['value']; ?>">
                    </div>
                <?php
            }
        }
        ?>
        </div>
    </div>
    <div>
        <a class="green-button lwb-save">Save</a>
    </div>
</div>