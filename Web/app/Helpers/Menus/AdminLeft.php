<?php

Menus::create('admin_left_menu', function ($menu) {
    $menu->disableOrdering();
    $menu->setView('admin._layout.menu_left');
});
