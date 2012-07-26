<?php

App::uses( 'SessionHelper', 'View/Helper' );



/**
 *	Extends the capabilities of the original SessionHelper.
 *	Typically meant to be used instead of it, using an alias in your controller :
 *
 *	```
 *		public $helpers = array(
 *			'Session' => array(
 *				'className' => 'Sprinkles.ExtendedSession'
 *			)
 *		);
 *	```
 *
 *	@package Sprinkles.View.Helper
 *	@author Félix Girault <felix.girault@gmail.com>
 */

class ExtendedSessionHelper extends SessionHelper {

	/**
	 *	Returns if there is pending flash messages in session.
	 *
	 *	@return boolean true if there is pending messages, false otherwise.
	 */

	public function hasFlash( ) {

		return ( bool )$this->read( 'Message' );
	}


	/**
	 *	Returns all pending flash messages from the session.
	 *
	 *	@return string messages 
	 */

	public function flashAll( ) {
		
		$messages = CakeSession::read( 'Message' );
		$html = '';

		foreach ( $messages as $type => $flash ) {
			$vars = $flash['params'];
			$vars['message'] = $flash['message'];

			$html .= $this->_View->element( $flash['element'], $vars );

			CakeSession::delete( "Message.$type" );
		}

		return $html;
	}
}
