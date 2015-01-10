{% extends "default.php" %}

{% block content %}
<!-- Main page -->
<div class="row col-md-12 main center-block">
  <h1><img src="{{ avatar }}" class="player-avatar"/> Stats for {{ name }}'s run on {{ dayId }}</h1>
  <div class="row">
    <div class="col-md-3">
    <table class="table table-responsive table-condensed">
        <tr>
          <td>Rank</td>
          <td>{{ rank }}</td>
        </tr>
        <tr>
          <td>Score</td>
          <td>{{ score }}</td>
        </tr>
    </table>
    </div>
  </div>
</div>
{% endblock %}
