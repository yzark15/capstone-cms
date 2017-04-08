<?php
require 'header.php';

echo '<h1>Theme Management</h1>';

echo '<div class="left-menu" id="left-menu">';
echo '<ul>';
echo '<li><a href="/cap.admin/pages.php">Pages</a></li>';
echo '<li><a href="/cap.admin/users.php">Users</a></li>';
echo '<li><a href="/cap.admin/themes.php">Themes</a></li>';
echo '</ul>';
echo '</div>';

echo '<div class="has-left-menu">';
//echo '<h2>Theme Management</h2>';

require 'themes/list.php';
echo '</div>';

echo '<script src="/cap.admin/themes/themes.js"></script>';
require 'footer.php';
?>