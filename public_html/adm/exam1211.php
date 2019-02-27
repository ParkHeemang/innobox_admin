<!DOCTYPE html>
<html>
<head>
	<title>
	</title>
</head>
<body>

<?php $array = array(
array(1,2,3,'a'),
array(4,5,6,'b')
);


?>


<script type="text/javascript">
	

var js_array = <?php echo json_encode($array)?>;
document.write(js_array);

</script>



</body>
</html>