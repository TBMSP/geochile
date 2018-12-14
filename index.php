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

//<CORS
cors();
function cors(){
if (isset($_SERVER['HTTP_ORIGIN'])){
header("Access-Control-Allow-Origin:{$_SERVER['HTTP_ORIGIN']}");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400');
}
if($_SERVER['REQUEST_METHOD']=='OPTIONS'){
if(isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
if(isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
exit(0);
}
}
//CORS>

if(isset($_GET['lat'])&&isset($_GET['lon'])){//verifica que "lat" y "lon" esten especificados en la URL
$geo=json_decode(file_get_contents("localities/CL.json"),true);//carga el archivo CL.json y lo almacena en un array
$max=count($geo);//obtiene la cantidad de items del array "geo"
$lat=$_GET['lat'];//obtiene el parametro "lat" especificado en la URL
$lon=$_GET['lon'];//obtiene el parametro "lon" especificado en la URL
if(isset($_GET['max'])){
$lmt=intval($_GET['max']);//obtiene el parametro "max" especificado opcionalmente en la URL
if($lmt<=$max){$max=$lmt;}//verifica que la cantidad de items que se devolveran no sea mayor que la cantidad de items ya almacenados en el array
}
$ref=array('Latitude'=>$lat,'Longitude'=>$lon);//se guarda esto como referencia, aqui se almacena los parametros "lay" y "lon" de la URL
foreach($geo as $key=>$row){
$distance=round(Distance($row,$ref),1);//se calcula y se redondea la distancia entre las localidades del array y la referencia en kilometros
$geo[$key]["Distance"]=$distance;//se guarda el parametro "Distance" en el array
$dir="";
$g1=$geo[$key]["Latitude"];
$g2=$geo[$key]["Longitude"];
if(abs(floatval($g1)-floatval($lat))>0.2){if(floatval($g1)>floatval($lat)){$dir="S";}else{$dir="N";}}//se verifica si es al norte o al sur para entregar una mejor referencia
if(abs(floatval($g2)-floatval($lon))>0.2){if(floatval($g2)<floatval($lon)){$dir=$dir."E";}else{$dir=$dir."O";}}//se verifica si es al este o al oeste para entregar una mejor referencia
$al=" al $dir";
if($dir==""){$al="";}
$geo[$key]["Reference"]=round($distance)." km$al de ".$geo[$key]["Name"];//se guarda el parametro "Reference" en el array
}
$data=[];//se crea un array vacio
foreach($geo as $key=>$row){
$data[$key]=$row['Distance'];//se almacena el parametro "Distance" de todos los items del array en el nuevo array vacio
}
array_multisort($data,SORT_ASC,$geo);//el array "geo" se ordena de acuerdo al parametro "Distance" segun el array "data"
array_splice($geo,$max,count($geo));//se limita el array "geo" (filtro "max")
if(isset($_GET['radiuskm'])){//verifica que el parametro "radiuskm" existe en la URL
$data=[];//se crea un array vacio
foreach($geo as $key=>$row){
$rkm=floatval($row['Distance']);//se obtiene la distancia del array
if($rkm>floatval($_GET['radiuskm'])){break;}//si la distancia supera a "radiuskm" entonces se detiene el loop
$data[$key]=$row;
}
$geo=$data;
}
header('Content-Type: application/json');//se le dice al php que lo que va a entregar es json
echo json_encode($geo);//se imprime la informacion en json
}
function Distance($a,$b){
$lat1=$a["Latitude"];
$lon1=$a["Longitude"];
$lat2=$b["Latitude"];
$lon2=$b["Longitude"];
$theta=$lon1-$lon2;
$dist=sin(deg2rad($lat1))*sin(deg2rad($lat2))+cos(deg2rad($lat1))*cos(deg2rad($lat2))*cos(deg2rad($theta));
$dist=acos($dist);
$dist=rad2deg($dist);
$miles=$dist*60*1.1515;
return $miles*1.609344;
}
