<?php
$sql = "SELECT * FROM maybe_smart_services";
$editable = $modx->getOption('editable', $scriptProperties, '');
foreach ($modx->query($sql) as $service) {
	print "<tr id='service-".$service['id']."'>";
		print "<td class='name' $editable onBlur='updateDB(".$service['id'].")'>";
			print $service['name'] . '<br>';
		print "</td><td class='price' $editable onBlur='updateDB(".$service['id'].")'>";
			print $service['price'] . '<br>';
		print "</td><td class='measure' $editable onBlur='updateDB(".$service['id'].")'>";
			print $service['measure'] . '<br>';
		print "</td>";
	print "</tr>";
}