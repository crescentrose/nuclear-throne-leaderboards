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

    <title>Nuclear Throne Daily Leaderboards {% block title %}{% endblock %}</title>

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <link href="/css/datepicker3.css" rel="stylesheet">
    
    <!-- Custom page CSS -->
    <link href="/css/custom.css?v=20150319" rel="stylesheet">


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

  <body class="background-home background{{ weekday }}">

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
              <li><a href="/all-time">All-time ranks</a></li>
              <li><a href="/archive">Archive</a></li>          
              <li><a href="/about">About</a></li>
            </ul>
            {% if session.steamid == "" %}
              <form action="/?login" method="post" class="navbar-form navbar-right">
                <div class="form-group">
                  <input type="image" src="http://cdn.steamcommunity.com/public/images/signinthroughsteam/sits_small.png">
                </div>
              </form>
            {% else %}
            <ul class="nav navbar-nav navbar-right">
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Hi, {{ session.steamname }}! <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="/player/{{ session.steamid }}/">My profile</a></li>
                  <li><a href="?logout">Logout</a></li>
                </ul>
              </li>
            </ul>
            {% endif %}
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>
      {% block content %}{% endblock %}
     <div class="row col-md-12 footer center-block">
       Nuclear Throne is property of Vlambeer. Steam is a trademark of Valve Incorporated. Neither Vlambeer nor Valve are affiliated with this site. This site was coded by <a href="http://steamcommunity.com/id/i542">[WA]Darwin</a>. Art was made by Justin Chan for Nuclear Throne update notes.
     </div>
    </div> <!-- /container -->
  </body>
</html>
