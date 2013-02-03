<?php

/**
 *
 *
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@package Sprinkles.Model.Behavior
 *	@license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

interface ThumbnailGenerator {

	/**
	 *
	 */

	public function generate( array $options, $source, $destination );

}
