<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>Nuclear Throne Stats</title>

    <!-- Bootstrap core CSS -->
    <link href="/throne/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom page CSS -->
    <link href="/throne/css/custom.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
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
            <a class="navbar-brand" href="#">Nuclear Throne Stats</a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li class="active"><a href="#">Today's Stats</a></li>
              <li><a href="#">Archive</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li><a href="http://reddit.com/r/nuclearthrone">/r/NuclearThrone</a></li>
              <li><a href="http://store.steampowered.com/app/242680/">Get Nuclear Throne on Steam!</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>
      <div class="row col-md-12"><div class="alert alert-warning" role="alert"><b>Beware!</b> This site is very early in development. As such, it WILL be broken. I'll finish it, I just require sleep.</div></div>
      {% block content %}{% endblock %}
     <div class="row col-md-12 footer center-block">
       Nuclear Throne is property of Vlambeer. Steam is a trademark of Valve Incorporated. Neither Vlambeer nor Valve are affiliated with this site. This site was coded by <a href="http://steamcommunity.com/id/i542">[SgC]Ruby</a>. This site is open-source - <a href="https://github.com/notyourshield/nuclear-throne-leaderboards">check it out on GitHub!</a> I've found the background with one of the patch notes - sorry for stealing, contact me if you're the original artist so that I can credit you or remove your work if you so desire!
     </div>
    </div> <!-- /container -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
