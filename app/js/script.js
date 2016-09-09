console.log('lodr')
/*
* Hello There c'est Skull !
*/
var greetingMessage = function () {
  console.log('  #####   ')
  console.log(' #######  ')
  console.log('#  ###  # ' + ' Hello There')
  console.log('#   #   # ')
  console.log('######### ' + ' Come contribute to the code !')
  console.log(' ### ###  ')
  console.log('  #####   ' + ' github.com/heartless-gaming')
  console.log('  # # #   ')
}

/*
* Toggle to colapse the game servers info.
*/
var arrowGameServer = function () {
  var $arrowGameserver = $('.js-gameserver')

  $arrowGameserver.removeClass('is-hidden')

  $arrowGameserver.click(function (event) {
    // Slide the Game server list up or down
    $(this)
      .toggleClass('rotate90')
      .parents('.game-server')
      .children('.js-gameserver-list')
      .stop()
      .slideToggle(500)
  })
}

jQuery(document).ready(function ($) {
  greetingMessage()
  arrowGameServer()
})
