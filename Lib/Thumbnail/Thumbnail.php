<?php

/**
 *
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

		App::uses( $class, $plugin . '.Lib/Thumbnail/Generator' );

		if ( !class_exists( $class )) {
			throw new CakeException( "The $class thumbnail generator does not exist." );
		}

		$this->_Generator = new $class( );
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
			$this->_formats[ $format ] = array_merge( $defaults, $settings );
		}
	}



	/**
	 *
	 */

	public function generate( $format ) {

		if ( $this->_Generator === null ) {
			throw new CakeException( 'No generator configured.' );
		}

		if ( !isset( $this->_formats[ $format ])) {
			throw new CakeException( "No configuration for the `$format` format." );
		}

		$this->_Generator->generate( $this->_formats[ $format ]);
	}
}
