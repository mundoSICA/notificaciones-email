<?php
//Agregamos la libreria
require_once('notificaciones_email.php');
	
//Definimos la configuraciÃ³n a usar
$config = array(
	'origen' => 'Contacto programacion SICA <programacion@mundosica.com>',
	'destino' => 'eymard@mundosica.com, ibaangr@gmail.com, chanerec@gmail.com',
	'asunto' => 'Algun asunto',
	'validaciones' => array(
		'nombre'    => array('Required','Text'),
		'telefono'  => array('Phone'),
		'email'     => array('Email'),
		'pagina_internet' => array('Required','Url'),
		'fecha'     => array('Required','DateTime'),
		)
);
	
//Creamos el Objeto a partir del arreglo de configuracion
$emails = new NotificacionesEmail($config);

//Enviamos los correos electronicos.
if( $emails->sendMails() ){
	echo '<h4>Su mensaje fue enviado</h4>';
}else{
	echo '<h4>Su mensaje no pudo ser enviado</h4>';
	echo "\n<pre>".print_r($emails->errors,true)."</pre>\n";
}

