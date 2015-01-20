<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="View current statistics for daily runs in the game Nuclear Throne, as well as your personal history and global stats.">
    <meta name="author" content="">
    <meta name="keywords" content="nuclear throne,daily run,statistics">
    <link rel="icon" href="favicon.ico">

    <title>Nuclear Throne Daily Run Statistics</title>

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <link href="/css/datepicker3.css" rel="stylesheet">
    
    <!-- Custom page CSS -->
    <link href="/css/custom.css" rel="stylesheet">


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Analytics-->
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-58437854-1', 'auto');
      ga('send', 'pageview');
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
  </head>

  <body>

    <div class="container">
      <!-- Static navbar -->
      <nav class="navbar navbar-default navbar-inverse">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">Nuclear Throne Stats</a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li><a href="/">Today's Daily</a></li>
              <li><a href="/all-time">All-time ranks <span class="label label-default">New</span></a></li>
              <li><a href="/archive">Archive</a></li>          
              <li><a href="/about">About</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li><a href="http://store.steampowered.com/app/242680/">Get Nuclear Throne on Steam!</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>
      {% block content %}{% endblock %}
     <div class="row col-md-12 footer center-block">
       Nuclear Throne is property of Vlambeer. Steam is a trademark of Valve Incorporated. Neither Vlambeer nor Valve are affiliated with this site. This site was coded by <a href="http://steamcommunity.com/id/i542">[SgC]Ruby</a>. This site is open-source - <a href="https://github.com/notyourshield/nuclear-throne-leaderboards">check it out on GitHub!</a> I've found the background with one of the patch notes - sorry for stealing, contact me if you're the original artist so that I can credit you or remove your work if you so desire!
     </div>
    </div> <!-- /container -->
  </body>
</html>
