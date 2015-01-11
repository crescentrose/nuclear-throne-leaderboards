{% extends "default.php" %}

{% block content %}
<!-- Main page -->
<div class="row col-md-12 main center-block">
  <h1>Stats for {{ date }}</h1>
  <h4>Data updated every 15 minutes.</h4>
  <table class="table table-responsive">
    <thead>
      <td>Rank</td>
      <td>Player</td>
      <td>Score</td>
    </thead>
    <tbody>
      {% for player in players %}
      <tr>
        <td width="30px">{{ player.rank }}</td>
        <td><img src="{{ player.avatar }}" class="player-avatar"/> <a href="/player/{{ player.steamId }}">{{ player.name }}</a></td>
        <td>{{ player.score }}</td>
      </tr>
      {% endfor %}
    </tbody>
    </table>
    <center><a class="btn btn-default " href="/daily/{{ page + 1 }}">More</a></center>
</div>
{% endblock %}
