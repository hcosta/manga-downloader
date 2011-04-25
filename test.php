<?php
include 'mangaDownloader.php';

//catalogo  ---> http://www.animextremist.com/mangas.htm      XD    //

/*ESTE EJEMPLO DESCARGA 2 CAPITULOS ESPECIFICOS DE BLEACH VIA ANIMEXTREMIST *

$obj = new mangaDownloader_ax();
$obj->download("http://www.animextremist.com/mangas-online/naruto/capitulo-504/naruto.html");

*/

/*ESTE EJEMPLO DESCARGA EL ULTIMO MANGA DE NARUTO O UN CAPITULO ESPECIFICO VIA SUBMANGA */

$obj = new mangaDownloader_sm();
//$obj->last("http://submanga.com/Naruto");
$obj->download("http://submanga.com/Naruto/2");
 

/*ESTE EJEMPLO (futuramente) ENVIARA UN EMAIL CON EL CAPI ADJUNTO Y EN ZIP A LOS MIEMBROS DE LA LISTA *

$users = array('sirservorius@gmail.com', 'hcostaguzman@gmail.com', 'urashima.h@gmail.com'); //franhp@franhp.com
$obj = new mangaDownloader_sm();
$ultimo = $obj->last("http://submanga.com/Naruto");
$obj->enviarManga($ultimo, $users); 

*/

?>