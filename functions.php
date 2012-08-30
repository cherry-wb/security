<?php
/*
sortByTimeSetup - Prepares $files to be sorted via array_multisort by timestamp
*/
function sortByTimeSetup($files) 
{
	$timestamps = array();
	foreach ($files as $key => $entry) 
	{
		$timestamps[$key]    = $entry['timestamp'];
	}
	return $timestamps;
}
/*
sidFeedCount - Checks the number of feeds associated with all enabled sid
output: array[sid] = count
*/
function sidFeedCount() {

	$feeds = array(); // array of objects containing information about sources
	$sourceFeeds = array(); // array of ints, representing how many feeds per sid
	
	$con = mysql_connect('localhost','root','') or die('error');
	mysql_select_db('security', $con) or die(mysql_error());
	
	$sql =	"SELECT ".
					"fd.*, " .
					"src.sid ".
				"FROM ".
					"feed_data as fd, ".
					"sources as src, ".
					"source_feed_map as sfm ".
				"WHERE ".
					"src.status=1 AND ".
					"sfm.fid=1 AND ".
					"src.sid=sfm.sid AND ".
					"sfm.mapid=fd.mapid";
	$query = mysql_query($sql) or die(mysql_error());

	
	while ($row = mysql_fetch_object($query))
	{
		array_push($feeds, $row);
	}
	foreach ($feeds as $feed) 
	{
		$sourceFeeds[$feed->sid] = 0;
	}
	foreach ($feeds as $feed)
	{
		$sourceFeeds[$feed->sid] = $sourceFeeds[$feed->sid] + 1;
	}
	return $sourceFeeds;
}

/*
listZippedFiles - Opens the directory $feedsDir and lists all zipped files
Output: returns an array of zipped filenames
*/
function listZippedFiles($feedsDir) 
{
	$zippedFiles = array();
	if ($handle = opendir($feedsDir)) 
	{
		while (false !== ($entry = readdir($handle))) 
		{
			$sid = substr($entry, 0, -30);
			$sid = substr($sid, 1);
			$timestamp = substr($entry, -18);
			$timestamp = substr($timestamp, 0, -8);

			if ($sid != false)
			{
				$file = array(	'sid' => $sid,
									'timestamp' => $timestamp,
									'filename' => $entry
									);
				array_push($zippedFiles, $file);
			}
		}
	}
	return $zippedFiles;
}	

/*
searchForKeyword - searchs the directory $extrDir of extracted xml files and parses
them as strings to see if they contain $keyword.
Output: returns an array of xml file data containing each files timetamp, filename and sid
*/
function searchForKeyword($extrDir, $keywordArray, $keywordCount)
{
	$files = array();
	if ($handle = opendir($extrDir)) 
	{
		while (false !== ($entry = readdir($handle))) 
		{
			if ($entry != '..' && $entry != '.') 
			{
				$tempFile = $extrDir . $entry;
				$xmlDoc = new DomDocument();
				$xmlDoc->load($tempFile);
			
				$track = 0;
				$match = 0;
				for ($acc=0; $acc<$keywordCount; $acc++)
				{
					if (stripos($xmlDoc->saveXML(), $keywordArray[$acc]))
					{
							$timestamp = substr($entry, -14);
							$timestamp = substr($timestamp, 0, -4);
							$sid = substr($entry, 0, -26);
							$sid = substr($sid, 1);
							$file = array(
										'timestamp' => $timestamp, 
										'filename' => $entry,
										'sid' => $sid
										);
							if ($track==0) {
								array_push($files, $file);
								$track++;
							}
					}
				}
			}
		}	
		closedir($handle);
	}
	return $files;
}

/*
clean - clears all files within the directory $extrDir
*/
function cleanTempFolder($extrDir)
{
	if ($handle = opendir($extrDir)) 
	{
		while (false !== ($entry = readdir($handle))) 
		{
			if ($entry != '.' && $entry != '..') 
			{
				unlink($extrDir . $entry);
			}
			
		}
		closedir($handle);
	} else echo 'error: could not open local directory';
}

