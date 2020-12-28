<?php
  /*$settings = array(
      'oauth_access_token'        => get_field('oauth_access_token', 'option'),
      'oauth_access_token_secret' => get_field('oauth_access_token_secret', 'option'),
      'consumer_key'              => get_field('consumer_key', 'option'),
      'consumer_secret'           => get_field('consumer_secret', 'option')
  );*/

if(!function_exists('pr')) {
  function pr($arr){
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
  }
}

function proper_parse_str($str) {
  # result array
  $arr = array();

  # split on outer delimiter
  $pairs = explode('&', $str);

  # loop through each pair
  foreach ($pairs as $i) {
    # split into name and value
    list($name,$value) = explode('=', $i, 2);
   
    # if name already exists
    if( isset($arr[$name]) ) {
      # stick multiple values into an array
      if( is_array($arr[$name]) ) {
        $arr[$name][] = $value;
      }
      else {
        $arr[$name] = array($arr[$name], $value);
      }
    }
    # otherwise, simply stick it in a scalar
    else {
      $arr[$name] = $value;
    }
  }

  # return result array
  return $arr;
}

function parseRSS($feedUrl) {   
  
  try
  {
    $context = stream_context_create(array(
      'http'=>array(
        'user_agent' => 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.57 Safari/536.11'
       )
    ));
    
    // Get data as a string
    //$feedUrl  = "http://www.businessmirror.com.ph/index.php/en/component/obrss/rss";
    $xml2   = file_get_contents($feedUrl, FALSE, $context);   
    //pr($test);
    //die();
    //$xml2 = utf8_encode(file_get_contents($feedUrl, FALSE, $context));
    //$xml2 = utf8_decode($xml2);
    $rss = simplexml_load_string($xml2);

    if(!empty($rss))
    {
      if (strpos($feedUrl, 'http://www.facebook.com') === 0) {
            foreach ($rss->channel->item as $item) {
                $fixedDesc = $item->description;
          
                $fixedDesc = str_replace('href="', 'href="http://www.facebook.com', $fixedDesc); 
                $fixedDesc = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $fixedDesc);
          
          #$fixedDesc = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', utf8_encode($fixedDesc));
          #$fixedDesc = iconv('UTF-8', 'ISO-8859-1//IGNORE', $fixedDesc);
                $item->description = $fixedDesc;    

            }
      }
  
    }
  } catch(Exception $e) {
    echo $e;
  }
  
  
    return $rss;
} 

function createLinks($text) { 
    return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $text);
}  

function getFriendlyDate($dateDiff) {
    $friendlyDate = '';
    
    if ($dateDiff < 21600) {
        $dateDiff = floor($dateDiff / 360);
        $friendlyDate = ( ($dateDiff) ? $dateDiff. ' min' : 'less a min') . ($dateDiff > 1 ? 's' : '') . '';
    }
    else if ($dateDiff < 518400) {
        $dateDiff = floor($dateDiff / 21600);
        $friendlyDate = $dateDiff . ' hr' . ($dateDiff > 1 ? 's' : '') . '';
    }
    else if ($dateDiff < 3628800) {
        $dateDiff = floor($dateDiff / 518400);
        $friendlyDate = $dateDiff . ' day' . ($dateDiff > 1 ? 's' : '') . '';
    }
    else {
        $dateDiff = floor($dateDiff / 3628800);
        $friendlyDate = $dateDiff . ' week' . ($dateDiff > 1 ? 's' : '') . '';
    }
    
    return $friendlyDate;
}

