$(function() {
	$(".dropdown").on("click", function() {
   $(".menu").slideToggle("slow");
    });
    $(".btn-notify").on("click", function() {
   $(".notify-down").slideToggle("slow");
    }); 
    $(".btn-login").on("click", function() {
   $(".user-dropdown").slideToggle("slow");
    });
    $(".advance").on("click", function() {
    $(".btn-advance").toggleClass("open");
   $(".list-advance").slideToggle("fast");
    });   
});