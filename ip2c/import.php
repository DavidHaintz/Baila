<?php
/*********************************************************

* DO NOT REMOVE *

Project: PHPWeby ip2country software version 1.0.2
Url: http://phpweby.com/
Copyright: (C) 2008 Blagoj Janevski - bl@blagoj.com
Project Manager: Blagoj Janevski

More info, sample code and code implementation can be found here:
http://phpweby.com/software/ip2country

This software uses GeoLite data created by MaxMind, available from
http://maxmind.com

This file is part of i2pcountry module for PHP.

For help, comments, feedback, discussion ... please join our
Webmaster forums - http://forums.phpweby.com

**************************************************************************
*  If you like this software please link to us!                          *
*  Use this code:						         *
*  <a href="http://phpweby.com/software/ip2country">ip to country</a>    *
*  More info can be found at http://phpweby.com/link                     *
**************************************************************************

License:
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.

*********************************************************/

include_once('../includes/config.php');
require_once('ip2country.php');

$ip2c=new ip2country();

$ip2c->mysql_host = $GLOBALS['db_host'];
$ip2c->db_user = $GLOBALS['db_user'];
$ip2c->db_pass = $GLOBALS['db_pass'];
$ip2c->db_name = $GLOBALS['db_name'];
$ip2c->table_name = 'ip2c';


ini_set('display_errors',1);
error_reporting(E_ALL);
set_time_limit(300);

if(!$ip2c->mysql_con())
die('Could not connect to database ' . mysql_error());
/*
if(!($r=mysql_query("SHOW VARIABLES LIKE 'max_allowed_packet'",$ip2c->get_mysql_con())))die( 'mysql_error:' . mysql_error($ip2c->get_mysql_con()) . '<br />');
$row=mysql_fetch_assoc($r);

$fsize=@filesize('GeoIPCountryWhois.csv');
if(!$fsize)
die('1. PHP does not have read permission to or the file GeoIPCountryWhois.csv does not exists.<br /><a href="http://forums.phpweby.com" target="_blank">Need help?</a><br /><a href="http://phpweby.com/software/ip2country" target="_blank">More info here</a>');

if(($row['Value'])<($fsize+1))
die('Mysql packet size is ' . $row['Value']/(1024*1024) . 'MB and it is lower than the size of the file GeoIPCountryWhois.csv which is '.round(($fsize+1)/(1024*1024),2).'MB. <br/> Please download the other version of import.php at <a href="http://phpweby.com/software/ip2country">http://phpweby.com/software/ip2country</a>');

unset($row,$r);
*/
$ip2c->create_mysql_table();

//this query deletes all data stored in table $ip2c->table_name
//@mysql_query("DELETE FROM ". $ip2c->table_name,$ip2c->get_mysql_con());

$f=@fopen('GeoIPCountryWhois.csv','r');

if(!$f)
die('PHP does not have read permission to or the file GeoIPCountryWhois.csv does not exists.<br /><a href="http://forums.phpweby.com" target="_blank">Need help?</a><br /><a href="http://phpweby.com/software/ip2country" target="_blank">More info here</a>');

//$str=@fread($f,$fsize);
//@fclose($f);

unset($fsize);

//$str=str_replace("\n",'),(',trim(trim($str,"\n")));

while (($str = fgets($f)) !== false)
	if(!mysql_query("INSERT into " .$ip2c->table_name . "(`begin_ip`,`end_ip`,`begin_ip_num`,`end_ip_num`,`country_code`,`country_name`) values(" . $str . ")",$ip2c->get_mysql_con()))die ('mysql_error: ' . mysql_error($ip2c->get_mysql_con()) . '<br />');
@fclose($f);

unset($str);
$ip2c->close();
//@unlink('import.php');
//@unlink('GeoIPCountryWhois.csv');
echo 'Successfully inserted data. If the files GeoIPCountryWhois.csv and import.php are not deleted, delete them.';
echo '<br />If you like this software please link to us!<br />Use this code:<br />
    '. htmlspecialchars('<a href="http://phpweby.com/software/ip2country">ip to country</a>') .'<br />
	More info and links can be found at <a href="http://phpweby.com/link" target="_blank">http://phpweby.com/link</a><br /> ' ;
echo 'For help, comments, feedback, discussion ... please join our
	<a href="http://forums.phpweby.com" target="_blank" style="color:blue;font-weight:bold;">Webmaster Forums</a>';
?>