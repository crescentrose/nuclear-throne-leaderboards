$('#search_form').submit(function(event) {
  window.location = '/search/' + $('#search').val();
  e.preventDefault();
})

$('#save-twitch').click(function(event) {
   event.preventDefault();

   $('#save-twitch').html('Saving...');
   $('#save-twitch').removeClass('btn-retro-success btn-retro-error');

   $.ajax({
      url: "/index.php?do=admin&act=update-twitch&json",
      method: 'post',
      data: {
        twitch_user: $("#twitch_user").val(),
        twitch_steamid: $("#twitch_steamid").val()
      },
      success: function(data) {
        data = JSON.parse(data);
         if (data.result == "error") {
           $('#save-twitch').addClass('btn-retro-error');
           $('#save-twitch').html('Error! Try again.');
         } else if (data.result == "permission") {
           $('#save-twitch').addClass('btn-retro-error');
           $('#save-twitch').html("You can't do that.");
         } else {
           $('#save-twitch').addClass('btn-retro-success');
           $('#save-twitch').html('Saved!');
         }
      },
      error: function() {
         $('#save-twitch').addClass('btn-retro-error').html('Error! Try again.');
      }
   });
});


$('#close-twitch').click(function(event) {
  $('#save-twitch').removeClass('btn-retro-success btn-retro-error').html('Save');
});
