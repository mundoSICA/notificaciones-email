Notificaciones por Email.
========================================

>**NotificacionesEmail** Es un _Objeto_ empotrado en un simple script(_ideal en sitios que sólo requieren area de contacto_) escrito en _PHP_ el cual nos permite enviar notificaciones por correo eléctronico, al tiempo que nos realiza una serie de validaciones correspondiente a la configuración que determinemos al crear dicho _Objeto_.


##Modo de uso.

	//Agregamos la libreria
	require_once('notificaciones_email.php');
	
	//Definimos la configuración a usar
	$config = array(
		'origen' => 'Contacto programación SICÁ <programacion@mundosica.com>',
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
	
	//Creamos el Objeto a partir del arreglo de configuración
	$emails = new NotificacionesEmail($config);
	
	//Enviamos los correos electronicos.
     if( $email->sendMails() ){
		echo '<h4>Su mensaje fue enviado</h4>';
	}else{
		echo '<h4>Su mensaje no pudo ser enviado</h4>';
			print_r($email->errors);
    }

##Campos requeridos en el arreglo config.

 - **origen** : Origen de los correos electronicos
 - **destino** : Destino, aquie(nes) llegara dicho correo.
 - **asunto** : Titulo del correo electronico.
 - **validaciones** : Arreglo de campos a validar y a enviar.

Las reglas de validación se deben de ubicar en el arreglo `validaciones`. de la forma:

	$config = array(
			//...
			'validaciones' => array(
				'nombre_de_campo'    => array('regla1','regla2'),
				)
	);

###Lista de las reglas aceptadas:

 - **Text**
 - **DateTime**
 - **Email**
 - **Phone**
 - **Url**
 - **IPv4**
 - **IPv6**

##Licencia.

 - MIT License (http://www.opensource.org/licenses/mit-license.php)
