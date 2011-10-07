<?php
/*   _   _   _   _   _   _   _   _   _   _   _   _   _  
 *  / \ / \ / \ / \ / \ / \ / \ / \ / \ / \ / \ / \ / \ 
 * ( m | u | n | d | o | s | i | c | a | . | c | o | m )
 *  \_/ \_/ \_/ \_/ \_/ \_/ \_/ \_/ \_/ \_/ \_/ \_/ \_/ 
 * 
 * Envio de correos electronico.
 * 
 * @Author        fitorec <programacion@mundosica.com>
 * @file          notificaciones_email.php
 * @package       NotificacionesEmail
 * @Description   Envia notificaciones por email realizando validaciones previas.
 * @copyright     2011, SICÁ - Soluciones Integrales en Computación Áplicada.
 * @link          http://mundosica.com/notificaciones_email
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @since         NotificacionesEmail v 0.1Alpha
 * 
 */

class NotificacionesEmail {
	/* variables to use */
	var $msg = '';
	var $errors = array();
	var $config = null;

	/************************************************************************************
	 *                             Functiones de validación                             *
	 ************************************************************************************/
	function validText($str='') {
		$validChars = 'a-z0-9 .,áéíóúüÁÉÍÓÚÜñÑ#';
		return preg_match('/^[' . $validChars . ']*$/i',$str);
	}
	function validEmail($mail='') {
		return preg_match('/^[A-Z0-9._%-]+@[a-z][A-Z0-9.-]+\\.[A-Z]{2,4}$/i',$mail);
	}
	/**
	* Check that a value is a valid phone number.
	* 
	* @param string $phone value to check
	* @return  boolean Success
	* @access public
	* @link 
	*/
	function validPhone($phone=null) {
		$validChars = '-0-9\s\(\)';
		return preg_match('/^[' . $validChars . ']{7,30}$/i',$phone);
	}
	/**
	* Check that a value is a valid human DateTime.
	*
	* @param string $dateTime value to check
	* @return boolean Success
	* @access public
	* @link http://www.projectcodegen.com/JQueryDateTimePicker.aspx
	*/
	function validDateTime( $dateTime ) {
		$regex = '%^(0[1-9]|1[0-2])/(0[1-9]|[1-3][0-9])/([1-2][0-9]{3}) (0[1-9]|1[0-2]):[0-5][0-9] (AM|PM)%';
		return preg_match($regex,$dateTime);
	}
	/**
	* Revisa si la URL es valida.
	*
	* @param string $URL valor a revisar
	* @return boolean Resultado
	* @access public
	* @link
	*/
	function ValidUrl( $url ) {
		$validChars = '([' . preg_quote('!"$&\'()*+,-.@_:;=~[]') . '\/0-9a-z\p{L}\p{N}]|(%[0-9a-f]{2}))';
		$hostname = '(?:[a-z0-9][-a-z0-9]*\.)*(?:[a-z0-9][-a-z0-9]{0,62})\.(?:(?:[a-z]{2}\.)?[a-z]{2,4}|museum|travel)';
		$regex = '/^(?:(?:https?|ftps?|file|news|gopher):\/\/)' . '(?:' . $hostname . ')' .
			'(?::[1-9][0-9]{0,4})?' .
			'(?:\/?|\/' . $validChars . '*)?' .
			'(?:\?' . $validChars . '*)?' .
			'(?:#' . $validChars . '*)?$/iu';
		return preg_match($regex,$url);
	}
	/**
	 * Init to object
	 *
	 * @param array $config arreglo de configuracion
	 * @return void
	 * @access publico
	 */
	public function __construct($config) {
        	$this->configurar($config);
	}
	/**
	 * Setea la configuracion.
	 *
	 * @param array $config arreglo de configuracion
	 * @return void
	 * @access publico
	 */
    public function configurar($config) {
        $this->config = $config;
    }
    
	/*
	 * Envia los correos electronicos segun la configuracion
	 *
	 * @param tipo $parametro1 descripción del párametro 1.
	 * @return Boolean el resultado de la acción.
	 * @access publico
	 * @link
	 */
	function sendMails() {
		$typesValidations = array(
			'Text' => 'Texto invalido',
			'Email' => 'Correo electrinico Invalido',
			'Phone' => 'Telefono Invalido',
			'DateTime' => 'Fecha invalida',
			'Url' => 'Direccion URL invalida'
		);
		foreach($this->config['validaciones'] as $field=>$rules):
			$value_field = $_POST[$field];
			#ALERT: In future check if Required no is present in the firts positions
			if($value_field == '' && $rules[0] != 'Required')
				continue;
			foreach($rules as $rule):
				if($rule == 'Required')
					continue;
				if( isset( $typesValidations[$rule] ) ){
					if( !$this->{'valid' . $rule }($value_field) )
						$this->errors[]=$typesValidations[$rule] . " en el campo <b>$field</b>";
				}
			endforeach;
		endforeach;
		if( !empty( $this->errors) )
			return false;
		// subject
		$subject = $this->config['asunto'];
		$message = "<html>
		<head>
		  <title>{$subject}</title>
		</head>
		<style type='text/css'>
			body{ background: #E6E6E6;color:#111}
		</style>
		<body>
		  <h2>{$subject}</h2>
		  <h3>Datos</h3>
		  <ul>";
		 ##
		 foreach($this->config['validaciones'] as $field=>$rules):
			$message .= '<li><b>'.$field.': </b> '.$_POST[$field].'</li>';
		 endforeach;
		$message .= ' </ul></body></html>';
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf8' . "\r\n";
		// Additional headers
		#$headers .= 'To: Fitorec <chanerec@gmail.com>,Registros <registro@enoaxaca.com.mx>,Eymard <eymard@mundosica.com>' . "\r\n";
		$headers .= 'From: ' .$this->config['origen'] . "\r\n";
		#$headers .= 'Cc: eymard@mundosica.com' . "\r\n";
		#$headers .= 'Bcc: fitorec@mundosica.com' . "\r\n";
		if( @mail($this->config['destino'], $subject, $message, $headers) )
			return true;
		else
			$this->errors[] = 'No se pudo enviar el correo elctronico, Revise la configuración del servidor.';
		return false;
	}
}
