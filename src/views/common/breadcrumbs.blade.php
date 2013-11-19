<div class="breadcrumb-container">
    <ul class="xbreadcrumbs">
        <li>
            <a href="{{\Request::root()}}/admin">
                <i class="icon-photon home"></i>
            </a>
        </li>
        <?php foreach ($breadcrumbs as $breadcrumb): ?>
            <li class="<?= last($breadcrumbs) == $breadcrumb ? 'current' : '' ?>">
                <a href="<?= $breadcrumb['url'] ?>"><?= $breadcrumb['title'] ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
