<?php

/*
 +--------------------------------------------------------------------------+
 | Copyright (C) 2009-2010 Xinn.org                                         |
 |                                                                          |
 | This program is free software; you can redistribute it and/or            |
 | modify it under the terms of the GNU General Public License              |
 | as published by the Free Software Foundation; either version 2           |
 | of the License, or (at your option) any later version.                   |
 |                                                                          |
 | This program is distributed in the hope that it will be useful,          |
 | but WITHOUT ANY WARRANTY; without even the implied warranty of           |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            |
 | GNU General Public License for more details.                             |
 +--------------------------------------------------------------------------+
 |Avarice-nms:A greedy and insatiable inventory and network managment system|
 +--------------------------------------------------------------------------+
 | This code is designed, written, and maintained by the Xinn.org. See      |
 | about.php and/or the AUTHORS file for specific developer information.    |
 +--------------------------------------------------------------------------+
 | http://avarice-nms.com                                                   |
 | http://avarice-nms.info                                                  |
 | http://xinn.org/avarice.php                                              |
 +--------------------------------------------------------------------------+
*/

$self = array();

include_once("../../include/config.php");

// Used to determine template to run.  Needs to be fleshed out. Currently 
// defaulting to template ID 1
$os_details = array("os"      => php_uname('s'),
                    "release" => php_uname('r'),
                    "version" => php_uname('v'));
$query = "
          SELECT template
            FROM inv__config_templates
           WHERE templateID = 1";
$template = mysql_result(dbquery_func($avarice_user_connection, $query), 0, 0);

$template_xml = simplexml_load_string($template);
$wmi_information = array();
foreach ($template_xml->asset->property as $property) {
  if ($property['method'] == "wmi") {
    if (!in_array((string)$property['namespace'], array_keys($wmi_information))) {
      $wmi_information[(string)$property['namespace']] = array("props" => array());
    };
    $wmi_information[(string)$property['namespace']]['props'][] = (string)$property['property'];
  };
};
foreach ($template_xml->asset->category as $category) {
  if ($category['type'] == "single") {
    foreach ($category->property as $property) {
      if ($property['method'] == "wmi") {
        if (!in_array((string)$property['namespace'], array_keys($wmi_information))) {
          $wmi_information[(string)$property['namespace']] = array("props" => array());
        };
        $wmi_information[(string)$property['namespace']]['props'][] = (string)$property['property'];
      };
    };
  } else if ($category['type'] = "multiple") {
    foreach ($category->instance->property as $property) {
      if ($property['method'] == "wmi") {
        if (!in_array((string)$property['namespace'], array_keys($wmi_information))) {
          $wmi_information[(string)$property['namespace']] = array("props" => array());
        };
        $wmi_information[(string)$property['namespace']]['props'][] = (string)$property['property'];
      };
    };
  };
};

$self['time_start'] = microtime(true);

foreach($wmi_information as $key => $value) {
  $obj = new COM('winmgmts:{impersonationLevel=impersonate}//./root/cimv2');
  $query = "SELECT * FROM " . $key;
  $result = $obj->ExecQuery($query);
  $wmi_information[$key]['result'] = array();
  foreach ($result as $resvalue) {
    $resarray = array();
    foreach ($value['props'] as $prop) {
      $resarray[$prop] = (string)$resvalue->$prop;
    };
    $wmi_information[$key]['result'][] = $resarray;
  };
};

$self['time_end'] = microtime(true);

print_r($self);
print_r($wmi_information);

?>