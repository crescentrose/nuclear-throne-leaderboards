{% extends "default.php" %}

{% block content %}
<!--Load the chart API-->
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Date');
        data.addColumn('number', 'Rank');
        data.addRows([
          {% for score in scores %}
            ['{{ score.dayId }}', {{ score.rank }}],
          {% endfor %}
        ]);

        // Set chart options
        var options = {'title':'{{ player.name }}\'s rank',
                       'width':500,
                       'height':200};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.LineChart(document.getElementById('chart'));
        chart.draw(data, options);
      }
</script>

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
    <div id="chart"></div>
</div>
{% endblock %}
