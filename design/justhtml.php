<?php ob_start();if(file_exists(cache_getvalue('temp_default'))) include cache_getvalue('temp_default');$context=ob_get_contents();ob_end_clean();?>
<!DOCTYPE html>
<html>
<HEAD>
<?php if(cache_getvalue("title")!=""){?><TITLE><?php echo cache_getvalue("title");cache_setvalue("title","");?></TITLE><?php }?>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
<meta charset="UTF-8">
<META NAME="Rating" CONTENT="<?php echo cache_getvalue("rating");cache_setvalue("rating","");?>" />
<meta name="robots" content="all" />
<meta name="AUTHOR" type="email" content="<?php echo cache_getvalue("author");cache_setvalue("author","");?>" />
<meta name="COPYRIGHT" content="<?php echo cache_getvalue("copyright");cache_setvalue("copyright","");?>" />
<meta NAME="revisit-after" CONTENT="10 days" />
<meta name="LASTUPDATE" content="<?php echo date('F j, Y, g:i a')?>" />
<meta name="SECURITY" content="Public" />
<meta name="keywords" content="<?php echo cache_getvalue("keywords");cache_setvalue("keywords","");?>">
<meta name="description" content="<?php echo cache_getvalue("description");cache_setvalue("description","");?>" />
<META name="verify-v1" content="<?php echo cache_getvalue("verify-v1");cache_setvalue("verify-v1","");?>" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<script type="text/javascript">
var tinyMCE;tinyMCE=false;var myNicEditor;myNicEditor=false;var currentPageProtection;currentPageProtection=<?php echo 0+intval(session_getvalue("protection_page"));?>;var sessionmaster='';
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.2.0/js/tether.min.js" integrity="sha384-Plbmg8JY28KFelvJVai01l8WyZzrYWG825m+cZ0eDDS1f7d/js6ikvy1+X+guPIB" crossorigin="anonymous"></script>

<?php echo cache_getvalue("beginhead");cache_setvalue("beginhead","");?>
<?php echo cache_getvalue("head");cache_setvalue("head","");?>
<?php if(file_exists("extensions/user.js")){?><script language="javascript"><?php include "extensions/user.js";?></script><?php }?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css" integrity="sha384-AysaV+vQoT3kOAXZkl02PThvDr8HYKPZhNT5h/CXfBThSRXQ6jW5DO2ekP5ViFdi" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

<?php echo cache_getvalue("finalhead");cache_setvalue("finalhead","");?>

</head>
<body><?php
echo cache_getvalue("body");cache_setvalue("body","");
echo $context;?>
<?php
echo cache_getvalue("afterbody");cache_setvalue("afterbody","");
?>


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>

</body>
</html>
