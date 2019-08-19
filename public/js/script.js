/*
 * @copyright Broadposter
 * @version 1.0
 *
 * Main script page for Broadposter
 * 
 * Author: Dory A.Azar
 * Copyright: Broadposter - 2018
 */


/* Jquery script for kickposter */

$(document).ready(function() {

/* on window resize make sure menu is closed */
$(window).resize(function() {
    if (isVisible($('#vertical_menu'))) {
        toggleMenu($('#vertical_menu'));              
    }
});

/* control the vertical menu on small screens */   
$('header').click(function(){
    if (isDisplayed($('#menu_icon'))) {
        toggleMenu($('#vertical_menu'));
    }
});

/* control closing */
$('.wrapper, section, footer').click(function(e){
   if (isVisible($('#vertical_menu'))) {
       toggleMenu($('#vertical_menu'));
        e.preventDefault();
   }
});

/* smooth scrolling for nav sections */
$('a').click(function(e){
  var link = $(this).attr('href');
  if (link == '') {
      e.preventDefault();
      $('body,html').animate({scrollTop:0},1000);
   } else if (link.match(['^#kp'])){
      var posi = $(link).offset().top - 59;
      $('body,html').animate({scrollTop:posi},700);
   } 
});
    
/* identify what content is on screen */
$(window).scroll(function() {
    var visible_divs = getIdOnScreen($("div[id^='kp']"));
    if (visible_divs.length != 0) { //only if there are values then apply the scrolling identification mechanism
      var top_item = visible_divs[visible_divs.length - 1];
      var top_item_id = top_item.getAttribute('id');
      var selected_anchors = $('a.selected');
      var item_to_select = $("a[href='#"+top_item_id+"']");
      if (selected_anchors.length == 0 || (selected_anchors.length != 0 && $(this).attr('href') != selected_anchors[0])) {
          toggleSelected(selected_anchors, item_to_select);
      }
    }
});


    
}); /* end jquery on document ready */

                  
/* Main javascript functions that will be called from jquery */

function toggleMenu(menu) {
    menu.toggleClass('show');
}

function isDisplayed(element) {
    return_value = false;
    if (element.css("display") != "none") {
        return_value = true;
    }
    return return_value;
}

function isVisible(element) {
    return_value = false;
    if (element.hasClass('show')) {
       return_value = true;
   }
    return return_value;
}

function toggleSelected(menu, itemtoselect) {
  menu.removeClass('selected');
  itemtoselect.addClass('selected');
}

function getIdOnScreen(element) {
    var winTop = $(window).scrollTop();
    var content_id = element;
    
    var top = $.grep(content_id, function(item) {
        return $(item).position().top -60 <= winTop;
    });
    return top;
}

function animateCSS(element, animationName, callback) {
    const node = document.querySelector(element)
    node.classList.add('animated', animationName)

    function handleAnimationEnd() {
        node.classList.remove('animated', animationName)
        node.removeEventListener('animationend', handleAnimationEnd)

        if (typeof callback === 'function') callback()
    }

    node.addEventListener('animationend', handleAnimationEnd)
}

/* Social buttons*/
$(function () {
  var all_classes = "";
  var timer = undefined;
  $.each($('li', '.social-class'), function (index, element) {
    all_classes += " btn-" + $(element).data("code");
  });
  $('li', '.social-class').mouseenter(function () {
    var icon_name = $(this).data("code");
    if ($(this).data("icon")) {
      icon_name = $(this).data("icon");
    }
    var icon = "<i class='fa fa-" + icon_name + "'></i>";
    $('.btn-social', '.social-sizes').html(icon + "Sign in with " + $(this).data("name"));
    $('.btn-social-icon', '.social-sizes').html(icon);
    $('.btn', '.social-sizes').removeClass(all_classes);
    $('.btn', '.social-sizes').addClass("btn-" + $(this).data('code'));
  });
  $($('li', '.social-class')[Math.floor($('li', '.social-class').length * Math.random())]).mouseenter();
});
