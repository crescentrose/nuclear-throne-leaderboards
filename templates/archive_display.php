{% extends "default.php" %}

{% block content %}

<!-- Main page -->
<script src="/js/bootstrap-datepicker.js"></script>
<div class="row col-md-12 main center-block">
	<h1>Leaderboard Archives for {{ year }}-{{ month }}-{{ day }}</h1>
	<center><div id="date"></div></center>
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
    {% if count == 0 %}
    <b>There's no more archive entries.</b>
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
              <span class="pull-right crown"><img src="/img/crown.png" alt="Previous wins" title="This player has won on {{ score.player.raw.wins }} day(s)!" /><span class="wins">{{ score.player.raw.wins }}</span></span>
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

{% endblock %}