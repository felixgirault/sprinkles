<?php

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
	 *
	 *	@var array
	 */

	protected static $_formats = array( );



	/**
	 *
	 */

	public static function configure( $generator, array $formats = array( )) {

		list( $plugin, $class ) = pluginSplit( $generator, true );
		$class .= 'Generator';

		App::uses( $class, $plugin . 'Lib/Thumbnail/Generator' );

		if ( !class_exists( $class )) {
			throw new CakeException( "The `$class` thumbnail generator does not exist." );
		}

		self::$_Generator = new $class( );
		$formats = array_merge(
			array(
				self::all => array(
					'path' => IMAGES
				)
			),
			$formats
		);

		$defaults = $formats[ self::all ];
		unset( $formats[ self::all ]);

		foreach ( $formats as $format => $settings ) {
			self::$_formats[ $format ] = array_merge( $defaults, $settings );
		}
	}



	/**
	 *
	 */

	public static function generate( $format, $id, $source ) {

		if ( self::$_Generator === null ) {
			throw new CakeException( 'No generator configured.' );
		}

		if ( !isset( self::$_formats[ $format ])) {
			throw new CakeException( "No configuration for the `$format` format." );
		}

		return self::$_Generator->generate( self::$_formats[ $format ]);
	}
}
