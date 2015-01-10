{% extends "default.php" %}

{% block content %}

<!-- Main page -->
<script src="/js/bootstrap-datepicker.js"></script>
<div class="row col-md-12 main center-block">
	<h1>Leaderboard Archives</h1>
	<center><div id="date"></div></center>
	<script> 
	var yesterday = (function(d){ d.setDate(d.getDate()-1); return d})(new Date)

	$('#date').datepicker({
    format: "yyyy-mm-dd",
    weekStart: 1,
    startDate: "2014-12-31",
    endDate: yesterday.toISOString(),
    language: "en-GB"
    });
    $('#date').datepicker()
    .on("changeDate", function(e){
        window.location = '/archive/' + e.date.getFullYear() + '/' + ("0" + (e.date.getMonth() + 1)).slice(-2) + '/' + ("0" + e.date.getDate()).slice(-2) + '/page-1/';
    });</script>
</div>
{% endblock %}
