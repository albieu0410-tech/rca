<?php

require_once("config/rights.php");
require_once("config/language.php");
require_once("config/db.php");

//specific for this site
getSiteConfigs();
global $conn;
global $_CONFIG;

if(getUserConfig('updateurl')=='')
{
	$_CONFIG['updateurl']="http://kit5.asiguram.ro/";
}

function file_force_contents($dir, $contents)
{
	$parts = explode('/', $dir);
	$file = array_pop($parts);
	$dir = '';
	foreach($parts as $part)
	{
		if($dir!="") $dir.="/";
		$dir.=$part;
		if(!is_dir($dir))
		{
			echo "<br>fac: ".$dir;
			mkdir($dir);
		}
	}
	if($dir!="")
		$file=$dir."/".$file;
	return file_put_contents($file,$contents);
}
$localver=intval(file_get_contents("myversion.php"));
$myver=file_get_contents(getUserConfig('updateurl')."version_kit.js");
$myver=explode("=",$myver);
if(intval($myver[1])>$localver)
{
	echo "<br>iau update";
	echo "<br>fac update de la ".$localver." la ".intval($myver[1]);
	$myfile=file_get_contents(getUserConfig('updateurl')."all_kit.src");
	echo "<br>salvez update";
	file_put_contents("myupdate.php",$myfile);
	chmod("myupdate.php",0755);
	echo "<br>incarc update";
	global $phpscripts;
	include("myupdate.php");
	//print_r($phpscripts);
	foreach($phpscripts as $k=>$v)
	{
		echo "<br>salvez ".$k;
		if(file_force_contents($k,gzuncompress(base64_decode($v)))===false)
		{
			echo "<br>nu pot salva ".$k;
			file_get_contents("http://demo.asiguram.ro/extensions/trimitealerta.php?s=Eroareupdate&m=Nupotterminaupdatekit5");
			die();
		}
	}
	echo "<br>inchid versiunea ".intval($myver[1]);
	file_put_contents("myversion.php",intval($myver[1]));
}
else
{
	echo "Nu am update ".$localver;
}?>
