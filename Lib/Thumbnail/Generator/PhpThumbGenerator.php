<?php

App::uses( 'ThumbnailGenerator', 'Sprinkles.Lib/Thumbnail' );

App::import(
	'Vendor',
	'Sprinkles.PHPThumb',
	array(
		'file' => 'PHPThumb' . DS . 'src' . DS . 'ThumbLib.inc.php'
	)
);



/**
 *
 *
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@package Sprinkles.Model.Behavior
 *	@license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class PhpThumbGenerator implements ThumbnailGenerator {

	/**
	 *
	 */

	public function generate( array $options, $source, $destination ) {

		$options += array(
			'settings' => array( ),
			'operations' => array( ),
			'format' => 'png'
		);

		$PhpThumb = PhpThumbFactory::create( $source, $options['settings']);

		foreach ( $options['operations'] as $method => $params ) {
			call_user_method_array( $method, $PhpThumb, $params );
		}

		return $PhpThumb->save( $destination, $options['format']);
	}
}
