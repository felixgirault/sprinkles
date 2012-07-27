<?php

App::uses( 'Controller', 'Controller' );
App::uses( 'View', 'View' );
App::uses( 'ExtendedHtmlHelper', 'Sprinkles.View/Helper' );



/**
 *
 */

class ExtendedHtmlHelperTest extends CakeTestCase {

	/**
	 *
	 */

	public $ExtendedHtml = null;



	/**
	 *
	 */

	public function setUp( ) {
		
		parent::setUp( );

		$Controller = new Controller( );
		$View = new View( $Controller );
		$this->ExtendedHtml = new ExtendedHtmlHelper( $View );
	}



	/**
	 *
	 */

	public function testTitle( ) {

		$this->assertEquals( '<h1>Title</h1>', $this->ExtendedHtml->title( 1, 'Title' ));
		$this->assertEquals(
			'<h2 class="title">Title</h2>',
			$this->ExtendedHtml->title( 2, 'Title', array( 'class' => 'title' ))
		);
	}
}
