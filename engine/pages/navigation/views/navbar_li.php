<?php
/**
 * @var $menu_option string
 * @var $menu_option_url string
 * @var $categories string
 */
?>

<li class="<?= strval($_GET['page'] == $menu_option_url) ? 'active' : '' ?>">
    <?php if ($menu_option_url !== 'category'): ?>
        <a <?= strval($_GET['page'] == $menu_option_url) ? '' : 'href="/' . $menu_option_url . '"' ?>
            data-content="index"
            data-menu=<?= $menu_option_url ?>
            class="session-config">
            <?= $menu_option ?>
        </a>
    <?php else: ?>
        <?= $categories ?>
    <?php endif; ?>
</li>