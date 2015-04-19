
$('#updateScoreBtn').click(function(event) {
   event.preventDefault();  

   if (done == false) {
      $('#updateScoreBtn').html('Be patient!')
      return;
   }

   $('#updateScoreBtn').html('Processing...');
   score = $('#updateScoreHash').val();
   video = $('#updateScoreVideo').val();
   comment = $('#updateScoreComment').val();

   done = false;

   $.ajax({
      method: "post",
      url: "/index.php?do=update&act=scoreupdate&json",
      data: {
         hash: score,
         video: video,
         comment: comment
      }
      success: function(data) {
         parsed = JSON.parse(data);
         if (!parsed.error) {
            $('#updateScoreBtn').html('Done!');
         } else {
            $('#updateScoreBtn').html(error + ' Try again!');
         }
         done = true;
      },
      error: function() {
         $('#updateScoreBtn').html('Error! Try again.');
      }
   });
});