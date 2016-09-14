/*
* Hello There c'est Skull !
* My jquery suck
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

var reloadServerInfo = function () {
  var $gameServerPlayers = $('.game-server__players')
  var $gameServerStatus = $('.game-server__status')

  $.getJSON('json/heartlessgaming-serverstatus.json', function (data) {
    var updatedPlayers = []
    var updatedStatus = []

    for (var i = 0, l = data.games.length; i < l; i++) {
      var gameServers = data.games[i].gameServers
      for (var j = 0, m = gameServers.length; j < m; j++) {
        updatedPlayers.push(gameServers[j].players)
        updatedStatus.push(gameServers[j].status)
      }
    }

    $gameServerPlayers.each(function (i, el) {
      if ($(el).text() !== updatedPlayers[i]) {
        $(el).text(updatedPlayers[i])
      }
    })
  })
  setTimeout(reloadServerInfo, 15000)
}

var gameServerInfoClick = function () {
  var $gameServerInfo = $('.game-server__info')

  $gameServerInfo.click(function (event) {
    $(this).find('.game-server__ip')
      .stop()
      .fadeToggle('slow')
  })
}

jQuery(document).ready(function ($) {
  greetingMessage()
  arrowGameServer()
  reloadServerInfo()
  gameServerInfoClick()
})

/*
 * Reload the content of the game server status json
 * file into the page every 15seconds
 */
