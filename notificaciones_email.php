<?php
/**  _   _   _   _   _   _   _   _   _   _   _   _   _  
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
 *                Algunas ideas extraidas del Objeto Validation de cakePHP (cakePHPv1.2.0.3830/cake/libs/validation.php)
 * @copyright     2011, SICÁ - Soluciones Integrales en Computación Áplicada.
 * @link          http://mundosica.github.com/notificaciones_email/
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @since         NotificacionesEmail v 0.1
 *
 ****************************************************************************************/

class NotificacionesEmail {
	/** variables a usar **/
	public $msg = '';
	public $errors = array();
	public $config = null;
	
	protected $validaciones = array(
			'Text' => array(
							'error' => 'Texto invalido en el campo <strong>%s</strong>',
							'regex' => '',
						),
			'Email' => array(
							'error' => 'Correo electrinico Invalido en el campo <strong>%s</strong>',
							'regex' => '',
						),
			'Phone' => array(
							'error' => 'Telefono Invalido en el campo <strong>%s</strong>',
							'regex' => '',
						),
			'DateTime' => array(
							'error' => 'Fecha invalida en el campo <strong>%s</strong>',
							'regex' => '',
						),
			'Url' => array(
							'error' => 'Dirección URL invalida en el campo <strong>%s</strong>',
							'regex' => '',
						),
			'Required' =>  array(
							'error' => 'Error campo <strong>%s</strong> Requerido',
							'regex' => '',
						)
	);

	/************************************************************************************
	 *                             Functiones de validación                             *
	 ************************************************************************************/
	 
