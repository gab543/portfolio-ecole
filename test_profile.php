<?php
require_once 'services/Database.php';
require_once 'models/Profile.php';
require_once 'repositories/ProfileRepository.php';

$repo = new \Repositories\ProfileRepository();
$p = $repo->getFirst();
var_dump($p);
