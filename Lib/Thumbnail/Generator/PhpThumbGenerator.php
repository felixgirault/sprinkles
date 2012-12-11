<?php

App::uses( 'ThumbnailGenerator', 'Sprinkles.Lib/Thumbnail' );



/**
 *
 *
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@package Sprinkles.Model.Behavior
 *	@license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class PhpThumbGenerator extends ThumbnailGenerator {

	/**
	 *
	 */

	public function generate( array $options, $source, $destination ) {

		$options += array(
			'settings' => array( ),
			'operations' => array( ),
			'format' => 'png'
		);

		try {
			$PhpThumb = PhpThumbFactory::create( $source, $options['settings']);
		} catch ( Exception $e ) {
			throw new CakeException( $e->getMessage( ));
		}

		foreach ( $options['operations'] as $method => $params ) {
			call_user_method_array( $method, $PhpThumb, $params );
		}

		return $PhpThumb->save( $destination, $options['format']);
	}
}
