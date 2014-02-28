<?php

class ArticleFixture extends CakeTestFixture {

	public $fields = [
		'id' => [ 'type' => 'integer', 'key' => 'primary' ],
		'title' => [ 'type' => 'string', 'length' => 255, 'null' => false ],
		'slug' => [ 'type' => 'string', 'length' => 255, 'null' => false ],
		'text' => 'text',
		'created' => 'datetime'
	);

	public $records = [
		[ 'id' => 1, 'title' => 'First article', 'slug' => 'first-article', 'text' => 'First article text', 'created' => '2007-03-18 10:39:23' ],
		[ 'id' => 2, 'title' => 'Second article', 'slug' => 'second-article', 'text' => 'Second article text', 'created' => '2007-03-18 10:41:23' ],
		[ 'id' => 3, 'title' => 'Third article', 'slug' => 'third-article', 'text' => 'Third article text', 'created' => '2007-03-18 10:43:23' ]
	];
}
