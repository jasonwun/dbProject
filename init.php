<!DOCTYPE html >
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <title>Project</title>
  <style>
    /* Always set the map height explicitly to define the size of the div
     * element that contains the map. */
    #map {
      height: 100%;
    }
    /* Optional: Makes the sample page fill the window. */
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
    }

    #filterdiv{
      margin:30px;
    }
 </style>
  </head>

  <?php
  session_start();
  $username =  $_SESSION['username'];
  $uid = $_SESSION['uid'];
  if($_SESSION['ulat'] != NULL){
      $ulat = $_SESSION['ulat'];
  }
  else{
      $ulat = 40.739217;
  }
  if($_SESSION['ulng'] != NULL){
      $ulng = $_SESSION['ulng'];  }
  else{
      $ulng = -73.9754976;
  }
  $utime = $_SESSION['utime'];
  $ustate = $_SESSION['ustate'];
  ?>
  <body style="margin:0px; padding:0px;" onload="initMap()">

    <div id="map" style="width: 50%; height: 100%; float:left"></div>

    <div>
        <label>Hello <?php echo $username?></label><br>
        <label id="latlabel">Your current location is at <?php echo $ulat?></label>
        <label id="lnglabel">, <?php echo $ulng?></label>
        <br>
        <label>Your current time is <?php echo $utime?></label>
    </div>
    <br>
    <div>
        <label>Your Friends</label>
        <table id="FriendsTable" border='3'>
          <tr>
              <th>Friends</th>
          </tr>
        </table>
        <form method="post" action="friend_request.php" style="margin-top : 15px">
          <tr>
            <input name="friendName" type="input"/> <input type="submit" value="Add Friends" name="AddFriend" />
          </tr>
        </form>
    </div>
    <br>
    <div id="loctimediv">
      <form method="post" action="UpdateTime.php" style="margin-top : 15px">
          <label >Time Selector</label>
          <input type="datetime-local" name="userDatetime" id="usercurrenttime"/>
          <input type="submit" value="update" id="updateusercurrenttime"/>
      </form>

    </div>


    <div id="filterdiv">
        <label id="ExistFiltersNum"></label>
        <table id="FilterTable" border='3'>
          <tr>
              <th>FilterName</th>
          </tr>
        </table>
        <form action="AddFilterPost.php" method="post">
            <input type="button" id="addFilter" value="Add new filter"/>
        </form>
       
    </div>
    <div>
        <form action="AddNote.php" method="post">
        <input type="submit" id="addNote" value="Add new Note"/>
        </form>
        
    </div>

    <div id="commentdiv" style="margin-top:20px">
        <table id="commentTable" border="3">
          <tr>
            <th>Create Time</th>
            <th>User Name</th>
            <th>Content</th>
          </tr>
        </table>
        <form method="post" action="AddComment.php">
            <input type="text" name="commentcontent"/>
            <input type="hidden" name="nid" id="hiddennid"/>
            <input type="submit" name="addcomment" value="Create Comment"/> 
        </form>
    </div>

    <script>
      function IntToTime(val){
          var hours = parseInt( val / 60 );
          var min = val - (hours * 60);
          var time = hours + ':' + (min < 10 ? '0' + min : min);
          return time;
      }
      function showVal(newVal){
          var result = IntToTime(newVal);
          var time_pass = "2018-12-12"+ ' ' +result +':00'
          document.getElementById("t_input").value=time_pass;
          document.getElementById("valBox").innerHTML=result;}
    </script>

    <script>

      var filter = {
        id : null,
        name : null
      }

      var filters = [];
      var map;
      var markers = [];
      var infoWindow;
      var locationSelect;
      var usermarker = null;
      var bounds;
      var commentCount = 0;


        function initMap() {
          var loca = {lat: 40.739217, lng: -73.9754976};
          map = new google.maps.Map(document.getElementById('map'), {
            center: loca,
            zoom: 16,
            mapTypeId: 'roadmap',
            mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU}
          });
          infoWindow = new google.maps.InfoWindow();
          bounds = new google.maps.LatLngBounds();
          usermarker = new google.maps.Marker({ //Current Location marker
                          map: map,
                          animation: google.maps.Animation.DROP,
                          icon: "http://maps.google.com/mapfiles/ms/icons/blue.png",
                          draggable:true
                        }); //change marker location by using usermarker.setPosition(latlng)


          map.addListener('click',function(event) {
              usermarker.setPosition(new google.maps.LatLng(event.latLng.lat(), event.latLng.lng()));
              UpdateUserProfile(usermarker.getPosition().lat(),usermarker.getPosition().lng());
              UpdateMapMarkerView();
              document.getElementById("latlabel").innerHTML = "Your current location is at " + event.latLng.lat();
              document.getElementById("lnglabel").innerHTML = ", " + event.latLng.lng();
          });

          usermarker.addListener('dragend', function(event){
            var lat = usermarker.getPosition().lat();
            var lng = usermarker.getPosition().lng();
            UpdateUserProfile(lat,lng);
            UpdateMapMarkerView();
            document.getElementById("latlabel").innerHTML = "Your current location is at " + lat;
            document.getElementById("lnglabel").innerHTML = ", " + lng;
          });
          
          var latlng = new google.maps.LatLng(<?php echo $ulat;?>, <?php echo $ulng;?>);

          usermarker.setPosition(latlng);
          map.setCenter(latlng);
          bounds.extend(latlng);
          map.fitBounds(bounds);

          GetFilters();
          GetFriends();
          UpdateMapMarkerView();
        }

        function UpdateUserProfile(lat, lng){
          var url = "UpdateUser.php?lat=" + lat + "&lng=" + lng;
          downloadUrl(url,function(data){

          });
        }





        function handleEvent(event) {
            document.getElementById('lat').value = event.latLng.lat();
            document.getElementById('lng').value = event.latLng.lng();
        }

      function UpdateMapMarkerView(){ //Update the Note Markers everytime we change our time/location or even login since we display all notes on the map
            clearMarkers();
            var url = "GetNotes.php?uid=" + <?php echo $uid ?>;
            downloadUrl(url, function(data){
              var xml = parseXml(data);
              var NotesNodes = xml.documentElement.getElementsByTagName("Note");

              for(var i = 0; i < NotesNodes.length; i++){
                  var nid = NotesNodes[i].getAttribute("nid");
                  var place_name = NotesNodes[i].getAttribute("place_name");
                  var address = NotesNodes[i].getAttribute("address");
                  var ncontent = NotesNodes[i].getAttribute("ncontent");
                  var latlng = new google.maps.LatLng(
                  parseFloat(NotesNodes[i].getAttribute("lat")),
                  parseFloat(NotesNodes[i].getAttribute("lng")));
                  createMarker(latlng, nid, place_name, ncontent);
                  bounds.extend(latlng);
              }
              if(NotesNodes.length > 0){
                map.fitBounds(bounds);
                
              }
              
            });
      }

      function clearMarkers(){
        if(infoWindow){
          infoWindow.close();
        }
        usermarker.setMap(null);
        usermarker.setMap(map);
        for (var i = 0; i < markers.length; i++) {
           markers[i].setMap(null);
         }
         markers.length = 0;
      }

      function GetFriends(){
          var url = "GetFriends.php";
          downloadUrl(url, function(data){
              var xml = parseXml(data);
              var friendsNodes = xml.documentElement.getElementsByTagName("friend");
              var table = document.getElementById("FriendsTable");
              for(var i = 0; i < friendsNodes.length; i++){
                  var id = friendsNodes[i].getAttribute("id");
                  var name = friendsNodes[i].getAttribute("name");
                  var status = friendsNodes[i].getAttribute("status");
                  var row = table.insertRow(1);
                  var cell1 = row.insertCell(0);
                  cell1.innerHTML = name;
                  if(status == 0){
                    var cell2 = row.insertCell(1);
                    var cell3 = row.insertCell(2);
                    cell2.innerHTML = "<a href=UpdateFriendRequest.php?id=" + id + "&action=0>Approve</a>";
                    cell3.innerHTML = "<a href=UpdateFriendRequest.php?id=" + id + "&action=1>Deny</a>";
                  }
              }
          });
        }

        function GetFilters(){
            url = "getAllFilters.php?uid=" + <?php echo $uid?>;
            downloadUrl(url, function(data){
              var xml = parseXml(data);
              var filterNodes = xml.documentElement.getElementsByTagName("filter");
              var table = document.getElementById("FilterTable");
              for (var i = 0; i < filterNodes.length; i++){
                  var id = filterNodes[i].getAttribute("filterid");
                  var name = filterNodes[i].getAttribute("filterdesc");
                  filters.push({id, name});
                  var row = table.insertRow(1);
                  var cell1 = row.insertCell(0);
                  var cell2 = row.insertCell(1);
                  var cell3 = row.insertCell(2);
                  cell1.innerHTML = name;
                  cell2.innerHTML = "<a href=EditFilterDetail.php?fid=" + id + ">Edit this Filter</a>";
                  cell3.innerHTML = "<a href=deleteFilter.php?fid=" + id + ">Delete this Filter</a>";
              }
            });
        }

        function GetComment(nid){
          var url = "GetComment.php?nid="+nid;
          downloadUrl(url, function(data){
            var xml = parseXml(data);
              var commentNodes = xml.documentElement.getElementsByTagName("comment");
              var table = document.getElementById("commentTable");
              commentCount = commentNodes.length;
              for(var i=0;i < commentNodes.length; i++){
                var content = commentNodes[i].getAttribute("content")
                var time = commentNodes[i].getAttribute("createtime");
                var username = commentNodes[i].getAttribute("username");
                var row = table.insertRow(1);
                var cell1 = row.insertCell(0);
                var cell2 = row.insertCell(1);
                var cell3 = row.insertCell(2);
                cell1.innerHTML = time;
                cell2.innerHTML = username;
                cell3.innerHTML = content;
          }});
        }

       function createMarker(latlng, nid, name, address) {
          var html = "<b>" + name + "</b> <br/>" + address;
          var marker = new google.maps.Marker({
            animation: google.maps.Animation.DROP,
            map: map,
            position: latlng
          });
          google.maps.event.addListener(marker, 'click', function() {
            infoWindow.setContent(html);
            infoWindow.open(map, marker);
            clearComments();
            GetComment(nid);
            document.getElementById("hiddennid").value = nid;

          });
          markers.push(marker);
        }

        function clearComments(){
          var table = document.getElementById("commentTable");
          for( var i = 1; i < commentCount + 1; i++){
            table.deleteRow(1);
          }
          commentCount = 0;
        }

       function downloadUrl(url, callback) {
          var request = window.ActiveXObject ?
              new ActiveXObject('Microsoft.XMLHTTP') :
              new XMLHttpRequest;

          request.onreadystatechange = function() {
            if (request.readyState == 4) {
              request.onreadystatechange = doNothing;
              callback(request.responseText, request.status);
            }
          };

          request.open('GET', url, true);
          request.send(null);
       }

       function parseXml(str) {
          if (window.ActiveXObject) {
            var doc = new ActiveXObject('Microsoft.XMLDOM');
            doc.loadXML(str);
            return doc;
          } else if (window.DOMParser) {
            return (new DOMParser).parseFromString(str, 'text/xml');
          }
       }

       function doNothing() {
        
       }
  </script>

  <script type='text/javascript' src='config.js' ></script>
  <script>
  var my_key = config.MY_KEY;
  
  document.write('<script async defer src="https://maps.googleapis.com/maps/api/js?key=' + my_key + '"><' + '/script>');
  </script>
  <script >
        
  </script>
  </body>
</html>
