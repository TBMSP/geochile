# GeoChile
Esta es una api de Geocodificación, para que, con las coordenadas Latitud y Longitud se entregue una lista de ciudades cercanas.

Se trata de un script en PHP para retornar un JSON con las localidades Chilenas cercanas a las coordenadas antes especificadas en los parametros de la URL, funciona a través del método GET.

Esto es utilizado por <a href="http://chilealerta.com">Chile Alerta</a> e <a href="http://sismologia.net">INSIMU</a> para generar informes sísmicos con sus respectivas referencias geográficas (especialmente el parametro "Reference" del json).

Ejemplo:
```
http://ejemplo.cl/?lat=-33&lon=-71&max=10
```
Resultado:

<img src="https://github.com/TBMSP/geochile/blob/master/ejemplo.png">
<img src="https://github.com/TBMSP/geochile/blob/master/ejemplo2.png">

Creado por <a href="https://twitter.com/TBMSP">@TBMSP</a>