	/**
	* Revisa si el campo $check es texto, sin caracteres especiales.
	* 
	* @param string $check el valor a revisar
	* @return boolean Resultado de la validación
	* @access publico
	* @link http://mundosica.github.com/notificaciones_email/
	**/
	function validText( $check=null ) {
		$pattern = '[-_a-z0-9 .,áéíóúüÁÉÍÓÚÜñÑ#]*';
		return preg_match('/^' . $pattern . '$/i',$check);
	}
	/**
	* Revisa si el campo $check corresponde a una dirección de correo valida.
	* 
	* @param string $check el valor a revisar.
	* @return boolean Resultado de la validación
	* @access publico
	* @link http://mundosica.github.com/notificaciones_email/
	**/
	function validEmail( $check=null ) {
		$pattern = '[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*@';
		$pattern .= '(?:[a-z0-9][-a-z0-9]*\.)*(?:[a-z0-9][-a-z0-9]{0,62})\.(?:(?:[a-z]{2}\.)?[a-z]{2,4}|museum|travel)';
		return preg_match('/^' . $pattern . '$/i',$check);
	}
	/**
	* Revisa si el campo $check corresponde a una dirección IP versión 4.
	* 		Ejemplos: 127.0.0.1, 192.168.10.123, 203.211.24.8
	* 
	* @param string $check el valor a revisar.
	* @return boolean Resultado de la validación
	* @access publico
	* @link http://mundosica.github.com/notificaciones_email/
	**/
	function validIPv4( $check=null ){
		$pattern = '(?:(?:25[0-5]|2[0-4][0-9]|(?:(?:1[0-9])?|[1-9]?)[0-9])\.){3}(?:25[0-5]|2[0-4][0-9]|(?:(?:1[0-9])?|[1-9]?)[0-9])';
		return preg_match('/^' . $pattern . '$/', $check);
	}
	/**
	* Revisa si el campo $check corresponde a una dirección IP versión 6.
	* 		Ejemplos: ::1, 2001:0db8::1428:57ab
	* 
	* @param string $check el valor a revisar.
	* @return boolean Resultado de la validación
	* @access publico
	* @link http://mundosica.github.com/notificaciones_email/
	**/
	function validIPv6( $check=null ){
		$pattern  = '((([0-9A-Fa-f]{1,4}:){7}(([0-9A-Fa-f]{1,4})|:))|(([0-9A-Fa-f]{1,4}:){6}';
		$pattern .= '(:|((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})';
		$pattern .= '|(:[0-9A-Fa-f]{1,4})))|(([0-9A-Fa-f]{1,4}:){5}((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})';
		$pattern .= '(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:)';
		$pattern .= '{4}(:[0-9A-Fa-f]{1,4}){0,1}((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2}))';
		$pattern .= '{3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:){3}(:[0-9A-Fa-f]{1,4}){0,2}';
		$pattern .= '((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)|';
		$pattern .= '((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:){2}(:[0-9A-Fa-f]{1,4}){0,3}';
		$pattern .= '((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2}))';
		$pattern .= '{3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:)(:[0-9A-Fa-f]{1,4})';
		$pattern .= '{0,4}((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)';
		$pattern .= '|((:[0-9A-Fa-f]{1,4}){1,2})))|(:(:[0-9A-Fa-f]{1,4}){0,5}((:((25[0-5]|2[0-4]';
		$pattern .= '\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4})';
		$pattern .= '{1,2})))|(((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})))(%.+)?';
		return preg_match('/^' . $pattern . '$/', $check);
	}
	/**
	* Revisa si el campo $check es un número telefonico valido
	* 
	* @param string $check el valor a revisar.
	* @return boolean Resultado de la validación
	* @access publico
	* @link http://mundosica.github.com/notificaciones_email/
	**/
	function validPhone( $check=null ) {
		$pattern = '[-0-9\s\(\)]{7,30}';
		return preg_match('/^' . $pattern . '$/i',$check);
	}
	/**
	* Revisa si el campo $check es una fecha generada por el Plugin JQueryDateTimePicker.
	*
	* @param string $check value to check
	* @return boolean Resultado de la validación
	* @access publico
	* @link http://www.projectcodegen.com/JQueryDateTimePicker.aspx
	**/
	function validDateTime( $check=null ) {
		$pattern = '%^(0[1-9]|1[0-2])/(0[1-9]|[1-3][0-9])/([1-2][0-9]{3}) (0[1-9]|1[0-2]):[0-5][0-9] (AM|PM)%';
		return preg_match($pattern,$check);
	}
	/**
	* Revisa si el campo $check corresponde a una URL valida.
	*
	* @param string $check valor a revisar
	* @return boolean Resultado de la validación
	* @access publico
	* @link http://mundosica.github.com/notificaciones_email/
	**/
	function validUrl( $check=null ) {
		$validChars = '([' . preg_quote('!"$&\'()*+,-.@_:;=~[]') . '\/0-9a-z\p{L}\p{N}]|(%[0-9a-f]{2}))';
		$hostname = '(?:[a-z0-9][-a-z0-9]*\.)*(?:[a-z0-9][-a-z0-9]{0,62})\.(?:(?:[a-z]{2}\.)?[a-z]{2,4}|museum|travel)';
		$pattern = '/^(?:(?:https?|ftps?|file|news|gopher):\/\/)' . '(?:' . $hostname . ')' .
			'(?::[1-9][0-9]{0,4})?' .
			'(?:\/?|\/' . $validChars . '*)?' .
			'(?:\?' . $validChars . '*)?' .
			'(?:#' . $validChars . '*)?$/iu';
		return preg_match($pattern,$check);
	}
	/**
	* Inicializa el objeto 
	*
	* @param array $config arreglo de configuración
	* @return void
	* @access publico
	* @link http://mundosica.github.com/notificaciones_email/
	**/
	public function __construct( $config = null ) {
			if( is_array( $config ) && !empty( $config ) )
				$this->configurar( $config );
	}
	/**
	* Setea la configuración.
	*
	* @param array $config arreglo de configuracion
	* @return void
	* @access publico
	* @link http://mundosica.github.com/notificaciones_email/
	**/
	public function configurar( $config ) {
		$this->config = $config;
	}
    
	/**
	* Envia los correos electronicos segun la configuración
	*
	* @param tipo $parametro1 descripción del párametro 1.
	* @return Boolean el resultado de la acción.
	* @access publico
	* @link http://mundosica.github.com/notificaciones_email/
	**/
	function sendMails() {
		foreach($this->config['validaciones'] as $field=>$rules):
			if( !isset($_POST[$field]) || $_POST[$field] == "" ):
					if( in_array('Required',$rules,true) )
						array_push($this->errors, sprintf($this->validaciones['Required']['error'], $field ) );
					continue;
			endif;
			$value_field = $_POST[$field];
			foreach($rules as $rule):
				if($rule == 'Required')
					continue;
				if( isset( $this->validaciones[$rule] ) ){
					if( !$this->{'valid' . $rule }($value_field) )
						array_push($this->errors, sprintf($this->validaciones['Required']['error'], $field) );
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
		#$headers .= 'To: Fitorec <programacion@mundosica.com>,Registros <registro@enoaxaca.com.mx>' . "\r\n";
		$headers .= 'From: ' .$this->config['origen'] . "\r\n";
		#$headers .= 'Cc: eymard@mundosica.com' . "\r\n";
		#$headers .= 'Bcc: programacion@mundosica.com' . "\r\n";
		if( @mail($this->config['destino'], $subject, $message, $headers) )
			return true;
		else
			$this->errors[] = 'No se pudo enviar el correo elctronico, Revise la configuración del servidor.';
		return false;
	}
}
