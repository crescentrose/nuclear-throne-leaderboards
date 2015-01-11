{% extends "default.php" %}

{% block content %}
<!-- Main page -->
<div class="row col-md-12 main center-block">
  <h1>About</h1>
  <p>Hi! I made this website so that I could have a better overview over my Nuclear Throne daily runs, which aren't available on public Steam leaderboards.</p>
  <p>You can contact me via <a href="http://steamcommunity.com/id/i542">Steam</a> if you're a fellow gamer, or via mail: <code>tko.netko&lt;at&gt;gmail.com</code>.</p>
  <p>This page would not be possible without:
  <ul><li><a href="http://store.steampowered.com">Steam</a> Web API</li>
  <li><a href="http://nuclearthrone.com/">Nuclear Throne</a>, the game</li>
  <li><a href="http://getbootstrap.com/">Bootstrap</a></li>
  <li><a href="http://twig.sensiolabs.org/">Twig</a></li>
  <li><a href="https://github.com/eternicode/bootstrap-datepicker">Date picker for Bootstrap</a></li></ul></p>
  <h3>FAQ</h3>
  <b>Q: Can I remove myself from the stats?</b>
  <p>A: Yes, don't play the game. <i>(badum-tss)</i> All of the data on this page is already public via Steam APIs - there's no black magic going on. However there might be a opt-out feature in the near future.</p>
  <b>Q: Can I pimp my profile?</b>
  <p>A: Profiles are in a very basic state as of now - expect lots of upgrades on that front. If there's enough interest you'll be able to edit them.</p>
  <b>Q: I've found a bug or have an idea - who do I yell at?</b>
  <p>A: My mail is right up there.</p>
  <b>Q: How was this page made?</b>
  <p>A: Check the <a href="https://github.com/notyourshield/nuclear-throne-leaderboards">source on GitHub!</a> Side effects may include shock, dismay, rage, karma from /r/ShittyProgramming or laugh overdose.</p>
</div>
{% endblock %}
