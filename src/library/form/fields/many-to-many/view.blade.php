<div class="control-group row-fluid">
    <div class="span3">
        <label class="control-label" for="<?= $field->column_name ?>">
            <?= $field->name ?>
            <?php if ($field->tooltip_text): ?>
                <a href="javascript:;"
                   class="bootstrap-tooltip"
                   data-placement="top"
                   data-original-title="<?= $field->tooltip_text ?>">
                    <i class="icon-photon info-circle"></i>
                </a>
            <?php endif; ?>
        </label>
    </div>
    <div class="span9">
        <div class="controls">
            <select multiple id="<?= $field->column_name ?>" name="<?= $field->column_name ?>[]" class="many-to-many">
                <?php foreach ($field->getOptions() as $index => $option): ?>
                    <option
                        value="<?= $option->id ?>"
                        <?php if (in_array($option->id, $field->getSelected())): ?>
                            selected
                        <?php endif; ?>>
                        <?= $option ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>