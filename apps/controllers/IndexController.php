<?php

class IndexController extends \Phalcon\Mvc\Controller {

	public function indexAction(){

        echo "<meta charset='utf-8'>";
        $config = $this->config;

        $result = Feeds::getForTweet($config->bitly->bitlyKey,$config->bitly->oauthLogin,$config->bitly->oauthToken);

        $twitter = new Twitter(
            $config->twitter->consumerKey,
            $config->twitter->consumerSecret,
            $config->twitter->accessToken,
            $config->twitter->accessTokenSecret
        );

        $i=0;
        foreach($result as $twit) {
            echo $twit['tweet']."<br/>";
            $twitter->send($twit['tweet']);

            $posted = new Posted();
            $posted->feed_id = $twit['feed_id'];

            if(isset($twit['item_link']))
                $posted->item_link = $twit['item_link'];

            $posted->save();

            $i++;
        }
        echo "Total tweets sended: ".$i;
	}
	
}