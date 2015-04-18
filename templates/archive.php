{% extends "default.php" %}
{% block head %}
<link rel="stylesheet" href="/css/index.css" />
{% endblock %}

{% block content %}

<!-- Main page -->
<script src="/js/bootstrap-datepicker.js"></script>
<div class="row">
  <div class="col-md-8 leaderboard">
    <div class="inner">
    <div class="row palace-wall">
      <div class="col-md-12">
          <h3 class="title stroke-hard">Leaderboard Archive</h3>
          <h5 class="title stroke">{{ year }}-{{ month }}-{{ day }}</h5>
      </div>
    </div>
    <div class="row palace-floor">
      <div class="col-md-12">
          <div class="global stroke">
            <div class="stat">Entries: <b>{{ global.runcount }}</b></div><div class="stat">Average score: <b>{{ global.avgscore|round }}</b></div>
          </div>
        {% if count == 0 %}
          <b>There are no more archive entries.</b>
        {% else %}
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
                  <td colspan="6"><i><center>A score was hidden by the site administrator. {% if session.admin > 0 %}[Admin: <a href="/score/{{ player.hash }}">score</a> | <a href="/player/{{ player.steamid }}">profile</a> ]{% endif%}</center></i></td>
                </tr>
                {% endif %}
              {% endfor %}
            </tbody>
          </table>
          <center><a class="btn btn-default " href="/archive/{{ year }}/{{ month }}/{{ day }}/page-{{ page + 1 }}/">More</a></center>
        {% endif %}
      </div>
      </div>
    </div>
  </div>
  <div class="col-md-4 sidebar">
      <div class="sidebar-box">
        <div class="row vault-wall">
          <div class="col-md-12">
            <h4 class="title stroke sidebar-title">Archives</h4>
          </div>
        </div>
        <div class="row vault-floor">
          <center><div id="date"></div></center>
      </div>
      </div>
    </div>
	<script> 
	var yesterday = (function(d){ d.setDate(d.getDate()-1); return d})(new Date)
	$('#date').datepicker({
    format: "yyyy-mm-dd",
    weekStart: 1,
    startDate: "2014-12-31",
    endDate: yesterday.toISOString(),
    language: "en-GB"
    });
    $('#date').datepicker()
    .on("changeDate", function(e){
        window.location = '/archive/' + e.date.getFullYear() + '/' + ("0" + (e.date.getMonth() + 1)).slice(-2) + '/' + ("0" + e.date.getDate()).slice(-2) + '/page-1/';
    });</script>
  </div>
</div>

{% endblock %}