function getTimeSince($eventTime) {
    
    date_default_timezone_set('UTC');

    $totaldelay = date('U') - strtotime($eventTime);
    if($totaldelay <= 0)
    {
        return '';
    }
    else
    {
        $first = '';
        $marker = 0;
        if($years=floor($totaldelay/31536000))
        {
            $totaldelay = $totaldelay % 31536000;
            $plural = '';
            if ($years > 1) $plural='s';
            $interval = $years." year".$plural;
            $timesince = $timesince.$first.$interval;
            if ($marker) return $timesince;
            $marker = 1;
            $first = ", ";
        }
        if($months=floor($totaldelay/2628000))
        {
            $totaldelay = $totaldelay % 2628000;
            $plural = '';
            if ($months > 1) $plural='s';
            $interval = $months." month".$plural;
            $timesince = $timesince.$first.$interval;
            if ($marker) return $timesince;
            $marker = 1;
            $first = ", ";
        }
        if($days=floor($totaldelay/86400))
        {
            $totaldelay = $totaldelay % 86400;
            $plural = '';
            if ($days > 1) $plural='s';
            $interval = $days." day".$plural;
            $timesince = $timesince.$first.$interval;
            if ($marker) return $timesince;
            $marker = 1;
            $first = ", ";
        }
        if ($marker) return $timesince;
        if($hours=floor($totaldelay/3600))
        {
            $totaldelay = $totaldelay % 3600;
            $plural = '';
            if ($hours > 1) $plural='s';
            $interval = $hours." hr".$plural;
            $timesince = $timesince.$first.$interval;
            if ($marker) return $timesince;
            $marker = 1;
            $first = ", ";

        }
        if($minutes=floor($totaldelay/60))
        {
            $totaldelay = $totaldelay % 60;
            $plural = '';
            if ($minutes > 1) $plural='s';
            $interval = $minutes." min".$plural;
            $timesince = $timesince.$first.$interval;
            if ($marker) return $timesince;
            $first = ", ";
        }
        if($seconds=floor($totaldelay/1))
        {
            $totaldelay = $totaldelay % 1;
            $plural = '';
            if ($seconds > 1) $plural='s';
            $interval = $seconds." sec".$plural;
            $timesince = $timesince.$first.$interval;
        }        
        return $timesince;

    }
}

function array_md_ksort(&$array, $subkey , $sort_ascending = false) {
    if (count($array)) {
         $temp_array[key($array)] = array_shift($array);
    }
 
    foreach ($array as $key => $val) {
         $offset = 0;
         $found = false;
         
         foreach($temp_array as $tmp_key => $tmp_val) {
             if (!$found and strtolower($val[$subkey]) > strtolower($tmp_val[$subkey])) {
                 $temp_array = array_merge((array) array_slice($temp_array,0,$offset), array($key => $val), array_slice($temp_array,$offset));
                 $found = true;
             }
             $offset++;
         }
         
         if (!$found) {
             $temp_array = array_merge($temp_array, array($key => $val));
         }
     }
 
    if ($sort_ascending) {
        $array = array_reverse($temp_array);
    }
    else { 
        $array = $temp_array;
    }
}

function truncateHtml($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true)
{

 if ($considerHtml) {
  // if the plain text is shorter than the maximum length, return the whole text
  if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
   return $text;
  }
  // splits all html-tags to scanable lines
  preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
  $total_length = strlen($ending);
  $open_tags = array();
  $truncate = '';
  foreach ($lines as $line_matchings) {
   // if there is any html-tag in this line, handle it and add it (uncounted) to the output
   if (!empty($line_matchings[1])) {
    // if it's an "empty element" with or without xhtml-conform closing slash
    if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
     // do nothing
    // if tag is a closing tag
    } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
     // delete tag from $open_tags list
     $pos = array_search($tag_matchings[1], $open_tags);
     if ($pos !== false) {
     unset($open_tags[$pos]);
     }
    // if tag is an opening tag
    } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
     // add tag to the beginning of $open_tags list
     array_unshift($open_tags, strtolower($tag_matchings[1]));
    }
    // add html-tag to $truncate'd text
    $truncate .= $line_matchings[1];
   }
   // calculate the length of the plain text part of the line; handle entities as one character
   $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
   if ($total_length+$content_length> $length) {
    // the number of characters which are left
    $left = $length - $total_length;
    $entities_length = 0;
    // search for html entities
    if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
     // calculate the real length of all entities in the legal range
     foreach ($entities[0] as $entity) {
      if ($entity[1]+1-$entities_length <= $left) {
       $left--;
       $entities_length += strlen($entity[0]);
      } else {
       // no more characters left
       break;
      }
     }
    }
    $truncate .= substr($line_matchings[2], 0, $left+$entities_length);
    // maximum lenght is reached, so get off the loop
    break;
   } else {
    $truncate .= $line_matchings[2];
    $total_length += $content_length;
   }
   // if the maximum length is reached, get off the loop
   if($total_length>= $length) {
    break;
   }
  }
 } else {
  if (strlen($text) <= $length) {
   return $text;
  } else {
   $truncate = substr($text, 0, $length - strlen($ending));
  }
 }
 // if the words shouldn't be cut in the middle...
 if (!$exact) {
  // ...search the last occurance of a space...
  $spacepos = strrpos($truncate, ' ');
  if (isset($spacepos)) {
   // ...and cut the text in this position
   $truncate = substr($truncate, 0, $spacepos);
  }
 }
 // add the defined ending to the text
 $truncate .= $ending;
 if($considerHtml) {
  // close all unclosed html-tags
  foreach ($open_tags as $tag) {
   $truncate .= '</' . $tag . '>';
  }
 }
 return $truncate;
 
}

