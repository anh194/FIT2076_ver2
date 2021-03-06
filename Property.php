<?php
	ob_start();
	session_start();
	require('FPDF/fpdf.php');
	include 'functions.php';
	login("Property.php");	
?>
<html>
	<body>		
		<input type = "button" value = "New Property" onclick="window.location.href='PropModify.php?Action=Create';"/>
		<!--Properties Table-->
		<table border = "1">
			<tr>
				<th>ID</th>
				<th>Address</th>
				<th>Bedrooms</th>
				<th>Bathrooms</th>
				<th>Description</th>
				<th>Type</th>
				<th>Edit</th>
				<th>Delete</th>
			</tr>
		<?php
		
			//connect to database and retrieve all properties
			include("connection.php");
			$conn = oci_connect($UName,$PWord,$DB);
			$propQuery = "SELECT * FROM PROPERTY ORDER BY PROP_STREET";
			$propStmt = oci_parse($conn,$propQuery);
			oci_execute($propStmt);
			
			//display each property
			$numResults = 0;
			while ($propRow = oci_fetch_array ($propStmt))
			{
				$numResults++;
				$address = 
					$propRow["PROP_STREET"]."<br/>".
					$propRow["PROP_CITY"].", ".
					$propRow["PROP_STATE"].", ".
					$propRow["PROP_POSTCODE"].", ".
					$propRow["PROP_COUNTRY"]; 
				if (isset($propRow["PROP_TYPE"]))
				{
					$typeQuery = "SELECT ptype_name FROM PropertyType WHERE PTYPE_ID = ".$propRow["PROP_TYPE"];
					$typeStmt = oci_parse($conn,$typeQuery);
					oci_execute($typeStmt);
					if ($typeRow = oci_fetch_array($typeStmt))
					{
						$propType = $typeRow["PTYPE_NAME"];
					}
					
					oci_free_statement($typeStmt);
				}
				else 
				{
					$propType = "None";
				}
				?>
				<tr>
					<td><?php echo $propRow["PROP_ID"]; ?></td>
					<td><?php echo $address; ?></td>
					<td><?php echo $propRow["PROP_BEDROOMS"]; ?></td>
					<td><?php echo $propRow["PROP_BATHROOMS"]; ?></td>
					<td><?php echo $propRow["PROP_DESCRIPTION"]; ?></td>
					<td><?php echo $propType; ?></td>
					
					<td><a href = "PropModify.php?propid=<?php echo $propRow["PROP_ID"]?>&Action=Update">Edit</a></td>
					<td><a href = "PropModify.php?propid=<?php echo $propRow["PROP_ID"]?>&Action=Delete">Delete </a></td>
				</tr>
				<?php
			}
			?>		
				<form method = "post" action="search.php">		
					<input type="text" name="keyword" />		
    				<input type="submit" value="Search" />		
				</form>		
			<?php
			oci_free_statement($propStmt);
			oci_close($conn);
		?>
		</table>
		<br/>
		<?php 
		if ($numResults == 0)
		{
			echo "No properties found";
		}
		?>
		<p>	
			<a href="DisplaySource.php?filename=Property.php">
				<img src="buttons/property_button.PNG" alt="Property Source Code Button" width = "200px" height = "30px"/>	
			</a>
		</p>
		
	</body>
</html>