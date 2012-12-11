<?php

App::uses( 'Sprinkles', 'Sprinkles.Lib' );



/**
 *
 *
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@package Sprinkles.View.Helper
 *	@license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class ThumbnailHelper extends AppHelper {

	/**
	 *
	 *
	 *	@var integer
	 */

	protected $_level = 2;



	/**
	 *
	 *
	 *	@var string
	 */

	protected $_basePath = '/img/';



	/**
	 *
	 *
	 *	@var string
	 */

	protected $_extension = 'jpg';



	/**
	 *
	 */

	public function __construct( View $view, array $settings = array( )) {

		$required = App::import(
			'Vendor',
			'Sprinkles.PHPThumb',
			array(
				'file' => 'PHPThumb' . DS . 'ThumbLib.inc.php'
			)
		);

		if ( !$required ) {
			throw new CakeException( 'PHPThumb is not installed.' );
		}

		parent::__construct( $view, $settings );

		if ( isset( $settings['level']) && is_int( $settings['level'])) {
			$this->_level = $settings['level'];
		}

		if ( isset( $settings['extension']) && is_string( $settings['extension'])) {
			$this->_extension = $settings['extension'];
		}
	}



	/**
	 *	FAIRE DES THUMBS DE 300 * 168
	 */

	public function path( $key ) {

		return $this->url( '/img/thumbs/' . rand( 1, 14 ) . '.jpg' );

		$hash = md5( $key );
		$level = Sprinkles::bound( 2, $this->_level, strlen( $hash ) - 1 );

		$start = substr( $hash, 0, $level );
		$path = preg_replace( '/([a-z0-9])/i', '$1/', $start );

		return $this->_basePath . $path . substr( $hash, $level ) . '.' . $this->_extension;
	}
}
