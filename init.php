<!DOCTYPE html >
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
  
  <body style="margin:0px; padding:0px;" onload="initMap()">
    
    <div id="map" style="width: 50%; height: 100%; float:left"></div>
    <div id="LoginForm">
      <label>UserName:</label>
      <input type="text" id="usernameInput" size="15"/>
      <label>Password:</label>
      <input type="text" id="passwordInput" size="15"/>
      <input type="button" id="loginbutton" value="Login"/>
      <label id="loginstatus" style="color:Red; display:none" >UserName/Password combination is not correct</label>
    </div>
    <label id="UserName" for="displayUserName" style="display:none"></label>
    <div>
         <label for="raddressInput">Search location:</label>
         <input type="text" id="addressInput" size="15"/>
        <label for="radiusSelect">Radius:</label>
        <select id="radiusSelect" label="Radius">
          <option value="50" selected>50 kms</option>
          <option value="30">30 kms</option>
          <option value="20">20 kms</option>
          <option value="10">10 kms</option>
        </select>

        <input type="button" id="searchButton" value="Search"/>
    </div>
    <div><select id="locationSelect" style="width: 10%; visibility: hidden"></select></div>
    <div id="loctimediv">
          <label >Time Selector</label>
          <input type="datetime-local" name="userDate" id="usercurrenttime"/>
          <input type="button" value="update" id="updateusercurrenttime"/>
          <label id="timeupdatestatus">The datetime value should not be earliear than 2018-09-25 00:00:00</label>
    </div>

    <div id="filterdiv">
        <label id="ExistFiltersNum"></label>
        <table id="FilterTable" border='3'>
          <tr>
              <th>FilterId</th>
              <th>FilterName</th>
          </tr>
        </table>
        <input type="button" id="editFilters" value="Add new filter"/>
    </div>


    <script>
      var user = {
        id : null,
        name : null,
        latlng : null,
        time : null,
        state : null,
      }

      var filter = {
        id : null,
        name : null
      }

      var filters = [];
      var map;
      var markers = [];
      var infoWindow;
      var locationSelect;

      

        function initMap() {
          var loca = {lat: 40.739217, lng: -73.9754976};
          map = new google.maps.Map(document.getElementById('map'), {
            center: loca,
            zoom: 16,
            mapTypeId: 'roadmap',
            mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU}
          });
          infoWindow = new google.maps.InfoWindow();

          var usermarker = new google.maps.Marker({ //Current Location marker
                          map: map,
                          animation: google.maps.Animation.DROP,
                          icon: 'https://maps.google.com/mapfiles/kml/shapes/info-i_maps.png'
                        }); //change marker location by using usermarker.setPosition(latlng)

          searchButton = document.getElementById("searchButton").onclick = searchLocations;
          loginButton = document.getElementById("loginbutton").onclick = login;
          locationSelect = document.getElementById("locationSelect");
          locationSelect.onchange = function() {
            var markerNum = locationSelect.options[locationSelect.selectedIndex].value;
            if (markerNum != "none"){
              google.maps.event.trigger(markers[markerNum], 'click');
            }
          };
        }

        function login(){
          var status = document.getElementById("loginstatus");
          status.style.display = "none";
          var username = document.getElementById("usernameInput").value;
          var psd = document.getElementById("passwordInput").value;
          loginRequest("login.php?username="+username+"&password="+psd, function(data){
                var xml = parseXml(data);
                var userNodes = xml.documentElement.getElementsByTagName("users");
                var id = userNodes[0].getAttribute("id");
                var name = userNodes[0].getAttribute("username");
                var latlng = new google.maps.LatLng(
                  parseFloat(userNodes[0].getAttribute("lat")),
                  parseFloat(userNodes[0].getAttribute("lng")));
                var time = userNodes[0].getAttribute("time");
                var state = userNodes[0].getAttribute("state");
                user.id = id;
                user.name = name;
                user.latlng = latlng;
                user.time = time;
                user.state = state;
                document.getElementById("UserName").textContent = "Hello " + name;
                document.getElementById("UserName").style.display = "inline";
                document.getElementById("LoginForm").style.display = "none";
                map.setCenter(latlng);


                GetFilters();
        });
      }

        function GetFilters(){
          if(user.id != null){
            url = "getAllFilters.php?uid=" + user.id;
            GetFiltersRequest(url, function(data){
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
                  var cell4 = row.insertCell(3);
                  cell1.innerHTML = id;
                  cell2.innerHTML = name;
                  cell3.innerHTML = "<a href=editFilter.php?fid=" + id + ">Edit this Filter</a>";
                  cell4.innerHTML = "<a href=deleteFilter.php?id=" + id + ">Delete this Filter</a>";
              }
            });
          }
        }

        function GetFiltersRequest(url, callback){
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


        function loginRequest(url, callback){
          var request = window.ActiveXObject ?
              new ActiveXObject('Microsoft.XMLHTTP') :
              new XMLHttpRequest;

           request.onreadystatechange = function() {
            if (request.readyState == 4) {
              request.onreadystatechange = doNothing;
              callback(request.responseText, request.status);
            }
            else{
              var status = document.getElementById("loginstatus");
              status.style.display = "inline";
            }  
          };

          request.open('GET', url, true);
          request.send(null);
        }

       function searchLocations() {
         var address = document.getElementById("addressInput").value;
         var geocoder = new google.maps.Geocoder();
         geocoder.geocode({address: address}, function(results, status) {
           if (status == google.maps.GeocoderStatus.OK) {
            searchLocationsNear(results[0].geometry.location);
           } else {
             alert(address + ' not found');
           }
         });
       }

       function clearLocations() {
         infoWindow.close();
         for (var i = 0; i < markers.length; i++) {
           markers[i].setMap(null);
         }
         markers.length = 0;

         locationSelect.innerHTML = "";
         var option = document.createElement("option");
         option.value = "none";
         option.innerHTML = "See all results:";
         locationSelect.appendChild(option);
       }

       function searchLocationsNear(center) {
         clearLocations();

         var radius = document.getElementById('radiusSelect').value;
         var searchUrl = 'locations.php?lat=' + center.lat() + '&lng=' + center.lng() + '&radius=' + radius;
         var tmp = 1;
         downloadUrl(searchUrl, function(data) {
           var xml = parseXml(data);
           var markerNodes = xml.documentElement.getElementsByTagName("marker");
           var bounds = new google.maps.LatLngBounds();
           for (var i = 0; i < markerNodes.length; i++) {
             var id = markerNodes[i].getAttribute("id");
             var name = markerNodes[i].getAttribute("name");
             var address = markerNodes[i].getAttribute("address");
             var distance = parseFloat(markerNodes[i].getAttribute("distance"));
             var latlng = new google.maps.LatLng(
                  parseFloat(markerNodes[i].getAttribute("lat")),
                  parseFloat(markerNodes[i].getAttribute("lng")));

             createOption(name, distance, i);
             createMarker(latlng, name, address);
             bounds.extend(latlng);
             map.setCenter(latlng);
           }
           map.fitBounds(bounds);
           locationSelect.style.visibility = "visible";
           locationSelect.onchange = function() {
             var markerNum = locationSelect.options[locationSelect.selectedIndex].value;
             google.maps.event.trigger(markers[markerNum], 'click');
           };
         });
       }

       function createMarker(latlng, name, address) {
          var html = "<b>" + name + "</b> <br/>" + address;
          var marker = new google.maps.Marker({
            map: map,
            position: latlng
          });
          google.maps.event.addListener(marker, 'click', function() {
            infoWindow.setContent(html);
            infoWindow.open(map, marker);
          });
          markers.push(marker);
        }

       function createOption(name, distance, num) {
          var option = document.createElement("option");
          option.value = num;
          option.innerHTML = name;
          locationSelect.appendChild(option);
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

       function doNothing() {}
  </script>
  <script type='text/javascript' src='config.js' ></script>
  <script>
  var my_key = config.MY_KEY;
  document.write('<script async defer src="https://maps.googleapis.com/maps/api/js?key=' + my_key + '&callback=initMap"><' + '/script>');
  </script>
  
  </body>
</html>