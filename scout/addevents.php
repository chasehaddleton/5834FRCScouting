<?php
// thebluealliance.com/api/v2/events/2016
include_once("../components/Settings.php");
$setting = new Settings();

require_once($setting->getAppPath() . '/components/common.php');
require_once($setting->getAppPath() . '/components/User.php');

printHead("Register");
printNav();
?>