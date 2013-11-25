<div class="control-group row-fluid">
    <div class="span3">
        <label class="control-label" for="<?= $field->name ?>">
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
            <input type="text"
                   id="<?= $field->name ?>"
                   name="<?= $field->column_name ?>"
                   value="<?= $field->getHtmlValue() ?>">
        </div>
    </div>
</div>
