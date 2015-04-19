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
    <div class="sidebar-box">
      <div class="row mansion-wall">
        <div class="col-md-12">
          <h4 class="title stroke sidebar-title">About player</h4>
        </div>
      </div>
      <div class="row mansion-floor">
      </div>
    </div>
  </div>
</div>
<script src="/js/update.js"></script>

{% endblock %}
