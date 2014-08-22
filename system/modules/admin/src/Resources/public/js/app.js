/* Pjax coming soon
$(document).pjax('a[data-pjax]', '#page-wrapper');

$(document).on('pjax:send', function() {
  //$('#loading').show()
  console.log('pjax turn.');
})

$(document).on('pjax:complete', function() {
  //$('#loading').hide()
})
*/

// sidebar togler for mobilescreen
$(function(){

    // feature under construction
    $('.soon').click(function(e){
      e.preventDefault();
      alert('Feature Under Construction !');
    });   
});