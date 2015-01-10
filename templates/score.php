{% extends "default.php" %}

{% block content %}
<!-- Main page -->
<div class="row col-md-12 main center-block">
  <h1><img src="{{ avatar_medium }}" class="player-avatar"/> <a href="/player/{{ steamid }}">{{ name }}</a> was #{{ rank }} on the {{ date }} daily!</h1>
  <h4>{{ name }} killed {{ score }} enemies.</h2>
  <div class="row">
    <div class="col-md-12">
    Think you can do better? <a href="steam://run/242680"><b>Play Nuclear Throne!</b></a>
    </div>
  </div>
</div>
{% endblock %}
