<?php

/**
 *
 *
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@package Sprinkles.Model
 *	@license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class Index extends AppModel {

	/**
	 *
	 */

	public $belongsTo = array(
		'Token' => array(
			'className' => 'Sprinkles.Token'
		)
	);

}