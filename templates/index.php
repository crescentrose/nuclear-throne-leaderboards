{% extends "default.php" %}

{% block head %}
<link rel="stylesheet" href="/css/index.css" />
{% endblock %}


{% block content %}

<!-- Main page -->
<div class="row">
    <div class="col-md-8 leaderboard">
    <div class="inner">
      <div class="row palace-wall">
        <div class="col-md-12">
          <h3 class="title stroke-hard">Daily Leaderboard</h3>
          <h5 class="title stroke">{{ date }}</h5>
        </div>
      </div>
      <div class="row palace-floor">
        <div class="col-md-12">
          <form class="form-inline form-search" id="search_form">
            <div class="form-group">
              <input type="text" class="form-control text-retro" id="search" placeholder="Search">
            </div>
            <button type="submit" id="do_search" class="btn btn-retro">Go</button>
          </form>
          <div class="global stroke">
            <div class="stat">Entries today: <b>{{ global.runcount }}</b></div><div class="stat">Average score: <b>{{ global.avgscore|round }}</b></div>
        </div>
        <table class="table table-responsive ranktable">
          <thead>
            <td>Rank</td>
            <td>Player</td>
            {% if session.admin > 0 %}
            <td><abbr title="The time that this score first showed up in the Steam Leaderboards">Time completed</abbr></td>
            {% endif %}
            <td>Score</td>
          </thead>
          <tbody>
            {% for score in scores %}
            {% if score.hidden == 0 %}
            <tr>
              <td width="30px">{{ score.rank }}</td>
              <td>
                <a href="/player/{{ score.player.steamid }}"><img src="{{ score.player.avatar }}" class="player-avatar"/> {% if score.player.donated %}<span class="donated">{{ score.player.name }}</span>{% else %}{{ score.player.name }}{% endif %}</a>
                {% if score.player.suspected_hacker %}
                  <span class="label label-danger pull-right">Suspected Hacker</span>
                {% endif %}
                {% if score.player.donated %}
                  <span class="pull-right"><img class="crown" src="/img/flair/muny.gif"  data-toggle="tooltip" title="This user donated during the July 2015 donation campaign." data-placement="bottom" />
                {% endif %}
                {% if score.raw.video %}
                  <span class="pull-right"><a href="{{ score.raw.video }}" target="_blank"><img src="/img/youtube.png" alt="Video link" title="There's a video attached to this score." /></a></span>
                {% endif %}
                {% if score.player.raw.wins > 0 %}
                  <span class="pull-right crown"><img src="/img/crown.png" alt="Previous wins" title="This player has won on {{ score.player.raw.wins }} day(s)!" /><span class="wins stroke">{{ score.player.raw.wins }}</span></span>
                {% endif %}
                {% if score.player.twitch %}
                  <span class="pull-right"><a href="https://twitch.tv/{{ score.player.twitch }}"><img src="/img/twitch.png" class="crown" data-toggle="tooltip" title="Click to visit player's Twitch page" data-placement="bottom"  /></a></span>
                {% endif %}
                {% if score.player.admin == 1 %}
                  <span class="pull-right"><img src="/img/flair/idpd.png" class="crown" data-toggle="tooltip" title="This user is a site moderator" data-placement="bottom"  /></span>
                {% endif %}
              </td>
              {% if session.admin > 0 %}
              <td>{{ score.first_created }}</td>
              {% endif %}
              <td>{{ score.score }}</td>
            </tr>
            {% else %}
            <tr class="hidden-score no-strike">
              <td colspan="6"><i><center>A score was hidden by the site administrator. {% if session.admin > 0 %}[Admin: <a href="/score/{{ score.hash }}">score</a> | <a href="/player/{{ score.player.steamid }}">profile</a> ]{% endif%}</center></i></td>
            </tr>
            {% endif %}
            {% endfor %}
          </tbody>
          </table>
          <center><a class="btn btn-retro stroke" href="/daily/{{ page }}">More</a></center>
        </div>
      </div>
      </div>
    </div>
    <div class="col-md-4 sidebar">
    {% if session.steamid == "" %}
      <div class="modal fade login-modal" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content dark-modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="loginModalLabel">Log in with Steam</h4>
            </div>
            <div class="modal-body">
              <p>Click the button below to be redirected to Steam for authentication.</p>

              <form id="login" action="/?login" method="POST">
                <center>
                  <input type="image" src="/img/sits_small.png" id="sign-in-btn" /><br/>
                  <input type="checkbox" name="remember-me" value="remember-me"  /> Remember me
                </center>
              </form>
              <br/>
              <p>Before logging in, please take a look at the <a href="/about" target="_blank">site rules and
              the privacy policy.</a> TL;DR - your data is as safe as we can make it, behave like
              you would in front of your mother.</p>
              <p><strong>If you select "Remember me", </strong> your login persists for up to two weeks, or until you clear your biscuits.</p>
            </div>
          </div>
        </div>
      </div>

      <div class="sidebar-box">
        <div class="row mansion-wall">
          <div class="col-md-12">
            <h4 class="title stroke sidebar-title">Steam Integration</h4>
          </div>
        </div>
        <div class="row mansion-floor">
          <div class="col-md-12 sidebar-text">
            <div class="center">
              <p>Login with your Steam account to view your personal details and link your Twitch account.</p>
              <button type="button" class="btn btn-retro stroke" data-toggle="modal" data-target=".login-modal">Log in</button>
            </div>
          </div>
      </div>
      </div>
      {% else %}
      <div class="modal fade twitch-modal" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="twitchModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content dark-modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="twitchModalLabel">Twitch integration</h4>
            </div>
            <div class="modal-body">
              <p>Give us your Twitch username, and we'll put a link to your channel along with any score of yours. Awesome for self-promotion!</p>
              <form class="form-inline form-search" id="twitch_form">
                  http://twitch.tv/ <input type="text" class="form-control text-retro text-twitch" id="twitch_user" value="{{ userdata.twitch }}">
                <input type="hidden" id="twitch_steamid" value="{{ session.steamid }}" />
              </form>
            </div>
            <div class="modal-footer">
              <button id="save-twitch" class="btn btn-retro">Save</button>
              <button data-dismiss="modal" class="btn btn-retro" id="close-twitch" >Close</button>
            </div>
          </div>
        </div>
      </div>
      <div class="sidebar-box">
        <div class="row mansion-wall">
          <div class="col-md-12">
            <h5 class="title stroke sidebar-title">Your profile</h5>
          </div>
        </div>
        <div class="row mansion-floor">
          <div class="col-md-12 sidebar-text">
            <div class="col-md-12">
              <div class="usercard">
                <a href="/player/{{ session.steamid }}">
                  <img src="{{ userdata.avatar_medium }}" class="user-picture" alt="Your avatar"/>
                  <div class="name stroke">{{ session.steamname }}</div>
                </a>
                {% if userdata.rank > 0 %}
                <div class="subname stroke">All-time: #{{ userdata.rank }}</div>
                {% endif %}
              </div>
            </div>
            <div class="col-md-12 user-today">
              <h4>Today's performance:</h4>
              {% if userdata.today_rank != "N/A" %}
                <p>Rank: #{{ userdata.today_rank }}<br/>
                Percentile: {{ userdata.percentile }}%</p>
              {% else %}
                <p>No data available... yet! Go play some Nuclear Throne!</p>
              {% endif %}
              {% if userdata.suspected_hacker %}
              <p><b>Warning:</b> You are marked as a suspected cheater. Your scores won't be visible on the site. Appeal <a href="http://forum.thronebutt.com">here.</a></p>
              {% endif %}
              <button type="button" class="btn btn-retro stroke" data-toggle="modal" data-target=".twitch-modal"><img src="/img/twitch-white.png" > {% if userdata.twitch %}Change{% else %}Link{% endif %}</button>
          </div>
        </div>
      </div>
      </div>
      {% endif %}
      <div class="sidebar-box">
        <div class="row vault-wall">
          <div class="col-md-12">
            <h4 class="title stroke  sidebar-title">Yesterday's top 5</h4>
          </div>
        </div>
        <div class="row vault-floor">
          <div class="col-md-12">
           <table class="table table-responsive table-condensed ranktable">
            <thead>
              <td>Rank</td>
              <td>Player</td>
              <td></td>
              <td>Score</td>
            </thead>
            <tbody>
              {% for score in scores_yesterday %}
              <tr>
                <td width="30px">{{ score.rank }}</td>
                <td><img src="{{ score.player.avatar }}" class="player-avatar"/> <a href="/player/{{ score.player.steamid }}">{% if score.player.donated %}<span class="donated">{{ score.player.name }}</span>{% else %}{{ score.player.name }}{% endif %}</a>
                {% if score.player.suspected_hacker %}
                  <span class="label label-danger pull-right">Suspected Hacker</span>
                {% endif %}</td>
                <td>

                {% if score.player.raw.wins > 0 %}
                    <span class="pull-right crown"><img src="/img/crown.png" title="This player has won on {{ score.player.raw.wins }} day(s)!" alt="Previous wins" /><span class="wins stroke">{{ score.player.raw.wins }}</span></span>
                  {% endif %}
                  {% if score.player.twitch %}
                    <span class="pull-right crown"><a href="https://twitch.tv/{{ score.player.twitch }}"><img src="/img/twitch.png" class="crown" data-toggle="tooltip" title="Click to visit player's Twitch page" data-placement="bottom"  /></a></span>
                  {% endif %}</td>

                <td>{{ score.score }}</td>
              </tr>
              {% endfor %}
            </tbody>
          </table>
        </div>
      </div>
      </div>
      <div class="sidebar-box">
          <div class="row desert-wall">
            <div class="col-md-12">
              <h4 class="title stroke">Currently popular livestreams</h4>
            </div>
          </div>
          <div class="row desert-floor">
            <div class="col-md-12">
        {% if streamcount == 0 %}
          <p>No one is livestreaming Nuclear Throne right now.</p>
        {% else %}
        <div class="streams">
        {% for stream in streams %}
          <div class="stream">
            <div class="stream-pic-container col-md-3">
              <a href="http://twitch.tv/{{ stream.name }}"><img src="{{ stream.preview }}" class="stream-pic" /></a>
            </div>
            <div class="stream-meta col-md-9">
              <div class="stream-title">
                <a href="http://twitch.tv/{{ stream.name }}">{{ stream.status }}</a>
              </div>
              <div class="stream-desc">
                on <a href="http://twitch.tv/{{ stream.name }}">{{ stream.name }}</a> | {{ stream.viewers }} viewers
              </div>
            </div>
       </div>
        {% endfor %}
        </div>
        {% endif %}
        </div>
        </div>
      </div>

  <a class="twitter-timeline" href="https://twitter.com/thronebutt" data-widget-id="612672701031915520">Tweets by @thronebutt</a>
  <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
    </div>
    </div>
  </div>


<script src="/js/home.js"></script>
<script>  $( "#sign-in-btn" ).click(function(e) {
    $( "#login-form" ).submit();
  });</script>
{% endblock %}
