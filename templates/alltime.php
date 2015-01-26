{% extends "default.php" %}

{% block content %}

<!-- Main page -->
<div class="row col-md-12 main center-block">
	<h1>All-time leaderboards</h1>
  <h4>Data updated hourly</h4>
  <table class="table table-responsive ranktable">
    <thead>
      <td>Rank</td>
      <td>Player</td>
      <td>Runs</td>
      <td>Total score</td>
    </thead>
    <tbody>
      {% for player in alltime %}
      <tr>
        <td width="30px">{{ player.ranks }}</td>
        <td><img src="{{ player.avatar }}" class="player-avatar"/> <a href="/player/{{ player.steamid }}">{{ player.name }}</a>
        </td>
        <td>{{ player.runs }}</td>
        <td>{{ player.score }}</td>
      </tr>
      {% endfor %}
    </tbody>
    </table>
    <center><a class="btn btn-default " href="/all-time/{{ page + 1 }}/">More</a></center>
</div>

{% endblock %}