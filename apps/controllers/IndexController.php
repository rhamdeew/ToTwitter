<?php

class IndexController extends \Phalcon\Mvc\Controller {

	public function indexAction(){

        echo "<meta charset='utf-8'>";
        Helper::Dump(Feeds::getForTweet());
	}
	
}