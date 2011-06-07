<?php

include("config.php");

$sql = "SELECT ticketID, name, subject, ost_ticket.created, ost_ticket.updated,
    ost_staff.email FROM ost_ticket LEFT JOIN ost_staff USING (staff_id) WHERE
    ost_ticket.dept_id = 1 AND closed IS NULL ORDER BY created DESC";

$res = mysql_query($sql);
?><!DOCTYPE html>
<html>
 <head>
 <meta http-equiv="refresh" content="120">
 <link href='http://fonts.googleapis.com/css?family=Maven+Pro' rel='stylesheet' type='text/css'>
 <link href='http://fonts.googleapis.com/css?family=Cabin+Sketch:bold' rel='stylesheet' type='text/css'>
 <script language="javascript" type="text/javascript" src="jquery.js"></script>
 <script language="javascript" type="text/javascript" src="jquery.flot.js"></script>
 <style>
 tr:nth-child(2n+2) {
   background-color: #ddd;
 }
 table, td, th, tr { 
   font-family: 'Maven Pro'; 
   margin: 0; 
   padding: 5px; 
   border-spacing: 0;
 }
 hr { 
   width: 75%; 
 }
 p { 
   font-family: 'Maven Pro'; 
 }
 h1 { 
   font-family: 'Cabin Sketch'; 
 }
 </style>
 </head>
 <body>
  <div id="plot" style="width: 50%; height: 100px; float: right; margin-right: 80px"></div>
  <h1>How busy is help@iweb.co.uk?</h1>
  <p>
   Normally, <em>really busy</em>. The graph to the right shows how many tickets
   are left over at the end of each day, unclosed.
  </p>
  <hr/>
  <table width="100%">
   <tr>
    <th>Ticket ID</th>
    <th>Raised by</th>
    <th>Subject</th>
    <th>Created</th>
    <th>Assigned to</th>
   </tr>
   <?php while($r = mysql_fetch_assoc($res)) { ?>
   <tr>
    <td><?= $r['ticketID'] ?></td>
    <td><?= $r['name'] ?></td>
    <td><?= $r['subject'] ?></td>
    <td><?= $r['created'] ?></td>
    <td><?= $r['email'] ?></td>
   </tr>
   <?php } ?>
</table>
<script>
var options = {
    xaxis: {
        mode: "time",
        timeformat: "%y/%m/%d",
    }
}

var d = [
[
<?php 
$res = mysql_query("SELECT UNIX_TIMESTAMP(DATE(closed)) * 1000 ts, (SELECT
    COUNT(*) FROM ost_ticket WHERE created <= DATE(x.closed) and DATE(closed) >
    DATE(x.closed)) q FROM ost_ticket x WHERE dept_id = 1 GROUP BY 
    DATE(closed)");
while($r = mysql_fetch_assoc($res)) {
    if($r['ts']) {
        ?>[<?= $r['ts'] ?>, <?= $r['q'] ?> ], <?php
    }
} 
?>
]];
var plot = $.plot('#plot', d, options);
</script>
<a href="http://github.com/insom/osticket-stats"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://d3nwyuy0nl342s.cloudfront.net/img/30f550e0d38ceb6ef5b81500c64d970b7fb0f028/687474703a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f6f72616e67655f6666373630302e706e67" alt="Fork me on GitHub"></a>
</body>
</html>