/*
unzipRecentFiles - Unzips files into a temporary folder
$zippedFiles - Array of filenames (the zipped files)
$feedCount - The number of feeds associated with each source
$feedsDir - Directory of the feeds (zipped)
$extrDir - Directory to extract the zipped files
*/
function unzipRecentFiles($zippedFiles, $feedCount, $feedsDir, $extrDir) 
{
	$filenames = array();
	array_multisort(sortByTimeSetup($zippedFiles), SORT_DESC, $zippedFiles);
	foreach ($zippedFiles as $file) 
	{
		if ($feedCount[$file['sid']] != 0) 
		{
			array_push($filenames, $file['filename']);
			$feedCount[$file['sid']] = $feedCount[$file['sid']] - 1;
		}
	}
	foreach ($filenames as $filename) 
	{
		$zip = new ZipArchive;
		if ($zip->open($feedsDir . $filename) === TRUE) 
		{
			$zip->extractTo($extrDir);
			$zip->close();
		}	
	}
}
/*
sortByDate - sorts the array of files by date, specified by $sort
$sort - specifies how to sort
$files - $files to be sorted
output: sorted $files
*/
function sortByDate($sort, $files)
{
	if ($sort == 'desc') 
		{
			array_multisort(sortByTimeSetup($files), SORT_DESC, $files);
		}
		if ($sort == 'asc') 
		{
			array_multisort(sortByTimeSetup($files), SORT_ASC, $files);
		}
		return $files;
}
/*
getSourceInfroFromDB - querys MySQL DB for information on current sources
Output: $sources - array of sources formatted as $sources[sid]=description
*/
function getSourceInfoFromDB()
{
	$sources = array();
	$con = mysql_connect('localhost','root','') or die('Error: failed to connect');
	mysql_select_db('security', $con) or die(mysql_error($con));
	$sql =	"SELECT " .
					"*" .
				"FROM " .
					"sources";
	$query = mysql_query($sql);
	
	while ($row = mysql_fetch_object($query)) 
	{
		$sources[$row->sid] = $row->desc;
	}
	mysql_close($con);
	
	return $sources;
}
/*
matchKeywords - checks to see if all keywords given are in given item
$keywordArray - array of keywords given
$keywordCount - size of $keywordArray
$node - current node being parsed
Output: $match - the number of keywords found within the item
*/
function matchKeywords($keywordArray, $keywordCount, $node)
{
	$match = 0;
	for ($acc=0; $acc<$keywordCount; $acc++)
	{
		if (stripos($node->title, $keywordArray[$acc]) || stripos($node->description, $keywordArray[$acc])) 
		{						
			$match++;
		}
	}
	return $match;
}
/*
echoHTML - outputs the information of the given $node as html
$node - the current node which information is being extracted
$titles - an array of titles which have already been parsed
$sources - name of the source
$file - the current file we are parsing
*/
function echoHTML($node, $titles, $sources, $file)
{
	if (!array_search((string) $node->title, $titles))
	{
		array_push($titles, (string) $node->title);
		$source = $sources[$file['sid']];
		//$date = date('Y-m-d H:i:s', $file['timestamp']);
		$date = date('Y-m-d H:i:s', $file['timestamp']);
		$html =	"<p>  
					<table width='100%'>  
						<tr> 
							<td width='80%'><h3>$node->title</h3></td><td>$date</td> 
						</tr>  
						<tr>  
							<td><b><i>$source</i></b></td> 
						</tr> 
						<tr> 
							<td>$node->description</td> 
						</tr>  
						<tr> 
							<td><b><a href='$node->link'>$node->link</a><b></td> 
						</tr> 
					</table>
				</p>
				<hr/>";
		echo $html;
	}	
}

?>