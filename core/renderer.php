<?php

require 'core/default.view.php';
function render($view, $data = []) {
    extract($data);
    require "views/{$view}.php";
}