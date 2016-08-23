//BBC hompage slider
var homeSlider = {

  vars: {
    //tray position
    pos: 0,
    //lock the slider to prevent multiple clicks when animating
    locked: 0,
     //animation duration
    duration: 1000,
  },

  helpers: {

    //return percent from .att('style')
    getPercent: function(str){
      var foo = str.substring(str.indexOf('left:')+5);
      return parseInt(foo.substring(0, foo.indexOf('%')));
    },

    //unlock the slider post animation
    unlock: function(){
      homeSlider.vars.locked = 0;
    }

  },

  initAutoload: function(slideLength){
    if (homeSlider.vars.locked === 0) {
      if (slideLength > 2) {
        homeSlider.move.direction(1);
      } else {
        homeSlider.move.direction_two_slider(1);
      }

    }
  },

  //bind all the things
  init: function(options){
    var interval;

    options = jQuery.extend({
      timeOut: 3000,
      pause: false,
    }, options);

    var slideLength = $('section.all-articles').length;

    interval = setInterval(function(){
      homeSlider.initAutoload(slideLength);
    }, options.timeOut);

    if (options.pause) {
      clearInterval(interval);
    }

    if (slideLength <= 1) {
      clearInterval(interval);
    } else {
      if (slideLength <= 2) {
        $('.slider .mask').on('click', homeSlider.nav.arrow_two_slider);
      } else {
        $('.slider .mask').on('click', homeSlider.nav.arrow);
        $('.slider-nav a').on('click', homeSlider.nav.menu);
      }
    }

    if (!options.pause) {
      $('.slider-wrap').hover(function () {
        clearInterval(interval);
        return false;
      }, function() {
        if (typeof interval !== 'undefined') clearInterval(interval);
        interval = setInterval(function(){
          homeSlider.initAutoload(slideLength);
        }, options.timeOut);
      });
    }
  },

  peek: {

    //hover on masks triggers peek direction
    over: function(e){
      if (e.type == 'mouseenter' && $(this).hasClass('left')) {
        homeSlider.peek.move(15);
      } else if (e.type == 'mouseenter' && $(this).hasClass('right')) {
        homeSlider.peek.move(-15);
      } else {
        homeSlider.peek.move(0);
      }
    },

    //peek move
    move: function(dist){
      $('.slider-wrap .outer').stop().animate({'left': dist}, 300);
    }

  },

  nav: {

    //handles arror nav click
    arrow: function(e){
      e.preventDefault();
      if ( homeSlider.vars.locked === 0) {
        if ( $(this).hasClass('left') ){
          homeSlider.move.direction(0);
        } else {
          homeSlider.move.direction(1);
        }
      }
    },

    //handles a menu nav click
    menu: function(e){
      e.preventDefault();
      if ( homeSlider.vars.locked === 0) {
        homeSlider.vars.locked = 1;
        homeSlider.move.toSlide($(e.target.hash));
      }
    },

    //handles arror nav v2 click
    arrow_two_slider: function(e){
      e.preventDefault();
      if ( homeSlider.vars.locked === 0) {
        if ( $(this).hasClass('left') ){
          homeSlider.move.direction_two_slider(0);
        } else {
          homeSlider.move.direction_two_slider(1);
        }
      }
    },

    //updates the menu nav visual class and arrow text
    update: function(){
       //nav menu
      homeSlider.move.currentClass($('.slider-nav').find('a[href="#' + $('.slider .current').attr('id') + '"]').parent());
      // var offset = $('.slider-nav li.current').position().left / $('.slider-nav').width() * 100;
      // $('.slider-nav .highlight').stop().animate({'left': offset+'%'}, homeSlider.vars.duration, 'easeInOutQuart');
       //update arrow text
      $('.arrow.right span').text($('.slider-nav').find('a[href="#' + $('.slider .current').next().attr('id') + '"]').text());
      $('.arrow.left span').text($('.slider-nav').find('a[href="#' + $('.slider .current').prev().attr('id') + '"]').text());
    }

  },

  move: {

    direction: function(dir){
      homeSlider.vars.locked = 1;
      var $curr = $('.slider .current');

      //go right
      if (dir === 1) {
        homeSlider.vars.pos -= 100;
        if ( $curr.next().is(':last-child') ){
          //append first to last
          homeSlider.move.firstToLast();
          homeSlider.move.animate(homeSlider.vars.pos, true);
        } else {
          //no appendingzings
          homeSlider.move.animate(homeSlider.vars.pos, false);
        }
        homeSlider.move.currentClass($curr.next());

      //go left
      } else {
        homeSlider.vars.pos += 100;
        if ( $curr.prev().is(':first-child') ){
          //prepend last to first
          homeSlider.move.lastToFirst();
          homeSlider.move.animate(homeSlider.vars.pos, true);
        } else {
          //no appendingzings
          homeSlider.move.animate(homeSlider.vars.pos, false);
        }
        homeSlider.move.currentClass($curr.prev());

      }

      //if the slider is currently peeking, remove peek
      homeSlider.peek.move(0);
      //update the bottom nav
      homeSlider.nav.update();
    },

    direction_two_slider: function(dir){
      homeSlider.vars.locked = 1;
      var $curr = $('.slider .current');

      //go right
      if (dir === 1) {
        homeSlider.vars.pos -= 100;
        homeSlider.move.firstToLast();
        homeSlider.move.animate(homeSlider.vars.pos, true);
        homeSlider.move.currentClass($curr.next());

      //go left
      } else {
        homeSlider.vars.pos += 100;
        if ( $curr.prev().is(':first-child') ){
          //prepend last to first
          homeSlider.move.lastToFirst();
          homeSlider.move.animate(homeSlider.vars.pos, true);
        } else {
          //no appendingzings
          homeSlider.move.animate(homeSlider.vars.pos, false);
        }
        homeSlider.move.currentClass($curr.prev());

      }

      //if the slider is currently peeking, remove peek
      homeSlider.peek.move(0);
      //update the bottom nav
      homeSlider.nav.update();
    },

    toSlide: function($elm){

      var pos =  homeSlider.helpers.getPercent($elm.attr('style')) * -1;

      //if moving to the beginning of the stack
      if ( $elm.is(':first-child') ) {
        homeSlider.move.lastToFirst();
        homeSlider.move.currentClass($elm);
        homeSlider.move.animate(pos, true);

      //if moving to the end of the stack
      } else if ( $elm.is(':last-child') ) {
        homeSlider.move.firstToLast();
        homeSlider.move.currentClass($elm);
        homeSlider.move.animate(pos, true);

      //if moving to the middle of the stack
      } else {
        homeSlider.move.currentClass($elm);
        homeSlider.move.animate(pos, false);
      }

      //update the bottom nav
      homeSlider.nav.update();
      //update the tray positon
      homeSlider.vars.pos = pos;

    },

    //updates current class to requested slide
    currentClass: function($elm){
      $elm.addClass('current').siblings().removeClass('current');
    },

    //animation of the slider tray
    animate: function(pos, flush){
      $('.slider .tray').animate({'left': pos+'%'}, homeSlider.vars.duration, 'easeInOutQuart', function(){
        if (flush) {
          homeSlider.move.flush();
        } else {
          homeSlider.helpers.unlock();
        }
      });
    },

    //dupe first slide to end of stack + mark first slide for removal
    firstToLast: function(){
      var $elm = $('.slider section:first-child');
        pos = homeSlider.helpers.getPercent($('.slider section:last-child').attr('style')) + 100;
        $clone = $elm.clone();
      $elm.addClass('remove');
      $clone.removeAttr('style').css({'left': pos+'%' }).appendTo($('.slider .tray'));
    },

    //dupe last slide to beginning of stack + mark last slide for removal
    lastToFirst: function(){
      var $elm = $('.slider section:last-child');
        pos = homeSlider.helpers.getPercent($('.slider section:first-child').attr('style')) - 100;
        $clone = $elm.clone();
      $elm.addClass('remove');
      $clone.removeAttr('style').css({'left': pos+'%' }).prependTo($('.slider .tray'));
    },

    //remove old slide from stack
    flush: function(){
      $('.slider .tray section.remove').remove();
      homeSlider.helpers.unlock();
    }

  }

};

var helper = {

  init: function(){
    helper.center();
    $(window).resize(helper.center);
  },

  center: function(){
    $.each($('.slider section img'), function(i, elm){
      $(elm).css({marginTop:-$(elm).height()/2, top:'50%', position:'relative'});
    })
  }
}

helper.init();
