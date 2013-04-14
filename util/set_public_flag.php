<?
# Task that is called from task_runner.php
include_class("Person");
$output = Person::resetPublicFlags();
$success = true;
?>