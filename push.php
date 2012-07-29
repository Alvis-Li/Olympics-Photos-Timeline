<?PHP
require('Pusher.php');

             $app_id = '24859';
             $pusher_key = '5929cbede666ad5bee2a';
             $pusher_secret = 'fca1621c140cac4b7031';

//var_dump($myOBJ);
//print_r($myOBJ);
for($timer = 0;$timer < 6;$timer++)
{
    $content = file_get_contents("http://api.datasift.com/stream?hash=1342868eaed8af9c68c48ac20a2ae928&count=5&api_key=2a1cb44b325c829d10c20e0e90d0d891&username=Oras");
    $myOBJ= json_decode($content);
    
    for($i=0;$i<20;$i++)
    {
        //print_r($myOBJ->stream[$i]->links);
        if($myOBJ->stream[$i]->deleted == true)
        {    //echo "DELETED STREAM<br />";}
        }
        else{
            //GETTING INSTAGRAM LINK
            if($myOBJ->stream[$i]->links->url[0] != null)
            {
                $savedArrayFile = file_get_contents('saved.txt');
                $savedArray = explode(",",$savedArrayFile);
                
                $newsaveLink.=$myOBJ->stream[$i]->links->url[0].',';
                
                if( in_array($myOBJ->stream[$i]->links->url[0], $savedArray ) == false)
                {
                    //$postDate = $myOBJ->stream[$i]->interaction->created_at;
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
                    //$pushMessage.= '<a href="#" class="large polaroid img'.$i.'"><img src="'.$instagramOBJ->url.'" alt="">{CONTENT}</a>';
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
//$social = "<a href='http://www.facebook.com/share.php?u=".strip_tags($pushMessage)."' target=_blank><img src='facebook.png' width='96' height='96' /></a> &nbsp;&nbsp;&nbsp;<a href='https://twitter.com/intent/tweet?text=".$discountRate."% on this product ".window.href." if you ordered withing 6 //hours' target=_blank><img src='twitter.png';// width='96' height='96' /></a>";
//$pushMessage.= "Share the coupon on <br />".$social;
?>