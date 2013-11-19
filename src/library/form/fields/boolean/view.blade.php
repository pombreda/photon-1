<div class="control-group row-fluid">
    <div class="span3">
        <label class="control-label" for="<?= $field->column_type ?>">
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
    <div class="span9 span-inset">
        <div class="controls">
            <div data-on-label="ON" data-off-label="OFF" class="switch switch-small">
                <input id="<?= $field->column_name ?>"
                       name="<?= $field->column_name ?>"
                       type="checkbox" <?= $field->getValue() ? 'checked="checked"' : '' ?>
                    />

            </div>
            <input id="<?= $field->column_name ?>_alt"
                   type="hidden"
                   name="<?= $field->column_name ?>"
                   value="<?= $field->getValue() ?>"/>
        </div>
    </div>
</div>

<!--Javascript section start-->
<script>
    var selector = '#<?=$field->column_name?>';
    var $switch = $(selector);
    $switch.change(function() {
        $(selector + '_alt').val(Number($switch.is(':checked')));
    });
</script>
<!--Javascript section end-->