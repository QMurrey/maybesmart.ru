<?php
$sql = "SELECT * FROM maybe_smart_services";
foreach ($modx->query($sql) as $service) {
	print "<tr id='service-".$service['id']."'>";
		print "<td class='name' contenteditable='true' onBlur='updateDB(".$service['id'].")'>";
			print $service['name'] . '<br>';
		print "</td><td class='price' contenteditable='true' onBlur='updateDB(".$service['id'].")'>";
			print $service['price'] . '<br>';
		print "</td><td class='measure' contenteditable='true' onBlur='updateDB(".$service['id'].")'>";
			print $service['measure'] . '<br>';
		print "</td>";
	print "</tr>";
}