function getFacebookFeeds($page_id, $count=0) {
    
    /*$feedUrl = 'http://www.facebook.com/feeds/page.php?format=rss20&id='.$page_id;    
    $entries = parseRSS($feedUrl);
    $items = $entries->channel->item;*/

    /**
     * This is the link to my page graph
     * I've included it here so i can copy an paste for quick reference
     *
     * Copying and pasting this into the browser url bar gives you a full graph of the feed
     * which is very handy for browsing and seeing what exists in the array
     *
     * Change the values to suit your own needs, and when your script is final, remove this
     * comment block
     *
     * Typing this into the url will get you the super array (graph) to analyze
     * https://graph.facebook.com/YOUR_PAGE_ID/feed?access_token=APP_ACCESS_TOKEN
     */

    // include the facebook sdk
    require_once('facebook-php-sdk-master/src/facebook.php');
    // connect to app
    $config = array();
    $config['appId']      = facebook_app_id;
    $config['secret']     = facebook_app_secret;
    $config['fileUpload'] = false; // optional

    // instantiate
    $facebook = new Facebook($config);

    // now we can access various parts of the graph, starting with the feed
    $pagefeed = $facebook->api("/" . $page_id . "/feed?count=3");    
    
    $items = $pagefeed['data'];

	$page_profile 	= $facebook->api('/' . $page_id."?fields=id,name,picture,username,link");        

    $i      = 0;    
    $arr_item = array();
    $response = "";
    $c=0;
    foreach($items as $item_ar) {
      $item = json_decode(json_encode($item_ar), FALSE);
      if($i==5) break;
      
      $description     = $item->description;
      $type =   $item->type;
        if($type == "photo"){
          $description = $item->message;
        }
      

      $description      = str_replace('href="', 'target="_blank" href="',$description);
      $newline        = array("<br>", "<br/>", "<br />");    
      $description      = ($item->name!='') ? createLinks($item->name) : str_replace($newline, "", preg_replace('%<(.*?)[^>]*>\\s*</\\1>%', '', preg_replace("/<img[^>]+\>/i", "", $description)));
      $title          = $item->author;
      
      #$dateDiff        = time() - strtotime($item->pubDate);                                                   
      $friendlyDate       = getTimeSince($item->created_time);    

      /*$readmore = '<a class = "readmore att_right" href = "javascript:void(0);" onClick = "window.open(\''.$item->link.'\', \'targetWindow\', \'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=800,height=700\')">Read more.</a>';
      $profile = "http://graph.facebook.com/$page_id/picture";
      $response .= '
      <div class="feed-img row socialfeed">
        <div class="three columns">
          <img src="'.get_bloginfo('template_directory').'/library/images/socialfeedicon.jpg">      
        </div>
        <div class="nine columns">
          <h5>'.$title.'</h5>
          <span class="author">@CREATEfnd</span><span class="date">'.$friendlyDate.'</span>
          <div class="clear"></div>
          <p>'.$description.'</p>
          '.$readmore.'
        </div>
      </div>';*/
        #echo $item->created_time;
      $pic_url = $item->picture;
      if( $pic_url ) {
        #pr($item);
        $pic = $item->picture;
        if($pic) {
          $url = parse_url($pic);
          if($url) {
            $url = parse_url($pic);
            #$args = parse_str($url['query']);
            $query = proper_parse_str($url['query']);
           /* $query['d']   = $query['d'];
            $query['w']   = 640;
            $query['h']   = 640;
            $query['url'] = urldecode($query['url']);
            $query['cfs'] = $query['cfs'];*/
            $pic_url = urldecode($query['url']);
          }
        }

      }    
        if($e['title'])  {
          $created = strtotime($item->created_time);
          $e['social']        = 'fb';
          $e['user']     		= array(
          							'name' 		=> $page_profile['name'],
          							'username' 	=> $page_profile['username'],
          							'image' 	=> $page_profile['picture']['data']['url'],
          							'url' 		=> $page_profile['link']
          							);        
          $e['title']         = $item->message;
          $e['description']   = $item->message;
          $e['type']          = $item->type;
          $e['images']        = $pic_url;
          $e['link']          = $item->link;
          $e['created_time']  = $created;
          $result[$created] = $e;      
        }
      #$i++;
      #break;
		$c++;
		if($c==$count) {
			break;
		}      
    }
    
    return $result;       
}

