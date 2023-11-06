<?php
require_once('./Antares.php');

Antares::init([
    "PLATFORM_URL" => 'https://platform.antares.id:8443', // TODO: Change this to your platform URL
    "ACCESS_KEY" => '908442793ffccbbf:2ecc959c713e64a3' // TODO: Change this to your access key
]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Antares GET/POST</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        .container {
            padding-top: 50px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        h1 {
            text-align: center;
            color: white; 
            size: 200px;    
        }
        h2 {
          text-align: center;
          color: #0B60AC;
          padding-bottom: 10px;
        }
        input {
            text-align: center;
        }
        
    </style>
        <link
      rel="stylesheet"
      href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
      integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" type="text/css" href="styles.css" />

    <!-- The core Firebase JS SDK is always required and must be listed first -->
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>

    <!-- TODO: Add SDKs for Firebase products that you want to use
        https://firebase.google.com/docs/web/setup#available-libraries -->
    <script src="https://www.gstatic.com/firebasejs/8.8.1/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-storage.js"></script>

    <script>
      // REPLACE WITH YOUR web app's Firebase configuration
      const firebaseConfig = {
        apiKey: "AIzaSyBotDoJ-nt4ONTh-5K3hXV6oHDem24yKHQ",
        authDomain: "ecosonicuny-f6058.firebaseapp.com",
        projectId: "ecosonicuny-f6058",
        storageBucket: "ecosonicuny-f6058.appspot.com",
        messagingSenderId: "37068989687",
        appId: "1:37068989687:web:b5f9ae15c4355611d59ff2",
      };
      // Initialize Firebase
      firebase.initializeApp(firebaseConfig);

      // Get a reference to the storage service, which is used to create references in your storage bucket
      var storage = firebase.storage();

      // Create a storage reference from our storage service
      var storageRef = storage.ref();
    </script>
    <script src="app.js" defer></script>

    <!-- Leaflet Map -->
    <link
      rel="stylesheet"
      href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
    />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <style>
      #map {
        height: 455px;
      }
    </style>

</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <table>
                <tr>
                    <!-- <th>Time (WIB)</th>
                    <th>Resource Index (ri)</th>
                    <th>Raw Data</th>
                    <th>Parsed Data</th> -->
                    <!-- <th>Map</th> New column for the map -->
                </tr>
                <?php
                try {
                    $resp = Antares::getInstance()->get('/antares-cse/antares-id/ecosonic/promini');
                    $first10 = $resp->listContentInstanceUris(1);
                    foreach ($first10 as $uri) {
                        $payload = Antares::getInstance()->get($uri);

                        // echo "<tr>";
                        // echo "<td>";
                        $date = strtotime($payload->getCreationTime());
                        // echo date('Y-m-d h:i:s', $date);
                        // echo "</td>";
                        // echo "<td>";
                        $resuri = $payload->ri;
                        // echo $resuri;
                        // echo "</td>";
                        // echo "<td>";
                        $data = json_decode($payload->getContent());

                        // Display the raw JSON data
                        $encoded = json_encode($data, JSON_PRETTY_PRINT);
                        // echo "<pre>" . $encoded . "<pre/>";

                        $imageUrl = "https://firebasestorage.googleapis.com/v0/b/ecosonicuny-f6058.appspot.com/o/data%2Fphoto.jpg?alt=media&token=158553fa-58a8-4d26-b7fe-2021429c1569";
                        $imageWidth = "500";
                        $imageHeight = "250";
                        // Parse and display specific data after the comma
                        $parsedData = "";
                        if (isset($data->data)) {
                            $parsedData = $data->data;
                        }

                        // echo "</td>";
                        // echo "<td>";
                        list($latitude, $longitude, $idobject) = explode(',', $parsedData);

                        // echo "latitude: $latitude<br>";
                        // echo "longitude: $longitude<br>";
                        // echo "idobject: $idobject<br>";

                    }
                } catch (Exception $e) {
                    echo ($e->getMessage());
                }
                ?>
            </table>
        </div>
    </div>
</div>
    
    <h2>
    <?php echo "Object ID: $idobject<br>"?>
    </h2>
    <h2>
      <?php echo "<img src='$imageUrl' width='$imageWidth' height='$imageHeight'"?>
              </h2>
    <div id="map"></div>
    <script>
      // Define latitude and longitude variables
var latitude = <?php echo $latitude; ?>;
var longitude = <?php echo $longitude; ?>;

      // Initialize the map
      var map = L.map("map").setView([latitude, longitude], 13);

      // Add a base layer (you can use different map providers)
      L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
        attribution:
          '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
      }).addTo(map);

      // Add a marker at the specified latitude and longitude
      var marker = L.marker([latitude, longitude]).addTo(map);

      // Add a popup to the marker
      marker
        .bindPopup("Latitude: " + latitude + "<br>Longitude: " + longitude)
        .openPopup();
    </script>
    <div>
      <?php
      echo "</td>";
      echo "<td>";
      
      $mapLink = "https://maps.google.com/maps/place/$latitude,$longitude";
      echo "<a href='$mapLink' target='_blank'>View Map</a>";
      echo "</td>";
      echo "</tr>";
      ?>
    </div>
    
    <script>
  // Function to reload the page
  function refreshData() {
    location.reload();
  }

  // Refresh the page every 30 seconds (adjust the time interval as needed)
  setInterval(refreshData, 3500); // 30,000 milliseconds = 30 seconds
</script>

</body>
</html>