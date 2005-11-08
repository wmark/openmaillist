<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
		"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>OML</title>
	</head>
	
	<body>
<b>Lists</b>
		<?php

	
		// Connecting, selecting database
		
		$link = mysql_connect($host, $user, $pass)
		or die('Could not connect: ' . mysql_error());
		//echo 'Connected successfully';
		mysql_select_db('test') or die('Could not select database');
		
		// Performing SQL query
		$query = '

		select li.LID, lname as Name, LEmailTo as EmailTo, LDescription as Description,
			count(th.TID) as Threads, 
			count(tm.TID) as Posts
		from Lists li
		left outer join Threads th on (li.LID = th.LID)
		left outer join ThreadMessages tm on (th.TID = tm.TID)
		group by li.lname, li.LEmailTo, li.LDescription
		-- Name 	Status 	Threads 	Posts 	Last post

';
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		
		// Printing results in HTML
		echo "<table>\n";
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		echo "\t<tr>\n";
		foreach ($line as $key => $col_value) {
		echo "\t\t<td><a href=\"http://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]."?list=".$line["LID"]."\">".$col_value."</a></td>\n";
		}
		echo "\t</tr>\n";
		}
		echo "</table>\n";
		
		// Free resultset
		mysql_free_result($result);
		
		// Closing connection
		mysql_close($link);
		?> 

<br><br><br>
<!-- ############################################################################################ -->
<b>Threads</b>		<?php
	
		if ($_GET["list"] != NULL){
		// Connecting, selecting database
		
		$link = mysql_connect($host, $user, $pass)
		or die('Could not connect: ' . mysql_error());
		//echo 'Connected successfully';
		mysql_select_db('test') or die('Could not select database');
		
		// Performing SQL query
		$query = '
		select th.TID,th.Threadname as Subject, tm.Sender, count(tm.tid) as Posts 
		from Threads th
		left outer join ThreadMessages tm on (th.tid = tm.tid) 
		-- In Abhaengikeit von th.tid,
		where '.$_GET["list"].'= th.lid
		group by th.Threadname, tm.Sender
		order by tm.DateReceived
		-- Subject 	Status 	Posts 	Views 	Sender 	Last post
';
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		
		// Printing results in HTML
		echo "<table>\n";
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		echo "\t<tr>\n";
		foreach ($line as $col_value) {
		echo "\t\t<td><a href=\"http://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]."?list=".$_GET["list"]."&thread=".$line["TID"]."\">".$col_value."</a></td>\n";
		}
		echo "\t</tr>\n";
		}
		echo "</table>\n";
		
		// Free resultset
		mysql_free_result($result);
		
		// Closing connection
		mysql_close($link);
		}
		?> 


<!-- ############################################################################################ -->
<br><br><br>
<!-- ############################################################################################ -->
<b>Messages</b>
		<?php
		if ($_GET["list"] != NULL && $_GET["thread"] != NULL){
		// Connecting, selecting database
		
		$link = mysql_connect($host, $user, $pass)
		or die('Could not connect: ' . mysql_error());
		//echo 'Connected successfully';
		mysql_select_db('test') or die('Could not select database');
		
		// Performing SQL query
		$query = '

		select tm.TID , tm.Sender, tm.DateSend as "Send at", tm.DateReceived as "Received at",
			me.Subject as "Subject",  me.body as "Text", 
			att.location as "Location"
		from ThreadMessages tm
		left outer join Messages me on (tm.MsgID = me.MsgID)
		left outer join Attachements att on (tm.MsgID = att.MsgID) 
		where '.$_GET["thread"].' = tm.TID
		order by tm.DateReceived

';
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		
		// Printing results in HTML
		echo "<table>\n";
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		echo "\t<tr>\n";
		foreach ($line as $col_value) {
		echo "\t\t<td>".$col_value."</td>\n";

		}
		echo "\t</tr>\n";
		}
		echo "</table>\n";
		
		// Free resultset
		mysql_free_result($result);
		
		// Closing connection
		mysql_close($link);
		}
		?> 
<!-- ############################################################################################ -->
<!-- ############################################################################################ -->








<!--
	<ul style="list-style-type:none" >
		<li>Eins</li>
		<li>Zwei</li>
		<li>Drei</li>
	</ul>
-->



</body>
</html>