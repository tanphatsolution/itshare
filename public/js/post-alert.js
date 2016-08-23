$(window).bind('beforeunload',function(){
    return 'Your post was stored in draft.';
});

$('form#create-post').submit(function() {
   $(window).unbind('beforeunload');
});

$('form#edit-post').submit(function() {
   $(window).unbind('beforeunload');
});