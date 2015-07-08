<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="View current statistics for daily runs in the game Nuclear Throne, as well as your personal history and global stats.">
    <meta name="author" content="">
    <meta name="keywords" content="nuclear throne,daily run,statistics">

    <title>Nuclear Throne Daily Leaderboards {% block title %}{% endblock %}</title>

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <link href="/css/datepicker3.css" rel="stylesheet">

    <!-- Custom page CSS -->
    <link href="/css/custom.css?v=20150703" rel="stylesheet">

    <!-- To preface, I never asked for this. I simply googled "favicon best practices."
    I guess that it's a cool thing that you can pin thronebutt to iOS now and it won't
    look like crap. But it might be a bit overkill. -->

    <link rel="apple-touch-icon" sizes="57x57" href="/ico/apple-touch-icon-57x57.png?v=PYndGmY7N0">
    <link rel="apple-touch-icon" sizes="60x60" href="/ico/apple-touch-icon-60x60.png?v=PYndGmY7N0">
    <link rel="icon" type="image/png" href="/ico/favicon-32x32.png?v=PYndGmY7N0" sizes="32x32">
    <link rel="icon" type="image/png" href="/ico/favicon-16x16.png?v=PYndGmY7N0" sizes="16x16">
    <link rel="manifest" href="/ico/manifest.json?v=PYndGmY7N0">
    <link rel="shortcut icon" href="/ico/favicon.ico?v=PYndGmY7N0">
    <meta name="msapplication-TileColor" content="#9f00a7">
    <meta name="msapplication-config" content="/ico/browserconfig.xml?v=PYndGmY7N0">
    <meta name="theme-color" content="#ffffff">

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
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>

    <script>
      $( document ).ready (function () {
        $('[data-toggle="tooltip"]').tooltip();
      });
    </script>


    {% block head %}{% endblock %}
  </head>

  <body class="">
      <!-- Static navbar -->
      <nav class="navbar navbar-inverse navbar-static-top">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">
              <div class="hidden-xs">
                <img alt="Nuclear Throne Daily Run Stats" src="/img/thronebutt-logo-big.png">
                <div class="tagline stroke">NUCLEAR THRONE LEADERBOARDS</div>
              </div>
              <div class="visible-xs  ">Nuclear Throne Leaderboards</div></a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-icons">
              <li>
                <a href="/">
                  <div class="hidden-xs">
                    <img data-toggle="tooltip" title="Today's Daily Challenge" data-placement="bottom" src="/img/daily.png" {% if location != "daily" %}class="inactive"{% endif %} alt="Today's Daily">
                  </div>
                  <div class="visible-xs">
                    Today's Leaderboards
                  </div>
                </a>
              </li>
              <li>
                <a href="/all-time">
                  <div class="hidden-xs">
                    <img src="/img/alltime.png" data-toggle="tooltip" title="All-time stats" data-placement="bottom" {% if location != "alltime" %}class="inactive"{% endif %} alt="All-time stats">
                  </div>
                  <div class="visible-xs">
                    All-time stats
                  </div>
                </a>
              </li>
              <li>
                <a href="/archive">
                  <div class="hidden-xs">
                    <img src="/img/archive.png" data-toggle="tooltip" data-placement="bottom" title="Archives" {% if location != "archive" %}class="inactive"{% endif %} alt="Archive">
                  </div>
                  <div class="visible-xs">
                    Archives
                  </div>
                  </a>
                </li>
              <li>
                <a href="/about">
                  <div class="hidden-xs">
                    <img src="/img/about.png" data-toggle="tooltip" data-placement="bottom" title="About" {% if location != "about" %}class="inactive"{% endif %} alt="About">
                  </div>
                  <div class="visible-xs">
                    About
                  </div>
                  </a>
              </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              {% if session.steamid != "" %}
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Hi, {{ session.steamname }}! <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="/player/{{ session.steamid }}/">My profile</a></li>
                  <li><a href="/?logout">Logout</a></li>
                </ul>
              </li>
            </ul>
            {% endif %}
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>
      <div class="container">
        {% block content %}{% endblock %}
        <div class="row col-md-12 footer center-block">
          Nuclear Throne is property of Vlambeer. Steam is a trademark of Valve Incorporated. Neither Vlambeer nor Valve are affiliated with this site. This site was coded by <a href="http://steamcommunity.com/id/i542">[WA]Darwin</a>. Art was made by Justin Chan for Nuclear Throne update notes.
        </div><!-- /container -->
      </div>
  </body>
</html>
