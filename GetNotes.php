<?php
require("db.inc.php");

$uid = $_GET["uid"];

$dom = new DOMDocument("1.0");

$node = $dom->createElement("Note");
$parnode = $dom->appendChild($node);

$pdo = pdo_connect();

$createAllNotequery = "
create temporary table AllNote 
select nid, nuid, place_name, address, lat, lng, nradius, nvisibility, ncontent, sdate,startime, endtime, daynum, tagname from Note 
natural join location 
natural join schedule
natural join notetag 
natural join tag 
left join repeatschedule on repeatschedule.schedule_id = Note.schedule_id
left join repeats on repeatschedule.repeat_id = repeats.repeat_id;";

$pdo->query($createAllNotequery);

$createAllFilterquery = sprintf("
create TEMPORARY table AllFilter
select fid, ftag, fuid, tagname, fstate, lat,lng, ulatt, ulong, fradius, fdate, fstarttime, fendtime, utime, fvisibility, fname, ustate, tid,user2
from filter
natural join location
join Users on fuid = uid
join tag on tid = ftag
left join Friendship on fuid = user1
where fuid = %d;", $uid);

$pdo->query($createAllFilterquery);


$finalquery = "
select distinct AllNote.nid, nuid, place_name, ncontent, AllNote.lat lat, AllNote.lng lng, ftag, tid, ustate, fstate, nvisibility, fvisibility
from AllNote
join AllFilter
where mydistance(ulatt, ulong, AllFilter.lat, AllFilter.lng) <= fradius
and mydistance(ulatt,ulong, AllNote.lat, AllFilter.lng) <= nradius
And Date(utime) = fdate And time(utime) between fstarttime And fendtime
And (Date(utime) = sdate or dayofweek(utime) = daynum)
And ((ftag = tid and ustate = fstate) Or (ftag=tid and fstate = null) Or (ftag=null And fstate=null) Or (ftag = null And fstate=ustate))
And ((fvisibility= 'everyone' And (nvisibility='everyone' Or (nvisibility = 'friend' And nuid=user2) Or (nvisibility = 'private' And nuid=fuid)))
Or (fvisibility='friend' And nuid=user2 And (nvisibility= 'everyone' Or nvisibility= 'friend')));";
$result = $pdo->query($finalquery);


header("Content-type: text/xml");
// Iterate through the rows, adding XML nodes for each
while ($row = $result->fetch()){
  $node = $dom->createElement("Note");
  $newnode = $parnode->appendChild($node);
  $newnode->setAttribute("nid", $row['nid']);
  $newnode->setAttribute("place_name", $row['place_name']);
  $newnode->setAttribute("ncontent", $row['ncontent']);
  $newnode->setAttribute("lat", $row['lat']);
  $newnode->setAttribute("lng", $row['lng']);
}
echo $dom->saveXML();

?>