function getTweets($twitter_id, $count=0) {
  #ob_start();
  $tweets   = array();
  $result   = array();
  $response = '';
  // Setting our Authentication Variables that we got after creating an application
  $settings = array(
      'oauth_access_token'        => oauth_access_token,
      'oauth_access_token_secret' => oauth_access_token_secret,
      'consumer_key'              => consumer_key,
      'consumer_secret'           => consumer_secret
  );
  // We are using GET Method to Fetch the latest tweets.
  $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';

  // Set your screen_name to your twitter screen name. Also set the count to the number of tweets you want to be fetched. Here we are fetching 5 latest tweets.
  $getfield = "?screen_name=$twitter_id&count=$count";
  $requestMethod = 'GET';

  include('twitter-feeds.php');
  // Making an object to access our library class
  $twitter = new TwitterAPIExchange($settings);
  $store = $twitter->setGetfield($getfield)
               ->buildOauth($url, $requestMethod)
               ->performRequest();
  // Since the returned result is in json format, we need to decode it             

  if($store) {
    $result_data = json_decode($store);

  // After decoding, we have an standard object array, so we can print each tweet into a list item.
    $tweets = objectToArray($result_data);      
    foreach($tweets as $tweet) {
      
      $title      = $tweet['user']['name'];
      $author     = $tweet['user']['screen_name'];
      
      $dateDiff     = time() - strtotime($tweet['created_at']);                                                   
      #$friendlyDate  = getFriendlyDate($dateDiff);    
      $friendlyDate       = getTimeSince($tweet['created_at']);         
      $description  = createLinks($tweet['text']);
      
      $item_link    = 'https://twitter.com/'.$tweet['user']['screen_name'].'/status/'.$tweet['id'];

      $readmore = '<a class = "readmore att_right" href = "javascript:void(0);" onClick = "window.open(\''.$item_link.'\', \'targetWindow\', \'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=800,height=700\')">Read more.</a>';
      /*$response .= '
      <div class="feed-img row socialfeed">
        <div class="three columns">
          <img src="'.$tweet['user']['profile_image_url'].'">
        </div>
        <div class="nine columns">
          <h5>'.$title.'</h5>
          <span class="author">@'.$author.'</span><span class="date">'.$friendlyDate.'</span>
          <div class="clear"></div>
          <p>'.truncateHtml($description, 70).'</p>
          '.$readmore.'
        </div>
      </div>'; */     
        $created = strtotime($tweet['created_at']);
        $e['social']        = 'twitter';
        $e['user']     		= array(
        							'name' 		=> $tweet['user']['name'],
        							'username' 	=> $tweet['user']['screen_name'],
        							'image' 	=> $tweet['user']['profile_image_url'],
        							'url' 		=> $tweet['user']['url']
        							);
        $e['title']         = $description;
        $e['description']   = $description;
        $e['type']          = $entry['source'];
        $e['images']        = '';
        $e['link']          = $item_link;
        $e['created_time']  = $created;

        $result[$created] = $e;
    }       
  }

  #ob_flush();
  return $result;
}

function getInstagram($client_id) {

  $access_token = instagram_access_token;
  $url = "https://api.instagram.com/v1/users/$client_id/media/recent?access_token=$access_token";
  echo $url;
  $ch = curl_init($url); 

  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

  $json = curl_exec($ch); 
  curl_close($ch);  

  $result = array();
  if($json) {
    $data = json_decode($json);
    if($data->data) {
      foreach($data->data as $entry) {

        $e['social']        = 'instagram';
        $e['title']         = $entry->caption->text;
        $e['description']   = $entry->caption->text;
        $e['type']          = $entry->type;
        $e['images']        = $entry->images->standard_resolution->url;
        $e['link']          = $entry->link;
        $e['created_time']  = $entry->created_time;                

        $result[$entry->created_time] = $e;
      }            
    }
  }

  return $result;
}

function getInstagramFeeds($username, $count=0){
	$result = array();
	
	$instaResult= file_get_contents('https://www.instagram.com/'.$username.'/media/');
	$feeds = json_decode($instaResult);
	if(isset($feeds->status) && $feeds->status == 'ok') {
		#return $feeds->items;
		$c=0;
		foreach($feeds->items as $entry) {

			$e = array();
			$e['social']        = 'instagram';
			$e['title']         = $entry->caption->text;
			$e['description']   = $entry->caption->text;
			$e['type']          = $entry->type;
			$e['image']        = $entry->images->standard_resolution->url;
			$e['image_thumbnail']  = $entry->images->standard_resolution->url;
			$e['link']          = $entry->link;
			$e['created_time']  = $entry->created_time;                

			$result[$entry->created_time] = $e;
			$c++;
			if($c==$count) {
				break;
			}
		}    		
	}
	return $result;
}