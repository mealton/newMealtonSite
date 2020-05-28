<?php
/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 28.05.2020
 * Time: 11:39
 */

?>

<aside>
    <div class="sidebar-publications">
        <div class="search">
            <form action="" onsubmit="location.href='/find/' + this.elements['search'].value; return false;"
                  id="search-form">
                <div class="search-container">
                    <input type="text" name="search" placeholder="Что будем искать?" class="form-control search-input">
                    <div id="search-options"></div>
                </div>
                <button type="submit" class="btn btn-primary" disabled>Найти</button>
            </form>
        </div>
        <br>
        <h4>Последние добавленные публикации</h4>
        <div class="sidebar-scroll-container">
            <div class="sidebar-scroll-container-inner">
                <?= $html ?>
            </div>
        </div>
        <?php if (main_Controller::$sidebarCounter > 6): ?>
            <p style="text-align: right;margin-top: 20px;">
                <button class="btn btn-default scroll-sidebar to-up" disabled><i class="fa fa-caret-up" aria-hidden="true"></i>
                </button>
                <button class="btn btn-default scroll-sidebar to-down"><i class="fa fa-caret-down"
                                                                          aria-hidden="true"></i></button>
            </p>
            <script>
                const sidebarOffsetMax = <?=main_Controller::$sidebarCounter - 1?>;
            </script>
        <?php endif; ?>
    </div>
</aside>
