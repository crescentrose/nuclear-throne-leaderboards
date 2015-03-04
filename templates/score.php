{% extends "default.php" %}

{% block content %}
<!-- Main page -->
<div class="row col-md-12 main center-block">
  <h1><img src="{{ avatar_medium }}" class="player-avatar"/> <a href="/player/{{ steamid }}">{{ name }}</a> was #{{ rank }} on the {{ date }} daily!</h1>
  <h4>{{ name }} killed {{ score }} enemies.</h2>
  {% if session.admin > 0 %}
  	<p><b>Admin tools:</b> {% if hidden %}<a href="/admin/player/{{ steamid }}/score/{{ hash }}/undelete">Unhide</a>{% else %}<a href="/admin/player/{{ steamid }}/score/{{ hash }}/delete">Hide</a>{% endif %}</p>  {% endif %}
  <div class="row">
    <div class="col-md-12">
    {% if suspected_hacker %}<p class="text-danger"> Note: {{ name }} is marked as a <span class="label label-danger">Suspected Hacker</span>. <a href="/about" class="text-danger"><b>Click here</b></a> to learn more about the hacker marking process.</p> {% endif %}
    {% if hidden %}<p class="text-danger"> Note: This score was hidden by the administrators. It won't show up on the front page or the archive page.</p>{% endif %}
    Think you can do better? <a href="steam://run/242680"><b>Play Nuclear Throne!</b></a>
    </div>
  </div>
</div>
{% endblock %}
