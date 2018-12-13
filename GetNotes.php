<?php
require("db.inc.php");

$uid = $_GET["uid"];

$dom = new DOMDocument("1.0");

$node = $dom->createElement("Notes");
$parnode = $dom->appendChild($node);

$pdo = pdo_connect();

$createAllNotequery = "
create temporary table allnote 
select nid, nuid, place_name, address, lat, lng, nradius, nvisibility, ncontent, sdate,startime, endtime, daynum, tagname from note 
natural join location 
natural join schedule
natural join notetag 
natural join tag 
left join repeatschedule on repeatschedule.schedule_id = note.schedule_id
left join repeats on repeatschedule.repeat_id = repeats.repeat_id;";

$pdo->query($createAllNotequery);

$createAllFilterquery = sprintf("
create TEMPORARY table allfilter
select fid, ftag, fuid, tagname, fstate, lat,lng, ulatt, ulong, fradius, fdate, fstarttime, fendtime, utime, fvisibility, fname, ustate, tid,user2
from filter
natural join location
join users on fuid = uid
join tag on tid = ftag
left join friendship on fuid = user1
where fuid = %d;", $uid);

$pdo->query($createAllFilterquery);


$finalquery = "
select distinct allnote.nid, nuid, place_name, ncontent, allnote.lat lat, allnote.lng lng, ftag, tid, ustate, fstate, nvisibility, fvisibility
from allnote
join allfilter
where mydistance(ulatt, ulong, allfilter.lat, allfilter.lng) <= fradius
and mydistance(ulatt,ulong, allnote.lat, allfilter.lng) <= nradius
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