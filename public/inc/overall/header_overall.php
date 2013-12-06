<!doctype html>
<html>
<?php include("inc/head.php"); ?>
<body>
<?php include("inc/header.php"); ?>
	<div id="container">
            <div id="contactable"></div>
                <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
                <script type="text/javascript" src="js/jquery.validate.pack.js"></script>
                <script type="text/javascript" src="js/jquery.contactable.js"></script>
                <script>
                    $(function(){
                        $('#contactable').contactable({
                            subject: 'feedback URL:'+location.href});}
                    );
                </script>
<?php include("inc/aside.php"); ?>