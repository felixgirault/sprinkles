<?php

App::uses( 'Sprinkles', 'Sprinkles.Lib' );



/**
 *
 *
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@package Sprinkles.Model.Behavior
 *	@license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class Thumbnail {

	/**
	 *
	 *
	 *	@var string
	 */

	const all = 'all';



	/**
	 *
	 *
	 *	@var Generator
	 */

	protected static $_Generator = null;



	/**
	 *
	 */

	protected static $_defaults = array(
		self::all => array(
			'levels' => 3,
			'format' => 'png'
		)
	);



	/**
	 *
	 *
	 *	@var array
	 */

	protected static $_formats = array( );



	/**
	 *
	 */

	public static function configure( $generator, array $formats ) {

		list( $plugin, $class ) = pluginSplit( $generator, true );
		$class .= 'Generator';

		App::uses( $class, $plugin . 'Lib/Thumbnail/Generator' );

		if ( !class_exists( $class )) {
			throw new CakeException( "The `$class` generator does not exist." );
		}

		self::$_Generator = new $class( );

		$formats += self::$_defaults;
		$defaults = $formats[ self::all ];
		unset( $formats[ self::all ]);

		foreach ( $formats as $name => $options ) {
			self::$_formats[ $name ] = array_merge( $defaults, $options );
		}
	}



	/**
	 *	@param string|array $configuration Config name or config array.
	 */

	public static function path( $format, $key ) {

		$options = self::_options( $format );

		$hash = md5( $key );
		$levels = Sprinkles::bound( 2, $options['levels'], strlen( $hash ) - 1 );
		$start = substr( $hash, 0, $levels );

		return $format
			. DS . implode( DS, str_split( $start ))
			. DS . substr( $hash, $levels ) . '.' . $options['format'];
	}



	/**
	 *
	 */

	protected static function _options( $format ) {

		if ( !isset( self::$_formats[ $format ])) {
			throw new CakeException( '' );
		}

		return self::$_formats[ $format ];
	}



	/**
	 *
	 */

	public static function generate( $format, $source, $key = '' ) {

		if ( empty( $key )) {
			$key = $source;
		}

		$options = self::_options( $format );

		$destination = IMAGES . self::path( $format, $key );
		$destinationDir = dirname( $destination );

		if ( !is_dir( $destinationDir )) {
			mkdir( $destinationDir, 0777, true );
		}

		return self::$_Generator->generate(
			$options,
			$source,
			$destination
		);
	}
}
