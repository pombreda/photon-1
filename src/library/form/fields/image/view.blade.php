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
        <?php if ($field->getValue()): ?>
            <div id="image-cell-<?= $field->column_name ?>"
                 class="image-cell"
                 style="margin-top:10px;"
                 data-column-name='<?= $field->column_name ?>'
                 data-entry-id='<?= $field->id ?>'>
                <img class="user-image"
                     src="/<?= $field->getValue() ?>"
                     alt="<?= $entry[$field->column_name] ?>"/>
                <img class="row-shadow"
                     src="<?= Config::get('photon::photon.package_assets_uri') ?>/images/photon/w_shadow.png"
                     alt="shadow">
            </div>
        <?php endif; ?>
        <div class="fileupload fileupload-new" data-provides="fileupload">
            <div class="input-append">
                <div class="uneditable-input span3">
                    <i class="icon-file fileupload-exists"></i>
                    <span class="fileupload-preview"></span>
                </div>
                <span class="btn btn-file">
                    <span class="fileupload-new">Select file</span>
                    <span class="fileupload-exists">Change</span>
                    <input
                        type="file"
                        id="<?= $field->column_name ?>"
                        name="<?= $field->column_name ?>" value="">
                </span>
                <a href="javascript:;"
                   class="btn fileupload-exists"
                   data-dismiss="fileupload">Remove
                </a>
            </div>
        </div>
    </div>
</div>
