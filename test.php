<?php
include 'mangaDownloader.php';

/* ESTE EJEMPLO DESCARGA 2 CAPITULOS ESPECIFICOS DE BLEACH VIA ANIMEXTREMIST */

$obj = new mangaDownloader_ax();
$obj->download('http://www.animextremist.com/mangas-online/mahou-sensei-negima/capitulo-1/mahousenseinegima.html');

/* ESTE EJEMPLO DESCARGA EL ULTIMO MANGA DE NARUTO O UN CAPITULO ESPECIFICO VIA SUBMANGA */

$obj = new mangaDownloader_sm();
$obj->download('http://submanga.com/Naruto/533');

/* ESTE EJEMPLO ENVIA UN EMAIL CON EL CAPI ADJUNTO Y EN ZIP A LOS MIEMBROS DE LA LISTA.
HACE 30 INTENTOS CADA MEDIA HORA HASTA QUE SE PARA Y ENVIA UN EMAIL INFORMANDO QUE NO SE
HA PUBLICADO. SOLO SE PUEDE USAR EN SERVIDORES CON BASH Y GZIP HABILITADO. LO IDEAL ES
LLAMAR A ESTE SCRIPT CON EL DEMONIO CRON :-) */

$users = 'example@example.com, example2@example.com';
$obj = new mangaDownloader_sm();
$obj->send_email('http://submanga.com/One_Piece', $users); 

/* ESTE EJEMPLO DESCARGARA1 CAPITULOS ESPECIFICO DE NARUTO VIA ANIMEREADER */

$obj = new mangaDownloader_mr();
$obj->download('http://www.mangareader.net/naruto/1'); 

?>