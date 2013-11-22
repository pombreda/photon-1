<div class="btn-toolbar btn-mobile-menus">
    <button class="btn btn-main-menu"></button>
</div>

<div class="nav-fixed-left" style="visibility: hidden">
    <ul class="nav nav-side-menu">
        <li class="shadow-layer"></li>
        <li>
            <a href="{{\Request::root()}}/admin">
                <i class="icon-photon home"></i>
                <span class="nav-selection">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="{{\Request::root()}}/admin/settings">
                <i class="icon-photon cloud_upload"></i>
                <span class="nav-selection">Creator</span>
            </a>
        </li>

        <!--Start with folders-->
        <?php foreach ($mainMenu as $entry): ?>
            <li>
                <a href="<?= \Request::root() ?>/admin/<?= $entry->table_name ?>">
                    <i class="icon-photon
                    <?php if ($entry instanceof \Orangehill\Photon\Folder): ?>
                    <?= $entry->icon ? : 'box' ?>
                    <?php else: ?>
                    <?= $entry->icon ? : 'aperture' ?>
                    <?php endif; ?>
                    "></i>
                    <span class="nav-selection"><?= $entry->name ?></span>
                </a>
                <?php if ($entry instanceof \Orangehill\Photon\Folder): ?>
                    <div class="sub-nav">
                        <ul class="nav">
                            <?php foreach ($entry->modules as $module): ?>
                                <li>
                                    <a href="<?= \Request::root() ?>/admin/<?= $module->table_name ?>">
                                        <?= $module->name ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
        <!--End with folders-->


        <?php if (0): ?>
            <?php foreach ($mainMenu as $item): ?>
                <li>
                    <a href="<?= \Request::root() ?>/admin/<?= $item['table_name'] ?>">
                        <i class="icon-photon @if($item['icon']!=''){{$item['icon']}}@else{{'document_alt_stroke'}}@endif"></i>
                        <span class="nav-selection">{{$item['name']}}</span>
                    </a>
                    <?php if ($item['children']): ?>
                        <div class="sub-nav">
                            <ul class="nav">
                                <?php foreach ($item['children'] as $child): ?>
                                    <li>
                                        <a href="<?= \Request::root() ?>/admin/<?= $child->table_name ?>">
                                            <?= $child->name ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
        <li class="nav-logout">
            <a href="{{\Request::root()}}/users/logout">
                <i class="icon-photon key_stroke"></i><span class="nav-selection">Logout</span>
            </a>
        </li>
    </ul>
</div>
