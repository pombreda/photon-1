<div class="control-group row-fluid">
    <div class="span3">
        <label class="control-label" for="<?= $field->column_name ?>">
            <?= $field->name ?>
            <?php if ($field->tooltip_text): ?>
                <a href="javascript:;;"
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
            <select
                id="<?= $field->column_name ?>"
                name="<?= $field->column_name ?>"
                class="one-to-many">
                <option>None</option>
                <?php foreach ($field->getOptions() as $index => $model): ?>
                    <option value="<?= $model->id ?>" <?= $model->id == $field->getValue() ? 'selected' : '' ?>>
                        <?= $model ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>
