{% extends "default.php" %}

{% block content %}

<!-- Main page -->
<div class="row col-md-12 main center-block">
	<h1>All-time leaderboards</h1>
  <p>Click on one of the score columns to sort by it. Data is updated every hour.</p>
  <table class="table table-responsive ranktable">
    <thead>
      <td class="col-sm-1">Rank</td>
      <td class="col-sm-6">Player</td>
      <td class="col-sm-1">Runs</td>
      <td class="col-sm-2">{% if sort == "avg" %}<b>Average score</b>{% else %}<a href="/all-time/avg" title="Sort by average">Average score</a>{% endif %}</td>
      <td class="col-sm-2">{% if sort == "" or sort == "total" %}<b>Total score</b>{% else %}<a href="/all-time/" title="Sort by total">Total score</a>{% endif %}</td>
    </thead>
    <tbody>
      {% for player in alltime %}
      <tr>
        <td>{{ player.ranks }}</td>
        <td><img src="{{ player.avatar }}" class="player-avatar"/> <a href="/player/{{ player.steamid }}">{{ player.name }}</a>
        </td>
        <td>{{ player.runs }}</td>
        <td>{% if sort == "avg" %}<b>{{ player.average }}</b>{% else %}{{ player.average }}{% endif %}</td>
        <td>{% if sort == "" or sort == "total" %}<b>{{ player.score }}</b>{% else %}{{ player.score }}{% endif %}</td>
      </tr>
      {% endfor %}
    </tbody>
    </table>
    <center><a class="btn btn-default " href="/all-time/{{ page + 1 }}/{{ sort }}">More</a></center>
</div>

{% endblock %}