<?php

include("config.php");

$sql = "SELECT ticketID, name, subject, ost_ticket.created, ost_ticket.updated,
    ost_staff.email FROM ost_ticket LEFT JOIN ost_staff USING (staff_id) WHERE
    ost_ticket.dept_id = 1 AND closed IS NULL ORDER BY created DESC";

$res = mysql_query($sql);
echo mysql_num_rows($res);
