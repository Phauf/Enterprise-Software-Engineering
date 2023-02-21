<?php
	include ("functions.php");
	$dblink=db_connect("docstorage");
	$receive = new DirectoryIterator("/var/www/html/receive");
	foreach($receive as $value)
	{
		if (!$value->isDot())
		{
			$filesize = $value->getSize();
			$tmp = explode("-",$value);
			$loannumber = $tmp[0];
			$title = $tmp[1];
			$datee = $tmp[2];
			$tmpp = explode(".",$datee);
			$date = $tmpp[0];
			$filetype = $tmpp[1];
			
			$totemp = file_get_contents("/var/www/html/receive/$value");
			$tmpname=tempnam("/var/www/html/temp", $value);
			file_put_contents($tmpname, $totemp);
			$fp = fopen($tmpname, 'r');
			$content = fread($fp, filesize($tmpname));
			fclose($fp);
			
			$contentclean=addslashes($content);

			echo $loannumber . " " . $title ." ". $date . " ". $filetype . " " . $filesize;
			echo "\n";			
		}

		
		$sql="Insert into `received`
		(`loan_number`,`title`,`date`,`filetype`,`filesize`,`content`) values ('$loannumber', '$title', '$date', '$filetype', '$filesize', '$contentclean')";
		$dblink->query($sql) or
		die("Something went wrong with $sql<br>".$dblink->error);
	}

?>