var page = 0;
var steamid = $("meta[name='steamid']").attr("content");
var done = true;

$('#nextPageBtn').click(function(event) {
   event.preventDefault();

   if (done == false) {
      $('#nextPageBtn').html('Be patient!')
      return;
   }

   $('#nextPageBtn').html('Loading...');

   done = false;

   $.ajax({
      url: "/index.php?do=player&steamid=" + steamid + "&page=" + (page + 1) + "&json",
      success: function(data) {
         parsed = JSON.parse(data)[0];
         $('#nextPageBtn').html('Older scores');

         $.each( parsed.scores, function( index, value ){
           var append = "<tr";
           if (value.hidden == 1) {
             append = append + ' class="hidden-score"';
           }
            append = append + '><td>' + value.raw.date + '</td><td>' + value.percentile + '%</td><td><b>#' + value.rank + '</b></td><td><b>' + value.score + '</b><span class="pull-right"><a href="/score/' + value.hash + '"><span class="glyphicon glyphicon-plus more-link"></span></a></span>';
            if (value.raw.video) {
               append = append + '<span class="pull-right"><a target="_blank" href="' + value.raw.video + '"><img src="/img/youtube.png" alt="Video link" title="There\'s a video attached to this score." /></a></span>';
            }
            append = append + '</td></tr>';
            $('#latest_score_table').append(append);
         });

         if (parsed.count < 15) {
            $('#nextPageBtn').html("That's all!");
         }
         page = page + 1;

         done = true;
      },
      error: function() {
         $('#nextPageBtn').html('Error! Try again.');
      }
   });
});
