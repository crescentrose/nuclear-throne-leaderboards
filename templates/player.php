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
      {% for score in scores %}
      <tr>
        <td width="30px">{{ score.rank }}</td>
        <td><img src="{{ score.avatar }}" class="player-avatar"/> <a href="score/{{ score.hash }}">{{ score.name }}</a></td>
        <td>{{ score.score }}</td>
      </tr>
      {% endfor %}
    </tbody>
    </table>
</div>
{% endblock %}
