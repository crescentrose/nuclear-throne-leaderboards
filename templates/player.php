{% extends "default.php" %}

{% block content %}
<!-- Main page -->
<div class="row col-md-12 main center-block">
  <h1><img src="{{ player.avatar_medium }}" class="player-avatar" /> Stats for {{ player.name }}</h1>
  <table class="table table-responsive table-hover">
    <thead>
      <td>Date</td>
      <td>Rank</td>
      <td>Score</td>
    </thead>
    <tbody>
      {% for score in scores %}
      <tr onclick="document.location = '/score/{{ score.hash}}'">
        <td>{{ score.dayId }}</td>
        <td><b>{{ score.rank }}</b></a></td>
        <td>{{ score.score }}</td>
      </tr>
      {% endfor %}
    </tbody>
    </table>
</div>
{% endblock %}
