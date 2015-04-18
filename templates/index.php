{% extends "default.php" %}

{% block head %}
<link rel="stylesheet" href="/css/index.css" />
{% endblock %}


{% block content %}

{% if notice %}
<div class="alert alert-info row">
{{ notice|raw }}
</div>
{% endif %}
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
                <img src="{{ score.player.avatar }}" class="player-avatar"/> <a href="/player/{{ score.player.steamid }}">{{ score.player.name }}</a>
                {% if score.player.suspected_hacker %}
                  <span class="label label-danger pull-right">Suspected Hacker</span>
                {% endif %}
                {% if score.player.raw.wins > 0 %}
                  <span class="pull-right crown"><img src="/img/crown.png" alt="Previous wins" title="This player has won on {{ score.player.raw.wins }} day(s)!" /><span class="wins stroke">{{ score.player.raw.wins }}</span></span>
                {% endif %}
              </td>
              {% if session.admin > 0 %}
              <td>{{ score.first_created }}</td>
              {% endif %}
              <td>{{ score.score }}</td>
            </tr>
            {% else %}
            <tr class="hidden-score">
              <td colspan="6"><i><center>A score was hidden by the site administrator. {% if session.admin > 0 %}[Admin: <a href="/score/{{ player.hash }}">score</a> | <a href="/player/{{ player.steamId }}">profile</a> ]{% endif%}</center></i></td>
            </tr>
            {% endif %}
            {% endfor %}
          </tbody>
          </table>
          <center><a class="btn btn-default " href="/daily/{{ page }}">More</a></center>
        </div>
      </div>
      </div>
    </div>
    <div class="col-md-4 sidebar">
    {% if session.steamid == "" %}
        <div class="sidebar-box">
        <div class="row mansion-wall">
          <div class="col-md-12">
            <h4 class="title stroke sidebar-title">Steam Integration</h4>
          </div>
        </div>
        <div class="row mansion-floor">
          <div class="col-md-12 sidebar-text">
            <div class="center">
              <p>Login with your Steam account to edit your profile, link YouTube videos to your 
              scores and interact with other players!</p>
              <a href="/?login"><img src="/img/sits_small.png" alt="Sign in via Steam"></a>
            </div>
          </div>
      </div>
      </div>
      {% else %}
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
                <img src="{{ userdata.avatar_medium }}" class="user-picture" alt="Your avatar"/>
                <div class="name stroke">{{ session.steamname }}</div>
                <div class="subname stroke">All-time: #{{ userdata.rank }}</div>
              </div>
            </div>
            <div class="col-md-12 user-today">
              <strong>Today's performance:</strong>
              {% if userdata.today_rank %}
                <p>Rank: #{{ userdata.today_rank }}<br/>
                Percentile: {{ userdata.percentile }}%</p>
              {% else %}
              <br/>No data available... yet!
              {% endif %}
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
                <td><img src="{{ score.player.avatar }}" class="player-avatar"/> <a href="/player/{{ score.player.steamid }}">{{ score.player.name }}</a>
                {% if score.player.suspected_hacker %}
                  <span class="label label-danger pull-right">Suspected Hacker</span>
                {% endif %}</td>
                <td>
                {% if score.player.raw.wins > 0 %}
                    <span class="crown"><img src="/img/crown.png" title="This player has won on {{ score.player.raw.wins }} day(s)!" alt="Previous wins" /><span class="wins stroke">{{ score.player.raw.wins }}</span></span>
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
     <div class="sidebar-box">
      <div class="row frozen-wall">
        <div class="col-md-12">
          <h4 class="title stroke sidebar-title">Community links</h4>
        </div>
      </div>
      <div class="row frozen-floor">
        <div class="col-md-12">
        <ul>
          <li><a href="http://nuclear-throne.wikia.com/wiki/Nuclear_Throne_Wiki">Nuclear Throne wiki</a></li>
          <li><a href="http://reddit.com/r/NuclearThrone">Nuclear Throne subreddit</a></li>
          <li><a href="http://steamcommunity.com/app/242680/discussions/">Steam Community forums for Nuclear Throne</a></li>
          <li><a href="http://www.twitch.tv/vlambeer">Developer Livestreams</a></li>
        </ul>
      </div>
      </div>
    </div>
    </div>
    </div>
  </div>
<script>$('#searchform').submit(function(e) {
  window.location = '/player/' + $('#search').val();
  e.preventDefault();
})</script>
{% endblock %}
