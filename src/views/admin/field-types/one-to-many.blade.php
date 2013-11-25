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
    <?php
    var_dump($field);
    ?>
    <div class="span9">
        <div class="controls">
            <select
                id="<?= $field->column_name ?>"
                name="<?= $field->column_name ?>"
                class="one-to-many">
                <option>None</option>
                <?php if (is_array($oneToMany[$field->column_name])): ?>
                    <?php foreach ($oneToMany[$field->column_name] as $id => $anchor): ?>
                        <option value="<?= $id ?>"
                            <?= $entry[$field->column_name] == $id ? 'selected' : '' ?>
                            >
                            <?= $anchor ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </div>
</div>
