<!DOCTYPE html>
<head>
  <title>Users Olympics Photos Timeline</title>
  <script src="http://js.pusher.com/1.11/pusher.min.js" type="text/javascript"></script>
  <script type="text/javascript">
    // Enable pusher logging - don't include this in production
    Pusher.log = function(message) {
      if (window.console && window.console.log) window.console.log(message);
    };
    // Flash fallback logging - don't include this in production
    WEB_SOCKET_DEBUG = true;
    var pusher = new Pusher('5929cbede666ad5bee2a');
    var channel = pusher.subscribe('test_channel');
    channel.bind('my_event', function(data) {
      //alert('hello' + data);
      currentData = document.getElementById('receiver').innerHTML;
      document.getElementById('receiver').innerHTML = data + currentData;
      
    });
  </script>
  <link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<header>
		
                <nav id="menu">
					<h1>Olympics Photos Timeline - Instagram photos shared on Twitter</i></h1>
					
					<ul class="mainMenu">
						<li>
							<a href="/">Home</a>
							<a href="#">About</a>
							<a href="http://www.twitter.com/orask">Follow me</a>
						</li>
					</ul>
				</nav>
        </header>
        
    
<section>
<center><img src="banner.png" width="1024" height="250"/></center>
            <div id="timeline_tab_content">
            <div class="TimelineSpine">
                
            </div>
            <div id="receiver"></div>
</section>

<footer>
</footer>

</body>
</html>