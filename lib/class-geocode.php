<?php
class Geocode {

  function get_localities($country) {
    return json_decode(file_get_contents('localities/'.$country.'.json'), true);
  }

  function get_distance($localitie_1, $localitie_2){
  $dist = $this->compute_distance($localitie_1['Latitude'], $localitie_2['Latitude'], /* Calculate theta */$localitie_1['Longitude'] - $localitie_2['Longitude']);
  return $this->distances_to_km($dist);
  }

  function compute_distance($lat_1, $lat_2, $theta) {
    return rad2deg(acos(sin(deg2rad($lat_1)) * sin(deg2rad($lat_2)) + cos(deg2rad($lat_1)) * cos(deg2rad($lat_2)) * cos(deg2rad($theta))));
  }

  function distances_to_km($dist) {
    $miles = $dist*60*1.1515;
    $kilometers = $miles*1.609344;
    return $kilometers;
  }

  function print_json_localities($localities) {
    echo json_encode($localities);
  }
}
