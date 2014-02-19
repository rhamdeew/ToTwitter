<?php

class Feeds extends \Phalcon\Mvc\Model
{

    /*
     * Забирает фид и подготавливает новые записи для постинга
     */
    public static function getForTweet($bitlyKey,$bitlyOauthLogin,$bitlyOauthToken) {
        $l = 110;
        $arr_twit = array();

        $feed = self::findFirst();

        $rss = Feed::loadRss($feed->feed_url);

        $bitly = new Bitly();
        $bitly->setKey($bitlyKey);

        foreach ($rss->item as $item) {

            $post = Posted::query()
                ->where("feed_id = :feed_id:")
                ->andWhere("item_link = :item_link:")
                ->bind(array("feed_id" => $feed->id,"item_link" => md5($item->link)))
                ->execute();

            if($post->count()==0) {
                $twit = strip_tags(current($item->{'content:encoded'}));
                $twit = preg_replace("/&#?[a-z0-9]+;/i","",$twit);

                $link = $bitly->bitly_v3_shorten($item->link, 'j.mp',$bitlyOauthLogin,$bitlyOauthToken);
                if(is_array($link) && isset($link['url']))
                    $link = $link['url'];
                else $link = '';

                $length = mb_strlen($twit,'utf-8');
                if($length>0) {
                    if($length>$l) {
                        $i = 0;
                        while($i<$length) {
                            $t = mb_substr($twit,$i,$l,'utf-8');

                            if(empty($t))
                                break;

                            $space_pos = mb_strrpos($t,' ',0,'utf-8');
                            if($space_pos>0 && mb_strlen($t,'utf-8')==$l) {
                                if($i==0) {
                                    $arr_twit[]['tweet'] = mb_substr($twit,$i,$space_pos,'utf-8').'...';
                                    $arr_twit = Feeds::tweetOptions($arr_twit,$feed);
                                }
                                else {
                                    $arr_twit[]['tweet'] = '...'.mb_substr($twit,$i,$space_pos,'utf-8');
                                    $arr_twit = Feeds::tweetOptions($arr_twit,$feed);
                                }
                                $i=$i+$space_pos;
                            }
                            else {
                                if($i==0) {
                                    $arr_twit[]['tweet'] = $t.'...';
                                    $arr_twit = Feeds::tweetOptions($arr_twit,$feed);
                                }
                                else {
                                    $arr_twit[]['tweet'] = '..:'.$t;
                                    $arr_twit = Feeds::tweetOptions($arr_twit,$feed);
                                }
                                $i=$i+$l;
                            }
                        }
                        end($arr_twit);
                        $key = key($arr_twit);
                        $arr_twit[$key]['tweet'] = $arr_twit[$key]['tweet'].' '.$link;
                        if(is_object($feed))
                            $arr_twit = Feeds::tweetOptions($arr_twit,$feed,$item);
                    }
                    else {
                        $arr_twit[]['tweet'] = $twit.' '.$link;
                        if(is_object($feed))
                            $arr_twit = Feeds::tweetOptions($arr_twit,$feed,$item);
                    }
                }
            }
        }
        return $arr_twit;
    }

    public static function tweetOptions($arr_twit,$feed,$item=NULL) {
        end($arr_twit);
        $key = key($arr_twit);
        $arr_twit[$key]['feed_id'] = $feed->id;

        if($item!==NULL)
            $arr_twit[$key]['item_link'] = md5($item->link);

        return $arr_twit;
    }

}