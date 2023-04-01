<?php
        error_reporting(0);
        $conn = mysqli_connect("localhost", "root", "", "project");
          
        if($conn === false){
            die("ERROR: Could not connect. " 
                . mysqli_connect_error());
        }
        
        //$place = $_GET['place'];
          
        //$sql = "select * from crops where location= '$place'"; 
        $sql = "select * from crops where location= 'bhopal'"; 
        //$sql="SELECT * FROM `crops`where location IN ('bhopal','ashta','sehore')";

         $id[0] = "";
         $name[0] = "";
         $cname[0] = "";
         $location[0] = "";
         $price[0] = "";
         $lot[0] = "";
         $longi[0] = "";
         $i=0;
        

        if ($result = mysqli_query($conn,$sql)) {
            while ($row = mysqli_fetch_assoc($result)) {
                $field1name = $row["id"];
                $field2name = $row["name"];
                $field3name = $row["cname"];
                $field4name = $row["location"];
                $field5name = $row["price"];
                $field6name = $row["latitude"];
                $field7name = $row["longitude"];

                $id[$i] = $field1name;
             $name[$i] = $field2name;
             $cname[$i] = $field3name;
             $l[$i] = $field4name;
             $price[$i] = $field5name;
                 $lot[$i] = $field6name;
                 $longi[$i] = $field7name;

             $i=+1;
            }
             //foreach( $l as $value ) {
            //echo "Value is $value <br />";
         //}
         }
        $result->free();
        mysqli_close($conn);
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.1.1/mapbox-gl.js'></script>
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.1.1/mapbox-gl.css' rel='stylesheet' />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    
    <style type="text/css">
      #search-div{
  margin: 40px 40px;
}

#map{
  height: 600px;
  width: 100%;
}

.geocoder {
  width: 80%;
}

.mapboxgl-popup {
  max-width: 200px;
}

.mapboxgl-popup-content {
  font-family: 'Open Sans', sans-serif;
}

    </style>
    <title>Map</title>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">SAARTHI</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Items
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" tabindex="-1" aria-disabled="true">Contact</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </nav>
    <div class="container">

      <div id='search-div' class="row justify-content-center">
        
        <form method="get" action="index.php" >
        <div class="col-sm-3">
          <div id='geocoder' class="geocoder"></div>
           <!-- <input type="text" name="place"> -->
        </div>
        
        <div class="col-sm-2">
          <input type="submit" id='current-loc-btn' class="btn btn-outline-dark" value="Search Location">
        </div>
        </form>
      </div>


      <div id="map">



      </div>


    </div>

    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.0/mapbox-gl-geocoder.min.js"></script>
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.0/mapbox-gl-geocoder.css" type="text/css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

    <script>
        var id = <?php echo json_encode($id, JSON_HEX_TAG); ?>;
//id=id.toString();
//id=id.split(",");
var x = <?php echo json_encode($name); ?>;
var cname = <?php echo json_encode($cname, JSON_HEX_TAG); ?>;
var loc = <?php echo json_encode($l, JSON_HEX_TAG); ?>;
var price = <?php echo json_encode($price, JSON_HEX_TAG); ?>;
var latitude = <?php echo json_encode($lot, JSON_HEX_TAG); ?>;
var longitude = <?php echo json_encode($longi, JSON_HEX_TAG); ?>;

//var div = document.getElementById("dom-target");
//var location = div.innerHTML;

console.log(id);
console.log(x);
console.log(cname);
console.log(loc);
console.log(price);
console.log(latitude);
console.log(longitude);


mapboxgl.accessToken = 'pk.eyJ1IjoieWFzaGt1bmR1IiwiYSI6ImNrb3VkMnE0dDA1aTMyd3Fqb2lkOW85bGYifQ.NKkAvJyVeqg_43mvB3LLQQ';

document.getElementById('current-loc-btn').addEventListener('click', function(){
  navigator.geolocation.getCurrentPosition(recenterMap, function(){}, {enableHighAccuracy: true});
});

//creating a map-------------------------------------------------------------------------------------------
const map = new mapboxgl.Map({
  container: 'map',
  style: 'mapbox://styles/mapbox/streets-v11',
  center: [77.1025, 28.7041],
  zoom: 8
});

const nav = new mapboxgl.NavigationControl();
map.addControl(nav);

const geocoder = new MapboxGeocoder({
accessToken: mapboxgl.accessToken,
mapboxgl: mapboxgl
});

document.getElementById('geocoder').appendChild(geocoder.onAdd(map));
// ---------------------------------------------------------------------------------------------------------

for(let i=0;i<cname.length;i++){
    const marker = {
      coords: {lng: longitude[i], lat: latitude[i]},
      name: cname[i],
      price: price[i],
      locName: loc[i]
    };
  addMarker(marker);
}

function recenterMap(position){
  map.flyTo({
    center: [position.coords.longitude, position.coords.latitude],
    zoom: 10,
    essential: true
  });
}


function addMarker(props){

  const popup = new mapboxgl.Popup({ offset: 25 }).setText(
    props.content
  );

  const marker = new mapboxgl.Marker({color:'green'})
    .setLngLat([props.coords.lng, props.coords.lat])
    .setPopup(new mapboxgl.Popup({ offset: 25 })
    .setHTML(`
      <div class="card" style="width: 12rem; background-color:#e5fac3;">
      <div class="card-body">
        <h5 class="card-title">${props.name}</h5>
        <p class="card-text">
          Price: ${props.price}/Kg<br>
          Location: ${props.locName}
        </p>
        <a href="#" class="btn btn-outline-success btn-sm">Contact</a>
      </div>
      </div>
     `))
    .addTo(map);
}



    </script>
  </body>
</html>
