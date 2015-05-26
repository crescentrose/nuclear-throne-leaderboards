{% extends "default.php" %}

{% block content %}


<!-- Main page -->
<div class="row">
  <div class="col-md-8 leaderboard">
    <div class="inner">
      <div class="row palace-wall">
        <div class="col-md-12">
          <h3 class="title stroke-hard">
            <a href="/player/{{ player.steamid }}" class="more-link">{{ player.name }}'s run</a>
          </h3>
          <h5 class="title stroke">
            {{ raw.date }}
          </h5>
        </div>
      </div>

      <div class="row palace-floor">
        <div class="col-md-12">
          <h4 class="profile-subtitle stroke">{{ score }} kills, ranked #{{ rank }}</h4>
          {% if raw.video %}
            {% if raw.video.type == "youtube" %}
              <iframe width="560" height="315" src="https://www.youtube.com/embed/{{ raw.video.id }}" frameborder="0" allowfullscreen></iframe>
            {% else %}

            {% endif %}
          {% endif %}
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4 sidebar">
    {% if session.admin %}
    <div class="sidebar-box">
      <div class="row mansion-wall">
        <div class="col-md-12">
          <h4 class="title stroke sidebar-title">Administration</h4>
        </div>
      </div>
      <div class="row mansion-floor">
        <p style="padding:5px;">Note: A hidden score will be re-ranked under the last legitimate score after the next update.</p>
        {% if raw.hidden == 1 %}
        <a class="btn btn-retro" href="/admin/player/{{ player.steamid }}/score/{{ hash }}/undelete">Unhide</a>
        {% else %}
        <a class="btn btn-retro" href="/admin/player/{{ player.steamid }}/score/{{ hash }}/delete">Hide</a>
        {% endif %}
      </div>
    </div>
    {% endif %}
  </div>
</div>
<script src="/js/update.js"></script>

{% endblock %}
