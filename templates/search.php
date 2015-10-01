{% extends "default.php" %}
{% block head %}
<link rel="stylesheet" href="/css/index.css" />
{% endblock %}
{% block content %}
<div class="row">
    <div class="col-md-12 leaderboard">
    <div class="inner">
      <div class="row palace-wall no-shadow">
        <div class="col-md-12">
          <h3 class="title stroke-hard">Search results</h3>
            <form class="form-inline form-search" id="search_form">
              <div class="form-group">
                <input type="text" class="form-control text-retro" id="search" placeholder="Search" value="{{ query }}">
              </div>
              <button type="submit" id="do_search" class="btn btn-retro">Go</button>
            </form>
        </div>
      </div>
      <div class="row palace-floor">
        <div class="col-md-12">
          {% if count == 0 %}
          <div class="not-found">We couldn't find anything. Sorry :(</div>
          {% endif %}
          <table class="table table-responsive ranktable">
          <tbody>
            {% for player in results %}
            <tr>
              <td><img src="{{ player.avatar }}" class="player-avatar"/> <a href="/player/{{ player.steamid }}">{{ player.name }}</a></td>
            </tr>
            {% endfor %}
          </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="/js/home.js"></script>
{% endblock %}
