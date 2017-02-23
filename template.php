<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title></title>
		<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css">
	</head>
	<body>
		<div id="tree"></div>
		<div id="datatest" hidden="true">
			<?php echo $data; ?>
		</div>
		<script src="http://cdn.bootcss.com/jquery/3.1.1/jquery.js"></script>
		<script src="bootstrap-treeview.js"></script>
		<script type="text/javascript">
			// console.log($('#datatest').text());
			$('#tree').treeview({data: JSON.parse($('#datatest').text())});
		</script>
	</body>
</html>
