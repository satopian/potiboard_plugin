<?php
$logfiles_arr =(glob('./data/{MSG*.log}', GLOB_BRACE));
asort($logfiles_arr);
foreach($logfiles_arr as $logfile){
	$fp=fopen($logfile,"r");
	$i=0;
	while($line =fgets($fp ,4096)){
		list($no,)
		=explode("\t",$line."\t\t\t\t\t\t\t\t\t");
		$log[]=$line;
		$tree[]=$no;
		
}
foreach($log as $i=>$val){
	if($i===0){
		list($no,$name,$now,$sub,$email,$url,$com,$host,$ip,$agent,$filename,$W,$H,,,$pch,$ptime,$applet,$thumbnail)
		=explode("\t",$val."\t");
		$ext = '.'.pathinfo($filename,PATHINFO_EXTENSION );
		$time = pathinfo($filename,PATHINFO_FILENAME);

	}else{
		unset($no,$name,$now,$email,$url,$com,$host,$ip,$agent,$filename,$W,$H,$pch,$ptime,$applet,$thumbnail,$ext,$time);
		$W=$H=$pch=$ptime=$ext=$time=$ip='';
		list($no,$name,$now,$com,,$host,$email,$url)
		=explode("\t",$val."\t\t\t\t\t\t\t\t\t");
	}

	$newlog[]="$no,$now,$name,$email,$sub,$com,$url,$host,$ip,$ext,$W,$H,$time,,$ptime,.\n";
	
}

	$treeline[]=implode(",",$tree)."\n";
	unset($log);
	unset($tree);
	fclose($fp);
}
arsort($treeline);
file_put_contents('tree.log',$treeline, LOCK_EX);
$newlog=mb_convert_encoding($newlog, "UTF-8", "sjis");
arsort($newlog);
file_put_contents('img.log',$newlog,LOCK_EX);

