<head>
	<title>Transwarp.se testsida</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="css/screen.css">
        <link rel="stylesheet" href="css/contactable.css" type="text/css" />
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
        <script type="text/javascript">
            jQuery(document).ready(function() {
            jQuery(".content").hide();
            //toggle the componenet with class msg_body
            jQuery(".heading").click(function()
            {
                jQuery(this).next(".content").slideToggle(500);
            });
            });
        </script>        		
	<script>
            $(function() {
                $( "#datepicker" ).datepicker();
                    $( "#datepicker" ).datepicker("option", "dateFormat", "yy-mm-dd");
                        $( "#datepicker" ).datepicker("option", "showAnim", "clip");
            });
  
            $(function() {
                $( "#datepicker2" ).datepicker();
                    $( "#datepicker2" ).datepicker("option", "dateFormat", "yy-mm-dd");
                    $( "#datepicker2" ).datepicker("option", "showAnim", "clip");
            });
  </script>
</head>