<?php
$page = $_GET['page'] ?? 'inicio';

require "views/$page.php";
