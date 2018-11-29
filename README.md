# GeoChile
Esta es una api de Geocodificación, para que, con las coordenadas Latitud y Longitud se entregue una lista de ciudades cercanas.

Se trata de un script en PHP para retornar un JSON con las localidades Chilenas cercanas a las coordenadas antes especificadas en los parametros de la URL, funciona a través del método POST con un body JSON.

Esto es utilizado por <a href="http://chilealerta.com">Chile Alerta</a> e <a href="http://sismologia.net">INSIMU</a> para generar informes sísmicos con sus respectivas referencias geográficas (especialmente el parametro "Reference" del json) y tambien por <a href="http://mascotasperdidas.cl/">Mascotas Perdidas Chile</a> para la organización de Ubicaciones.

---

# Parametros
------------------------------------

**lat**: (float) Es la Latitud.

**lng**: (float) Es la Longitud.

**limit**: (int) Filtro de la cantidad máxima de ítems a devolver (opcional).

**radiuskm**: (float) Este es un filtro, el radio máximo de distancia, puede reducir la cantidad máxima de acuerdo a que si el ítem a devolver esta dentro del radio máximo seleccionado en kilometros (opcional).

---

# Ejemplo:
------------------------------------
```
curl -d '{"lat":"-31", "lng":"-71", "country":"CL", "limit":10}' -H "Content-Type: application/json" -X POST http://localhost/
```
Resultado:

<img src="https://github.com/TBMSP/geochile/blob/master/ejemplo.png">
<img src="https://github.com/TBMSP/geochile/blob/master/ejemplo2.png">

Creado por <a href="https://twitter.com/TBMSP">@TBMSP</a>
