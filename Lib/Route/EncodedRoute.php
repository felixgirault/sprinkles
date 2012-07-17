<?php

/**
 *	A custom route class which offers a graceful and transparent way to handle
 *	the encoding and decoding of some of the url's parameters.
 *
 *	Here's an example of how you may use this route class :
 *
 *	{{{
 *		App::uses( 'EncodedRoute', 'Sprinkles.Lib/Route' );
 *
 *		Router::connect(
 *			'/articles/:id-:slug',
 *			array( 'controller' => 'articles', 'action' => 'read' ),
 *			array(
 *				'pass' => array( 'id', 'slug' ),
 *				'routeClass' => 'EncodedRoute', // we're using the route
 *				'encode' => array( 'id' ) // the id parameter will be encoded
 *				'encodeCallback' => 'EncodedRoute::encodeNumber',
 *				'decodeCallback' => 'EncodedRoute::decodeNumber'
 *			)
 *		);
 *	}}}
 *
 *	In this case, if you are dealing with the id '123456789', it will be
 *	converted to '21i3v9' in the urls, without changing anything in the models,
 *	views or controllers code.
 *
 *	@author Félix Girault <felix.girault@gmail.com>
 */

class EncodedRoute extends CakeRoute {

	/**
	 *	Constructor for an EncodedRoute.
	 *
	 *	### Options :
	 *		- 'encode' - An array of parameters to encode.
	 *		- 'encodeCallback' - A function used to encode parameters,
	 *			defaults to EncodedRoute::encodeString.
	 *		- 'decodeCallback' - A function used to decode parameters,
	 *			defaults to EncodedRoute::decodeString.
	 *
	 *	@see CakeRoute::__construct( )
	 */

	function __construct( $template, $defaults = array( ), $options = array( )) {

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
	 */

	function parse( $url ) {

		$params = parent::parse( $url );

		if ( is_callable( $this->options['decodeCallback'])) {
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
		}

		return $params;
	}



	/**
	 *	Encodes all required parameters.
	 *
	 *	@see CakeRoute::_writeUrl( )
	 */

	function _writeUrl( $params ) {

		if ( is_callable( $this->options['encodeCallback'])) {
			foreach ( $this->options['encode'] as $param ) {
				if ( isset( $params[ $param ])) {
					$params[ $param ] = urlencode(
						call_user_func(
							$this->options['encodeCallback'],
							$params[ $param ]
						)
					);
				}
			}
		}

		return parent::_writeUrl( $params );
	}



	/**
	 *	Converts the given number from base 10 to base 36.
	 *
	 *	@param int $number number to convert.
	 *	@return string encoded number.
	 */

	static function encodeNumber( $number ) {

		return ( string ) base_convert(( int ) $number, 10, 36 );
	}



	/**
	 *	Decodes the given number from base 36 to base 10.
	 *
	 *	@param string $number number to decode.
	 *	@return int original number.
	 */

	static function decodeNumber( $number ) {

		return ( int ) base_convert(( string ) $number, 36, 10 );
	}



	/**
	 *	Encodes the given string to base 64.
	 *
	 *	@param string $number number to convert.
	 *	@return string encoded string.
	 */

	static function encodeString( $string ) {

		return base64_encode( $string );
	}



	/**
	 *	Decodes the given string from base 64.
	 *
	 *	@param string $number number to convert.
	 *	@return string original string.
	 */

	static function decodeString( $string ) {

		return base64_decode( $string );
	}
}

?>

