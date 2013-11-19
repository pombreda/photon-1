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
            <select id="<?= $field->column_name ?>"
                    name="<?= $field->column_name ?>"
                    class="one-to-many">
                <?php for ($i = 10; $i >= -10; $i--): ?>
                    <option value="<?= $i ?>" <?= $field->getValue() == $i ? 'selected' : '' ?>><?= $i ?></option>
                <?php endfor; ?>
            </select>
        </div>
    </div>
</div>
