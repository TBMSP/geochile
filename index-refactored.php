<?php
/*
 * This file is part of the GeoChile package.
 *
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * @author Matias Gutierrez <soporte@tbmsp.net>
 * @file index.php
 *
 * Project home:
 *   https://github.com/tbmsp/geochile
 *
*/

require_once 'lib/class-geocode.php';
$geocode = new Geocode;

// JSON and CORS
header('Content-Type: application/json');
header('Access-Control-Max-Age: 86400');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");

$payload = json_decode(file_get_contents('php://input'), true);
$lat = $payload['lat'];
$lng = $payload['lng'];
$country = $payload['country'];
$max = $payload['limit'];
$rkm = $payload['radiuskm'];

// verifies that latitude and longitude are specified in the request
if (!isset($lat) || !isset($lng)) {
    echo '{"message":"missing lat or lng parameter"}';
    exit();
}
// loads the localities
$localities = $geocode->get_localities($country);
$localities_count = count($localities);

if (!isset($max)) {
  $max = '10';
}

  foreach($localities as $key => $row){
    //se calcula y se redondea la distancia entre las localidades del array y la referencia en kilometros
    $distance = round ($geocode->get_distance($row, ['Latitude' => $lat, 'Longitude' => $lng]), 1);
    //se guarda el parametro "Distance" en el array
    $localities[$key]["Distance"] = $distance;
    $direction = "";
    $g1 = $localities[$key]["Latitude"];
    $g2 = $localities[$key]["Longitude"];
    //se verifica si es al norte o al sur para entregar una mejor referencia
    if (abs(floatval($g1) - floatval($lat)) > 0.2) {
      if (floatval($g1) > floatval($lat)){
        $direction = "S";
      }
      else{
        $direction = "N";
      }
    }
    //se verifica si es al este o al oeste para entregar una mejor referencia
    if (abs(floatval($g2) - floatval($long)) > 0.2){
      if (floatval($g2) < floatval($long)) {
        $direction = $direction."E";
      }
      else{
        $direction = $direction."O";
      }
    }
    //se guarda el parametro "Reference" en el array
    $localities[$key]["Reference"] = round($distance)." km al $direction de ".$localities[$key]["Name"];
  }

  $data = [];

  foreach ($localities as $key=>$row) {
    //se almacena el parametro "Distance" de todos los items del array en el nuevo array vacio
    $data[$key] = $row['Distance'];
  }
  //el array "geo" se ordena de acuerdo al parametro "Distance" segun el array "data"
  array_multisort($data, SORT_ASC, $localities);
  //se limita el array "geo" (filtro "max")
  array_splice($localities, $max, count($localities));

  //verifica que el parametro "radiuskm" existe en la URL
  if(!empty($max)) {
    //se crea un array vacio
    $data = [];
    foreach ($localities as $key => $row) {
      //se obtiene la distancia del array
      $rkm = floatval($row['Distance']);
      //si la distancia supera a "radiuskm" entonces se detiene el loop
      if($rkm > floatval($rkm)) {
        break;
      }
      $data[$key] = $row;
    }
    $localities = $data;
  }

  //se imprime la informacion en json
  $geocode->print_json_localities($localities);
