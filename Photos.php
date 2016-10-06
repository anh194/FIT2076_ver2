<?php	
	ob_start();
	session_start();
	
	include("connection.php");
	$conn = oci_connect($UName,$PWord,$DB) or die ("Could not connect to database.");
?>

<html>
	<body>
		<?php
		
		if (isset($_POST['photosToDelete']))
		{
			deleteImages($_POST['photosToDelete']);
		}
		
		showAllImages();
		oci_close($conn);
		?>
	</body>
</html>

<script language="javascript"> 
	function promptDelete()
	{
		var text =  "Are you sure you want to delete the selected photos?"
		if(window.confirm(text))
		{
			document.getElementById("photosToDelete").submit();
		}
	}
</script>

<?php
	function showAllImages()
	{
		?>
		<form id="photosToDelete" method = "post" action = "Photos.php">
		
			<td><input type = "button" value = "Delete Selected Photos" onclick="promptDelete();"/></td> <br/>

			<table cellpadding=3>
				<tr>
					<td><b>Photo</b></td>
					<td><b>Property</b></td>
					<td><b>Delete<b></td>
				</tr>
				<?php
				$propImagesDir = dirname($_SERVER["SCRIPT_FILENAME"])."\property_images";
				$dir = opendir($propImagesDir); 
				while($file = readdir($dir))
				{
					if(!is_dir($file))
					{
						?>
						<tr>
							<td>
								<img src="<?php echo "property_images\\".$file; ?>" alt="Property Photo" width = "200px" height = "200px"/>
							</td>
								
							<td>
								<?php showPropertyDetails($file);?>
							</td>
							
							
							<td>
								<center>
								<input 
									type="checkbox" 
									name="photosToDelete[]" 
									value="<?php echo $file; ?>" 
								>
								</input>
								</center>
							</td>
							
						</tr>
						<?php
					} 
				}	
				?>
			</table>
		</form>
		<?php
	}
	
	function showPropertyDetails($file)
	{
		global $conn;
		$query="SELECT * FROM Photo WHERE photo_path = '".$file."'";
		$stmt = oci_parse($conn,$query);
		oci_execute($stmt);
		if ($row = oci_fetch_array($stmt))
		{
			$propid = $row["PHOTO_PROPERTY"];
			$propQuery = "SELECT * FROM Property WHERE prop_id = ".$propid;
			$propStmt = oci_parse($conn,$propQuery);
			oci_execute($propStmt);
			if ($propRow = oci_fetch_array($propStmt))
			{
				$address = 
					$propRow["PROP_STREET"]."<br/>".
					$propRow["PROP_CITY"].", ".
					$propRow["PROP_STATE"]."<br/>".
					$propRow["PROP_POSTCODE"].", ".
					$propRow["PROP_COUNTRY"]; 
				?>
				<a href="PropModify.php?propid=<?php echo $propid; ?>"><?php echo $address; ?></a>
				<?php
			}
			oci_free_statement($propStmt);
		}
		else
		{
			echo "Not linked to property \n";
		}
		oci_free_statement($stmt);
	}
	
	function deleteImages($images)
	{
		global $conn;
		foreach($images as $photo)
		{
			unlink("property_images//".$photo);
			$query = "DELETE FROM Photo WHERE photo_path = '".$photo."'";
			$stmt = oci_parse($conn,$query);
			oci_execute($stmt);
			oci_free_statement($stmt);
		}
	}
?>