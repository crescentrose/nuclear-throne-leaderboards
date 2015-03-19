{% extends "default.php" %}

{% block content %}
<!-- Main page -->
<div class="container-fluid ">
  <div class="row">
      <div class="col-md-8 main">
          <h1>Daily run leaderboards for {{ data.date }}</h1>
          <h4>Data updated every 15 minutes.</h4>
          <form class="form-inline" id="searchform">
            <div class="form-group">
              <input type="text" class="form-control" id="search" style="width: 300px" placeholder="Enter your Steam custom ID (e.g. i542)">
            </div>
            <button type="submit" class="btn btn-default">Search</button>
          </form>
          <div class="global">
            <div class="stat">Entries today: <b>{{ data.global.amount }}</b></div><div class="stat">Average score: <b>{{ data.global.average }}</b></div>
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
              {% for player in data.players %}
              {% if player.hidden == 0 %}
              <tr>
                <td width="30px">{{ player.rank }}</td>
                <td>
                  <img src="{{ player.avatar }}" class="player-avatar"/> <a href="/player/{{ player.steamId }}">{{ player.name }}</a>
                  {% if player.suspected_hacker %}
                    <span class="label label-danger pull-right">Suspected Hacker</span>
                  {% endif %}
                  {% if player.wins > 0 %}
                    <span class="pull-right crown"><img src="/img/crown.png" alt="This player has won on {{ player.wins }} day(s)!" /><span class="wins">{{ player.wins }}</span></span>
                  {% endif %}
                </td>
                {% if session.admin > 0 %}
                <td>{{ player.first_created }}</td>
                {% endif %}
                <td>{{ player.score }}</td>
              </tr>
              {% else %}
              <tr class="hidden-score">
                <td colspan="6"><i><center>A score was hidden by the site administrator. {% if session.admin > 0 %}[Admin: <a href="/score/{{ player.hash }}">score</a> | <a href="/player/{{ player.steamId }}">profile</a> ]{% endif%}</center></i></td>
              </tr>
              {% endif %}
              {% endfor %}
            </tbody>
            </table>
            <center><a class="btn btn-default " href="/daily/{{ data.page }}">More</a></center>
        </div>
      <div class="col-md-4">
        <div class="sidebar-box">
          <h4>Yesterday's top 5</h4>
           <table class="table table-responsive table-condensed ranktable"> 
            <thead>
              <td>Rank</td>
              <td>Player</td>
              <td>Score</td>
            </thead>
            <tbody>
              {% for player in data.players_yesterday %}
              <tr>
                <td width="30px">{{ player.rank }}</td>
                <td><img src="{{ player.avatar }}" class="player-avatar"/> <a href="/player/{{ player.steamId }}">{{ player.name }}</a>
                {% if player.suspected_hacker %}
                  <span class="label label-danger pull-right">Suspected Hacker</span>
                {% endif %}</td>
                <td>{{ player.score }}</td>
              </tr>
              {% endfor %}
            </tbody>
          </table>
        </div>
        <div class="sidebar-box">
          <h4>Currently popular streams</h4> 
          <div class="streams container-fluid">
          {% if data.streamcount == 0 %}
            <p>Noone is streaming right now :( It can take up to 15 minutes for streams to show up here, so be patient!</p>
          {% else %}
          {% for stream in data.streams %}   
            <div class="stream row">
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
          {% endif %}
          </div>
        </div>
       <div class="sidebar-box">
          <h4>Community links</h4>
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
<script>$('#searchform').submit(function(e) {
  window.location = '/player/' + $('#search').val();
  e.preventDefault();
})</script>
{% endblock %}
