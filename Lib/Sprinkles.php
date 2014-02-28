<?php

App::uses( 'Router', 'Routing' );



/**
 *	Utilities.
 *
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@package Sprinkles.Lib
 *	@license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class Sprinkles {

	/**
	 *
	 */

	public static function bound( $min, $value, $max ) {

		if ( $value < $min ) {
			return $min;
		}

		if ( $value > $max ) {
			return $max;
		}

		return $value;
	}



	/**
	 *	Tests if the string $haystack ends with $needle.
	 *
	 *	@param string $haystack String to test against.
	 *	@param string $needle Supposed end of the string.
	 *	@return boolean True if $haystack ends with $needle, otherwise false.
	 */

	public static function endsWith( $haystack, $needle ) {

		$needleLength = strlen( $needle );

		if ( $needleLength > strlen( $haystack )) {
			return false;
		}

		return ( substr_compare( $haystack, $needle, -$needleLength, $needleLength ) === 0 );
	}



	/**
	 *	Returns the request URL as an array understandable by Router::url( ).
	 *
	 *	@param CakeRequest The request.
	 *	@return array The request URL.
	 */

	public static function url( CakeRequest $Request ) {

		$url = Router::parse( $Request->here( ));

		// removes useless passed args

		$reserved = [ 'plugin', 'controller', 'action', 'named', 'pass' ];
		$params = $url;

		foreach ( $reserved as $key ) {
			unset( $params[ $key ]);
		}

		for ( $i = 0; $i < count( $params ); $i++ ) {
			array_shift( $url['pass']);
		}

		if ( $url['plugin'] === null ) {
			unset( $url['plugin']);
		}

		// named params

		if ( !empty( $url['named'])) {
			$url = array_merge( $url, $url['named']);
		}

		unset( $url['named']);

		// passed args

		if ( !empty( $url['pass'])) {
			$url = array_merge( $url, $url['pass']);
		}

		unset( $url['pass']);

		// query string

		$query = $Request->query;

		if ( !empty( $query )) {
			$url['?'] = http_build_query( $query );
		}

		return $url;
	}
}
