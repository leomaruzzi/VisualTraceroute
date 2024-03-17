<?php
if (isset($_POST['dest'])) {
    $destination = $_POST['dest'];
    $command = "traceroute -n $destination";
    $output = shell_exec($command);

    $lines = explode("\n", $output);
    $routerIPs = [];
    $markers = [];

    foreach ($lines as $index => $line) {
        if (preg_match('/\b(?:\d{1,3}\.){3}\d{1,3}\b/', $line, $matches)) {
            $variableName = "routerIP_$index";
            $$variableName = $matches[0]; 
            $routerIPs[] = $$variableName; 

            $url = "http://ip-api.com/json/{$matches[0]}";
            $response = file_get_contents($url);
            $data = json_decode($response, true);

            if ($data['status'] === 'success') {
                $country = $data['country'];
                $city = $data['city'];
                $latitude = $data['lat'];
                $longitude = $data['lon'];
                $popupContent = "Routeur IP : $matches[0] ($country, $city)";

                $marker = array(
                    'lat' => $latitude,
                    'lng' => $longitude,
                    'popup' => $popupContent
                );
                
                $markers[] = $marker;
            } 
        }
    }
}


include('index.html');
?>
