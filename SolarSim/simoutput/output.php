<?php
if($_GET['generation']=='testing'){
	$homepage = file_get_contents('./output.csv');
	$fileTime = filemtime('./output.csv');
}else{
	$homepage = file_get_contents('./grav_output.csv');
	$fileTime = filemtime('./grav_output.csv');
}
if($_GET['LastUpdate']==$fileTime){
	$s = explode("\n",$homepage);
	echo $s[0]."\n";
	echo $s[1]."\n";
	echo $s[2]."\n";
	echo $s[3]."\n";
	echo $s[4]."\n";
	echo $s[5]."\n";
	echo "END\n";
	echo "LastUpdate=".$fileTime."\n";
}else
if(strpos($homepage,"END")>10){
	echo $homepage;
	echo "LastUpdate=".$fileTime."\n";
}else{
	echo "Data Fault";
}
?>
