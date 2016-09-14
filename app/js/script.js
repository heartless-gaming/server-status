/*
* Hello There c'est Skull !
* On va faire du jquery comme des cochon !
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
      $(el).text(updatedPlayers[i])
    })
    // $gameServerStatus.each(function (j, el) {
    //   $(el)
    //   console.log(j)

    // })
  })
  // setTimeout(reloadServerInfo, 150000000)
}

jQuery(document).ready(function ($) {
  greetingMessage()
  arrowGameServer()
  reloadServerInfo()
})

/*
 * Reload the content of the game server status json
 * file into the page every 15seconds
 */
