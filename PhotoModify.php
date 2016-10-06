<?php	
	ob_start();
	session_start();
	
	if (isset($_GET["photo"]))
	{
		$_SESSION["photo"] = $_GET["photo"];
	}
	
	if (isset($_GET["Action"]))
	{
		$_SESSION["PhotoAction"] = $_GET["Action"];
	}

	if (isset ($_SESSION["photo"]))
	{
		$photo = $_SESSION["photo"];
	}
	
	if (isset ($_SESSION["PhotoAction"]))
	{
		$Action = $_SESSION["PhotoAction"];
	}
	
	include("connection.php");
	$conn = oci_connect($UName,$PWord,$DB) or die ("Could not connect to database.");
	?>
<html>
<body>
	<?php
	switch ($Action)
	{
		case "Delete":
			del();
			break;
		case "ConfirmDelete":
			confirmDelete();
			break;
	}
?>
</body>
</html>

<?php
	function del()
	{
		global $photo;
		?>
		<center>
			<h3> Are you sure you want to delete the following photo? <h3>
			<table> 
				<tr>
					<td><input type = "button" value = "Delete" onclick="window.location.href='PhotoModify.php?Action=ConfirmDelete';"/></td>
					<td><input type = "button" value = "Cancel" onclick="window.location.href='Property.php';"/></td>
				</tr>
			</table>
			<br/>
			<img src="property_images\<?php echo $photo; ?>" alt="Property Photo: <?php echo $photo; ?>" width = "400px" height = "400px"/>
		</center>

		<?php
	}

	function confirmDelete()
	{
		global $conn;
		global $photo;
		unlink("property_images//".$photo);
		$query = "DELETE FROM Photo WHERE photo_path = '".$photo."'";
		$stmt = oci_parse($conn,$query);
		oci_execute($stmt);
		oci_free_statement($stmt);
		header("Location: PropModify.php");
		exit;
	}
?>