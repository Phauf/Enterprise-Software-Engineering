<?php
$username="nko631";
$password="rxqFpXYHk3TMN@7D";
$data="username=$username&password=$password";

include ("functions.php");
$dblink=db_connect("docstorage");
$ch=curl_init('https://cs4743.professorvaladez.com/api/create_session');
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'content-type: application/x-www-form-urlencoded',
	'content-length: ' . strlen($data))
);
$time_start=microtime(true);
$result=curl_exec($ch);
$time_end=microtime(true);
$execution_time=($time_end - $time_start)/60;
curl_close($ch);
$cinfo=json_decode($result,true);
if($cinfo[0]=="Status: OK" && $cinfo[1]=="MSG: Session Created")
{
	$sid=$cinfo[2];
	$data="sid=$sid&uid=$username";
	echo "\r\nSession Created Successfully!\r\n";
	echo "SID: $sid\r\n";
	echo "Create Session Execution Time: $execution_time\r\n";
	$ch=curl_init('https://cs4743.professorvaladez.com/api/query_files');
	curl_setopt($ch, CURLOPT_POST,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'content-type: application/x-www-form-urlencoded',
		'content-length: ' . strlen($data))
	);
	$time_start = microtime(true);
	$result = curl_exec($ch);
	$time_end = microtime(true);
	$execution_time = ($time_end - $time_start)/60;
	curl_close($ch);
	$cinfo=json_decode($result,true);
	if ($cinfo[0]=="Status: OK")
	{
		if ($cinfo[1]=="Action: None")
		{
			echo "\r\n No New files to import found\r\n";
			echo "SID $sid\r\n";
			echo "Username: $username\r\n";
			echo "Query Files Execution Time: $execution_time\r\n";
		}
		else
		{
			$tmp=explode(":",$cinfo[1]);
			$files=explode(",",$tmp[1]);
			echo "Number of new files to import found: ".count($files)."\r\n";
			echo "Files:\r\n";
			foreach($files as $key=>$value)
			{
				$tmp=explode("/",$value);
				$file=$tmp[4];
				echo "File: $file\r\n";
				$data="sid=$sid&uid=$username&fid=$file";
				$ch=curl_init('https://cs4743.professorvaladez.com/api/request_file');
				curl_setopt($ch, CURLOPT_POST,1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 	true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'content-type: application/x-www-form-urlencoded',
					'content-length: ' . strlen($data))
				);
				$time_start=microtime(true);
				$result=curl_exec($ch);
				$time_end=microtime(true);
				$execution_time=($time_end - $time_start)/60;
				$content=$result;
				//
				
				
				
				
				$fp=fopen("/var/www/html/receive/$file","wb");
				fwrite($fp,$content);
				fclose($fp);
				echo "\r\n$file written to file system\r\n";
				
						$filesize = filesize("/var/www/html/receive/$file");
						$t = explode("-",$file);
						$tmp = explode("-",$value);
						$loannumber = $t[0];
						$title = $tmp[1];
						$datee = $tmp[2];
						$tmpp = explode(".",$datee);
						$date = $tmpp[0];
						$filetype = $tmpp[1];
					
						$contentclean=addslashes($content);
//				echo "\n\n";
//				echo $loannumber . " " . $title ." ". $date . " ". $filetype . " ". $filesize ;
//				echo "\n\n";

						$sql="Insert into `received`
						(`loan_number`,`title`,`date`,`filetype`,`filesize`,`content`) values ('$loannumber', '$title', '$date', '$filetype', '$filesize', '$contentclean')";
						$dblink->query($sql) or
						die("Something went wrong with $sql<br>".$dblink->error);
				
				
				
			}
						
				
//				$receive = new DirectoryIterator("/var/www/html/receive");
//				foreach($receive as $value)
//				{
//					if (!$value->isDot())
//					{
//						$filesize = $value->getSize();
//						$tmp = explode("-",$value);
//						$loannumber = $tmp[0];
//						$title = $tmp[1];
//						$datee = $tmp[2];
//						$tmpp = explode(".",$datee);
//						$date = $tmpp[0];
//						$filetype = $tmpp[1];
//
//						$totemp = file_get_contents("/var/www/html/receive/$value");
//						$tmpname=tempnam("/var/www/html/temp", $value);
//						file_put_contents($tmpname, $totemp);
//						$fp = fopen($tmpname, 'r');
//						$content = fread($fp, filesize($tmpname));
//						fclose($fp);
//
//						$contentclean=addslashes($content);
//
//						echo $loannumber . " " . $title ." ". $date . " ". $filetype . " " . $filesize;
//						echo "\n";	
//						$sql="Insert into `received`
//						(`loan_number`,`title`,`date`,`filetype`,`filesize`,`content`) values ('$loannumber', '$title', '$date', '$filetype', '$filesize', '$contentclean')";
//						$dblink->query($sql) or
//						die("Something went wrong with $sql<br>".$dblink->error);
//						}
//				}
					
					
					
			echo "Query Files Execution Time: $execution_time\r\n";
		}
		$data="sid=$sid";
		$ch=curl_init('https://cs4743.professorvaladez.com/api/close_session');
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'content-type: application/x-www-form-urlencoded',
			'content-length: ' . strlen($data))
		);
		$time_start = microtime(true);
		$result = curl_exec($ch);
		$time_end = microtime(true);
		$execution_time = ($time_end - $time_start)/60;
		curl_close($ch);
		$cinfo=json_decode($result,true);
		if ($cinfo[0]=="Status: OK")
		{
			echo "Session successfully closed!\r\n";
			echo "SID: $sid\r\n";
			echo "Close Session execution time: $execution_time\r\n";
		}
		else
		{
			echo $cinfo[0];
			echo "\r\n";
			echo $cinfo[1];
			echo "\r\n";
			echo $cinfo[2];
			echo "\r\n";
		}
	}
	else			//error
	{
		echo $cinfo[0];
		echo "\r\n";
		echo $cinfo[1];
		echo "\r\n";
		echo $cinfo[2];
		echo "\r\n";
	}
}
else
{
	echo $cinfo[0];
	echo "\r\n";
	echo $cinfo[1];
	echo "\r\n";
	echo $cinfo[2];
	echo "\r\n";
}
		
		
		
		
		
		