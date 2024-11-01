<?php
/*

Plugin name: SmartWeber - FREE Aweber List Segmentation Plugin
Description: SmartWeber extends the functionality of your Aweber account by allowing you to segment your lists easily and quickly.
Author name: Jean Paul
*/

include_once "app/class-smartweber.php";

$smartweber = new SmartWeber;
$smartweber->run();