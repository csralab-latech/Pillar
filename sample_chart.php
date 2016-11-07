<?php
// include and create object
include("lib/inc/chartphp_dist.php");
$p = new chartphp();

// set few params
$p->data = array(array(array("2010/10",48.25),array("2011/01",238.75),array("2011/02",95.50)));
$p->chart_type = "line";

// render chart and get html/js output
$out = $p->render('c1');
?>

<!DOCTYPE html>
<html>
<head>

    <script src="lib/js/jquery.min.js"></script>
    <script src="lib/js/chartphp.js"></script>
    <link rel="stylesheet" href="lib/js/chartphp.css">

</head>

<body>
    <div style="margin:10px">

    <!-- display chart here -->
    <?php echo $out?>
    <!-- display chart here -->

    </div>  
</body>
</html>