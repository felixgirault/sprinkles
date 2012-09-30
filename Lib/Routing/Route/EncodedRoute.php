<?php

/**
 *	A custom route class which offers a graceful and transparent way to handle
 *	the encoding and decoding of some of the url's parameters.
 *
 *	Here's an example of how you may use this route class :
 *
 *	```
 *		App::uses( 'EncodedRoute', 'Sprinkles.Lib/Routing/Route' );
 *
 *		Router::connect(
 *			'/articles/:id-:slug',
 *			array( 'controller' => 'articles', 'action' => 'read' ),
 *			array(
 *				'id' => '[a-z0-9]+',
 *				'slug' => '[a-z0-9-]+',
 *				'pass' => array( 'id', 'slug' ),
 *				'routeClass' => 'EncodedRoute', // we're using the route
 *				'encode' => array( 'id' ) // the id parameter will be encoded
 *				'encodeCallback' => 'EncodedRoute::encodeNumber',
 *				'decodeCallback' => 'EncodedRoute::decodeNumber'
 *			)
 *		);
 *	```
 *
 *	In this case, if you are dealing with the id '123456789', it will be
 *	converted to '21i3v9' in the urls, without changing anything in the models,
 *	views or controllers code.
 *	
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@package Sprinkles.Lib.Routing.Route
 *	@license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class EncodedRoute extends CakeRoute {

	/**
	 *	Constructor for an EncodedRoute.
	 *
	 *	### Options
	 *
	 *	- 'encode' - An array of parameters to encode.
	 *	- 'encodeCallback' - A function used to encode parameters, defaults to
	 *		EncodedRoute::encodeString.
	 *	- 'decodeCallback' - A function used to decode parameters, defaults to
	 *		EncodedRoute::decodeString.
	 *
	 *	@see CakeRoute::__construct( )
	 */

	public function __construct( $template, $defaults = array( ), $options = array( )) {

		$options = array_merge(
			array(
				'encode' => array( ),
				'encodeCallback' => 'EncodedRoute::encodeString',
				'decodeCallback' => 'EncodedRoute::decodeString'
			),
			$options
		);

		parent::__construct( $template, $defaults, $options );
	}



	/**
	 *	Decodes all required parameters.
	 *
	 *	@see CakeRoute::parse( )
	 *	@param string $url The url to attempt to parse.
	 *	@return mixed Boolean false on failure, otherwise an array or parameters
	 */

	public function parse( $url ) {

		$this->_checkCallback( $this->options['decodeCallback']);

		$params = parent::parse( $url );

		if ( $params !== false ) {
			foreach ( $this->options['encode'] as $param ) {
				if ( isset( $params[ $param ])) {
					$params[ $param ] = call_user_func(
						$this->options['decodeCallback'],
						urldecode( $params[ $param ])
					);
				}
			}
		}

		return $params;
	}



	/**
	 *	Encodes all required parameters.
	 *
	 *	@see CakeRoute::match( )
	 *	@param array $url An array of parameters to check matching with.
	 *	@return mixed Either a string url for the parameters if they match or false.
	 */
	
	public function match( $url ) {

		$this->_checkCallback( $this->options['encodeCallback']);

		foreach ( $this->options['encode'] as $param ) {
			if ( isset( $url[ $param ])) {
				$url[ $param ] = urlencode(
					call_user_func(
						$this->options['encodeCallback'],
						$url[ $param ]
					)
				);
			}
		}

		return parent::match( $url );
	}



	/**
	 *	Checks if the given callback is callable. If not, an exception is raised.
	 *
	 *	@param callback $callback The callback to check.
	 *	@throws CakeException
	 */

	protected function _checkCallback( $callback ) {

		if ( !is_callable( $callback )) {
			throw new CakeException(
				sprintf(
					'EncodedRoute: %s is not a valid callback function.',
					( string )$callback
				)
			);
		}
	}



	/**
	 *	Converts the given number from base 10 to base 36.
	 *
	 *	@param int $number Number to convert.
	 *	@return string Encoded number.
	 */

	public static function encodeNumber( $number ) {

		return base_convert( $number, 10, 36 );
	}



	/**
	 *	Decodes the given number from base 36 to base 10.
	 *
	 *	@param string $number Number to decode.
	 *	@return int Original number.
	 */

	public static function decodeNumber( $number ) {

		return base_convert( $number, 36, 10 );
	}



	/**
	 *	Encodes the given string to base 64.
	 *
	 *	@param string $sting String to convert.
	 *	@return string Encoded string.
	 */

	public static function encodeString( $string ) {

		return base64_encode( $string );
	}



	/**
	 *	Decodes the given string from base 64.
	 *
	 *	@param string $sting String to convert.
	 *	@return string Original string.
	 */

	public static function decodeString( $string ) {

		return base64_decode( $string );
	}
}
