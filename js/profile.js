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

         $.each( parsed, function( index, value ){
            console.log(index + "..." + value);
         });

         $.each( parsed.scores, function( index, value ){
            $('#latest_score_table').append('<tr><td>' + value.raw.date + '</td><td>' + value.percentile + '%</td><td><b>#' + value.rank + '</b></td><td><b>' + value.score + '</b></td>');
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