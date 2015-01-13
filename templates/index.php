{% extends "default.php" %}

{% block content %}
<!-- Main page -->
<div class="row col-md-12 main center-block">
  <h1>Daily run statistics for {{ date }}</h1>
  <h4>Data updated every 15 minutes.</h4>
  <form class="form-inline" id="searchform">
    <div class="form-group">
      <input type="text" class="form-control" id="search" style="width: 300px" placeholder="Enter your Steam custom ID (e.g. i542)">
    </div>
    <button type="submit" class="btn btn-default">Search</button>
  </form>
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
        <td><img src="{{ player.avatar }}" class="player-avatar"/> <a href="/player/{{ player.steamId }}">{{ player.name }}</a>
        {% if player.suspected_hacker %}
          <span class="label label-danger pull-right">Suspected Hacker</span>
        {% endif %}</td>
        <td>{{ player.score }}</td>
      </tr>
      {% endfor %}
    </tbody>
    </table>
    <center><a class="btn btn-default " href="/daily/{{ page }}">More</a></center>
</div>
<script>$('#searchform').submit(function(e) {
  window.location = '/player/' + $('#search').val();
  e.preventDefault();
})</script>
{% endblock %}
