<?php
// Copyright AI Software Ltd Bucharest, Romania since 2001
class db_conn_dummy {
var $db;
var $error = "";
var $update_values=0;
var $update_id=0;
var $sel;
var $table;
var $fieldid;
var $xtra_cond;
var $eof=true;
function escape($str)
{
	return '';
}

function __construct()
{
}

function execute($sql)
{
	return false;
}

function openselect($sql)
{
	$this->eof=false;
}

function movenext()
{
	$this->eof=true;
}

function eof()
{
	return $this->eof;
}

function getvalue($fieldname)
{
	return "";
}
function getvaluefast($fieldname)
{
	return "";
}

function setvalue($fieldname,$fieldvalue)
{
}

function addnew($table)
{
}

function edit($table,$id,$fieldid,$xtra="")
{
}

function update()
{
	return 1;
}

function close()
{
}
function close_db()
{
}
}
?>
