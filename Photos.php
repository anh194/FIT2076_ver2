<?php	
	ob_start();
	session_start();
?>
<html>
	<body>
		<form method="post" enctype="multipart/form-data" action="Photos.php">
			<table border="0">
				<tr>
					<td><b>Select a file to upload:</b><br>
					<input type="file" size="50" name="userfile">
					</td>
				</tr>
					<tr>
					<td><input type="submit" value="Upload File"> </td>
				</tr>
			</table>
		</form>
		
		<?php
		if (isset($_FILES["userfile"]["tmp_name"]))
		{
			$upfile = "property_images/".$_FILES["userfile"]["name"];
			if (!move_uploaded_file($_FILES["userfile"]["tmp_name"],$upfile))
			{
				echo "ERROR: Could not move file into directory";
			}
		}
		
		if (isset($_GET["propid"]))
		{
			$_SESSION["propid"] = $_GET["propid"];
		}
		else
		{
			showAllImages();
		}
		
		?>
	</body>
</html>

<?php
	function showAllImages()
	{
		?>
		<table cellpadding=3>
			<?php
			$propImagesDir = dirname($_SERVER["SCRIPT_FILENAME"])."\property_images";
			$dir = opendir($propImagesDir); 
			while($file = readdir($dir))
			{
				if($file == "." || $file =="..")
				{
					continue;
				}
				if(!is_dir($file))
				{
					?>
					<tr>
						<td>
							<img src="<?php echo "property_images\\".$file; ?>" alt="Property Photo" width = "200px" height = "200px"/>
						</td>
					</tr>
					<?php
				} 
			}	
			?>
		</table>
		<?php
	}
	
	function showPropertyImages()
	{
		
	}
?>