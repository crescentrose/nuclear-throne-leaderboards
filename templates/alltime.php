{% extends "default.php" %}
{% block head %}
<link rel="stylesheet" href="/css/index.css" />
{% endblock %}
{% block content %}
<div class="row">
    <div class="col-md-12 leaderboard">
    <div class="inner">
      <div class="row palace-wall">
        <div class="col-md-12">
          <h3 class="title stroke-hard">All-time Leaderboards</h3>
          <h5 class="title stroke">Combined scores from all daily runs</h5>
        </div>
      </div>
      <div class="row palace-floor">
        <div class="col-md-12">
          <table class="table table-responsive ranktable">
          <thead>
            <td class="col-sm-1">Rank</td>
            <td class="col-sm-6">Player</td>
            <td class="col-sm-1">{% if sort_by == "runs" %}<b>Runs</b>{% else %}<a href="/all-time/runs" title="Sort by runs">Runs</a>{% endif %}</td>
            <td class="col-sm-2">{% if sort_by == "avg" %}<b>Average score</b>{% else %}<a href="/all-time/avg" title="Sort by average">Average score</a>{% endif %}</td>
            <td class="col-sm-2">{% if sort_by == "" or sort_by == "score" %}<b>Total score</b>{% else %}<a href="/all-time/" title="Sort by total">Total score</a>{% endif %}</td>
          </thead>
          <tbody>
            {% for player in scores %}
            <tr>
              <td>{{ player.ranks }}</td>
              <td><img src="{{ player.avatar }}" class="player-avatar"/> <a href="/player/{{ player.steamid }}">{{ player.name }}</a>
              {% if player.wins > 0 %}
                <span class="crown pull-right"><img src="/img/crown.png" alt="This player has won on {{ player.wins }} day(s)!" /><span class="wins stroke">{{ player.wins }}</span></span>
              {% endif %}
              </td>
              <td>{% if sort_by == "runs" %}<b>{{ player.runs }}</b>{% else %}{{ player.runs }}{% endif %}</td>
              <td>{% if sort_by == "avg" %}<b>{{ player.average }}</b>{% else %}{{ player.average }}{% endif %}</td>
              <td>{% if sort_by == "" or sort_by == "score" %}<b>{{ player.score }}</b>{% else %}{{ player.score }}{% endif %}</td>
            </tr>
            {% endfor %}
          </tbody>
          </table>
          <center><a class="btn btn-default " href="/all-time/{{ page + 1 }}/{{ sort_by }}">More</a></center>
        </div>
      </div>
    </div>
  </div>
</div>

{% endblock %}