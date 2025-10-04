<?php
// Copyright AI Software Ltd Bucharest, Romania 2001-2014
function writeDbSaveState($table,$id,$op)
{
	if(getUserConfig("dbobjversions")!="")
	{
		$alltable=explode(",",getUserConfig("dbobjversions"));
		if(in_array($table,$alltable))
		{
			//save state
			$del=create_db_connection();
			$del->addnew("junk_items");
			$del->setvalue("fromtable",$table);
			$del->setvalue("opid",$id);
			$del->setvalue("operation",$op);
			$newid=$del->update();
			$del->execute("delete from junk_items where fromtable='".$table."' and opid=0".$id." and operation=0".$op." and id<0".$newid);
		}
	}
}
function writeDbDeleteCheck($sql)
{
	if(getUserConfig('dbsynchron')!="")
	{
		//check for delete items to play on sync
		if(substr($sql,0,12)=="delete from ")
		{
			$table=trim(strtok(substr($sql,12)," "));
			if($table!="junk_items")
			{
				$where=strtok(" ");
				$where=strtok("");
				if($where!="")
				{
					$del=create_db_connection();
					$del->openselect("select id from ".$table." where ".$where);
					$delids="";
					while(!$del->eof())
					{
						if($delids!="") $delids.=",";
						$delids.=$del->getvalue("id");
						$del->movenext();
					}
					$del=create_db_connection();
					$del->addnew("junk_items");
					$del->setvalue("fromtable",$table);
					$del->setvalue("delid",$delids);
					$del->setvalue("operation",3);
					$del->update();
				}
			}
		}
	}
}

function writeDbLogSql($sql,$type='s',$insid=0)
{
	if(getUserConfig("dblog")!="")
	{
		if($type=='s' && getUserConfig("dblog_filtru")!="")
		{
			if(substr($sql,0,24)=="insert into pdfarchiving") return;
			if(substr($sql,0,18)=="insert into oferta") return;
			if(substr($sql,0,13)=="update oferta") return;
		}
		$lock=false;
		if(function_exists("enterCriticalSection"))
			$lock=enterCriticalSection(getUserConfig("dblog").".lock");
		$f=fopen(getUserConfig("dblog").'.v4',"a");
		if($f)
		{
			fwrite($f,$type.":".session_getvalue("login_id").":".$insid.":".base64_encode(gzcompress($sql))."\r\n");
			fclose($f);
		}
		else
		{
			session_addvalue("error","unable to write db log");
		}
		exitCriticalSection($lock);
	}
}

if(file_exists("config/db_gladius.php")) require_once("config/db_gladius.php");
if(file_exists("config/db_sqlite.php")) require_once("config/db_sqlite.php");
if(file_exists("config/db_mysqli.php")) require_once("config/db_mysqli.php");
if(file_exists("config/db_mysql.php")) require_once("config/db_mysql.php");
if(file_exists("config/db_pssql.php")) require_once("config/db_pssql.php");
if(file_exists("config/db_cached.php")) require_once("config/db_cached.php");
if(file_exists("config/db_remote.php")) require_once("config/db_remote.php");
if(file_exists("config/db_dummy.php")) require_once("config/db_dummy.php");
if(intval(substr(phpversion(),0,1))>4 && file_exists("config/db_pdo.php")) require_once("config/db_pdo.php");

?>
