<?php

App::uses( 'Sprinkles', 'Sprinkles.Lib' );



/**
 *
 *
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@package Sprinkles.Lib
 *	@license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class Thumbnail {

	/**
	 *
	 */

	protected $_defaults = [
		'path' => 'thumbs',
		'levels' => 3,
		'extension' => 'jpg'
	];



	/**
	 *
	 */

	protected $_configs = [ ];



	/**
	 *
	 */
	protected $_components = [ ];



	/**
	 *
	 */

	public function __construct( array $configs = [ ]) {

		$this->_configs = array_map( function( $config ) {
			$config += $this->_defaults;
			$config['path'] = trim( $config['path'], '/' . DS );

			return $config;
		}, $configs );
	}



	/**
	 *
	 */

	public function configs( ) {

		return array_keys( $this->_configs );
	}



	/**
	 *
	 */

	public function components( $key, $configName ) {

		$hash = md5( $key );

		if ( empty( $this->_components[ $configName ][ $hash ])) {
			if ( empty( $this->_configs[ $configName ])) {
				throw new Exception( "The `$configName` config is not defined." );
			}

			$config = $this->_configs[ $configName ];
			$levels = Sprinkles::bound( 2, $config['levels'], strlen( $hash ) - 1 );
			$start = substr( $hash, 0, $levels );

			$this->_components[ $configName ][ $hash ] = [
				'dir' => IMAGES . $config['path'],
				'url' => '/' . Configure::read( 'App.imageBaseUrl' ) . $config['path'],
				'path' => implode( DS, str_split( $start )),
				'file' => substr( $hash, $levels ) . '.' . $config['extension']
			];
		}

		return $this->_components[ $configName ][ $hash ];
	}



	/**
	 *
	 */

	public function path( $key, $config, array $options = [ ]) {

		$components = $this->components( $key, $config );
		$options += [
			'mkdir' => 0755
		];

		$path = $components['dir'] . DS . $components['path'];

		if ( $options['mkdir']) {
			mkdir( $path, $options['mkdir'], true );
		}

		return $path . DS . $components['file'];
	}



	/**
	 *
	 */

	public function url( $key, $config, array $options = [ ]) {

		$components = $this->components( $key, $config );
		$options += [ ];

		return $components['url'] . '/' . $components['path'] . '/' . $components['file'];
	}
}
