<?php

class Feeds extends \Phalcon\Mvc\Model
{
    /*
     * Забирает фид и подготавливает новые записи для постинга
     */
    public static function getForTweet() {
        $l = 120;
        $arr_twit = array();

        $feed = self::findFirst();

        $rss = Feed::loadRss($feed->feed_url);

        $bitly = new Bitly();

        foreach ($rss->item as $item) {

            $post = Posted::query()
                ->where("feed_id = :feed_id:")
                ->andWhere("item_link = :item_link:")
                ->bind(array("feed_id" => $feed->id,"item_link" => md5($item->link)))
                ->execute();

            if($post->count()==0) {
                $twit = strip_tags(current($item->{'content:encoded'}));
                $link = $bitly->bitly_v3_shorten($item->link, 'j.mp','LOGIN','SECRET');
                $link = $link['url'];

                $length = mb_strlen($twit,'utf-8');
                if($length>$l) {
                    $i = 0;
                    while($i<$length) {
                        $t = mb_substr($twit,$i,$l,'utf-8');
                        if(empty($t))
                            break;
                        $space_pos = mb_strrpos($t,' ',0,'utf-8');
                        if($space_pos>0 && mb_strlen($t,'utf-8')==$l) {
                            if($i==0)
                                $arr_twit[] = mb_substr($twit,$i,$i+$space_pos,'utf-8').'...';
                            else
                                $arr_twit[] = '...'.mb_substr($twit,$i,$i+$space_pos,'utf-8');
                            $i=$i+$space_pos;
                        }
                        else {
                            if($i==0)
                                $arr_twit[] = $t.'...';
                            else
                                $arr_twit[] = '...'.$t;
                            $i=$i+$l;
                        }
                    }
                    end($arr_twit);
                    $key = key($arr_twit);
                    $arr_twit[$key] = $arr_twit[$key].' '.$link;
                }
                else $arr_twit[] = $twit.' '.$link;

                $posted = new Posted();
                $posted->feed_id = $feed->id;
                $posted->item_link = md5($item->link);
                $posted->save();
            }
        }
        return $arr_twit;
    }
}