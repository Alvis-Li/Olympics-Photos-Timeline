<?PHP
require('Pusher.php');

             $app_id = ''; // Your Pusher App ID
             $pusher_key = ''; // Your Pusher Key
             $pusher_secret = ''; // Your Pusher Secret

// Due to the fact that the cron job runs based on minutes at least and can't be configured to run on seconds
// I used a cron job to update every minute, but made the script runs 5 times for 10 seconds which will end before the next cron runs

for($timer = 0;$timer < 6;$timer++)
{
    $content = file_get_contents(""); // Your Datasift API Stream direct link
    $myOBJ= json_decode($content);
    
    for($i=0;$i<20;$i++)
    {
        if($myOBJ->stream[$i]->deleted == true)
        {    
            //Some tweets might be deleted so you need to ignore them
        }
        else{
            //GETTING INSTAGRAM LINK
            if($myOBJ->stream[$i]->links->url[0] != null)
            {
                // Saving the photos links to check in the next loop if they're already pushed to the website
                // to avoid duplications. This can be done using DB as well.
                $savedArrayFile = file_get_contents('saved.txt');
                $savedArray = explode(",",$savedArrayFile);
                
                $newsaveLink.=$myOBJ->stream[$i]->links->url[0].',';
                
                if( in_array($myOBJ->stream[$i]->links->url[0], $savedArray ) == false)
                {
                    if( date('s', strtotime($myOBJ->stream[$i]->interaction->created_at)) < 60 )
                    {
                        $postDate = date('s',strtotime($myOBJ->stream[$i]->interaction->created_at))." seconds ago";
                    }
                    elseif( date('i', strtotime($myOBJ->stream[$i]->interaction->created_at)) < 60  )
                    {
                        $postDate = date('i',strtotime($myOBJ->stream[$i]->interaction->created_at))." minutes ago";
                    }
                    elseif( date('H', strtotime($myOBJ->stream[$i]->interaction->created_at)) < 24 )
                    {
                        $postDate = date('H', strtotime($myOBJ->stream[$i]->interaction->created_at))." hours ago";
                    }
                    else{
                         $postDate = "OLD";   
                    }
                    
                    $url = "http://api.instagram.com/oembed?url=".$myOBJ->stream[$i]->links->url[0];
                    $instagramJSON = file_get_contents($url);
                    $instagramOBJ = json_decode($instagramJSON);
                    $pushMessage.= '<div class="TimelineTwoColumn">
                        <div class="TimelineUnitActor">
                            <img class="uiProfilePhoto uiProfilePhotoMedium img" src="'.$myOBJ->stream[$i]->interaction->author->avatar.'" alt="">
                            <a href="http://www.twitter.com/'.$myOBJ->stream[$i]->interaction->author->username.'" target=_blank>'.$myOBJ->stream[$i]->interaction->author->name.'</a>
                            <span>'.$postDate.'</span>
                            <div class="TimelineSelectorButton">
                                <div><div>
                                    <a href="http://www.facebook.com/sharer.php?u='.$myOBJ->stream[$i]->links->url[0].'" target=_blank><img src="fb.png" class="social1" /></a>
                                    <a href="http://twitter.com/share?url='.$myOBJ->stream[$i]->links->url[0].'" target=_blank><img src="twitter.png" /></a>
                                </div></div>
                            </div>
                        </div>
                
                        <a href="'.$myOBJ->stream[$i]->links->url[0].'" target=_blank><img src="'.$instagramOBJ->url.'"  width="484" height="300" /></a>
                        <li class="TimelineColumn">'.$myOBJ->stream[$i]->interaction->content.'</li>    
                    </div>';
                
                }
            }
        }
    }
    file_put_contents('saved.txt',$newsaveLink);
    
    if($pushMessage != null)
    {
        $pusher = new Pusher($pusher_key, $pusher_secret, $app_id);
        $pusher->trigger('test_channel', 'my_event', $pushMessage);
    }
    sleep(10);
    $pushMessage = "";
    $newsaveLink = "";
}
?>