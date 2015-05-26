$('#search_form').submit(function(e) {
  window.location = '/search/' + $('#search').val();
  e.preventDefault();
})
