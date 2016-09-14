const color = require('chalk')
const isIp = require('is-ip')
const Promise = require('bluebird')
const readFile = Promise.promisify(require('fs').readFile)
const writeFile = Promise.promisify(require('fs').writeFile)
const gameQuery = require('game-server-query')

const log = console.log.bind(console)

const gameServerMap = 'app/json/heartlessgaming-servermap.json'
const gameServerStatusJson = 'app/json/heartlessgaming-serverstatus.json'
const gameServersSteamApiJson = 'app/json/heartlessgaming-steamapi.json'

// let logResult = function (res) {
//   log(res)
// }

let logError = function (err) {
  log(color.yellow(err))
}

/*
 * Returns a parsed json file
 */
let readJson = function (jsonFile) {
  let parseJson = function (json) {
    return new Promise(function (resolve) {
      resolve(JSON.parse(json))
    })
  }

  let parsingError = function () {
    log(`Parsing of ${jsonFile} failed`)
  }

  return readFile(jsonFile, 'utf8')
    .then(parseJson)
    .catch(parsingError)
}

/*
 * Write a json file
 */
let writeJson = function (jsonFileName, fileContent) {
  let encodeJson = function (json) {
    return new Promise(function (resolve) {
      resolve(JSON.stringify(json))
    })
  }

  let encodeError = function () {
    log(`writing of ${jsonFileName} FAILED`)
  }

  let writeJsonFile = function (encodedJson) {
    return writeFile(jsonFileName, encodedJson)
  }

  return encodeJson(fileContent)
    .then(writeJsonFile)
    .catch(encodeError)
}

/*
 * Return the result of a game server query
 */
let doGameQuery = function (gameId, ip, queryPort) {
  return new Promise(function (resolve, reject) {
    return gameQuery({type: gameId, host: `${ip}:${queryPort} `},
      function (res) {
        if (res.error) reject('doGameQuery failed : ' + color.red(res.error))
        else resolve(res)
      })
  })
}

/*
 * Returns an array of doGameQuery funtion
 */
let buildGameQueries = function (gameServerJson) {
  let getIp = function () {
    return new Promise(function (resolve, reject) {
      let ip = gameServerJson.gameServerIp

      if (ip) {
        if (isIp(ip)) resolve(ip)
        else reject(color.yellow('Ip address badly formatted in json file.'))
      } else {
        reject(color.yellow('No ip found in json file.'))
      }
    })
  }

  let buildGameServerQueries = function (gameServerIp) {
    return new Promise(function (resolve, reject) {
      let ip = gameServerIp
      let games = gameServerJson.games
      let gameQueries = []

      if (Array.isArray(games)) {
        games.map(function (game) {
          let gameId = game.gameId
          if (gameId !== undefined) {
            if (Array.isArray(game.gameServers)) {
              game.gameServers.map(function (gameServer) {
                let gameQueryPort = gameServer.queryPort
                gameQueries.push(doGameQuery(gameId, ip, gameQueryPort))
              })
            } else {
              reject(color.yellow('gameServers not an array in json file'))
            }
          }
        })
        resolve(gameQueries)
      } else {
        reject(color.yellow('Games not an array in json file.'))
      }
    })
  }

  return getIp(gameServerJson)
    .then(buildGameServerQueries)
}

/*
 * Actually execute an array of game server query all at the same time.
 */
let doGameQueries = function (gameQueries) {
  return Promise.all(gameQueries)
}

/*
 * Build an array that put each game query result into is own game array
 */
let formatQueriesResult = function (gameServersQueriesResult) {
  let formatedQueriesResult = [[]]
  let previousGame = gameServersQueriesResult[0].query.type
  let i = 0

  gameServersQueriesResult.map(function (gameServerQuery) {
    // if the gameServerQuery.query.type changes
    // then push the next query in the next formatedQueriesResult position
    if (previousGame === gameServerQuery.query.type) {
      formatedQueriesResult[i].push({
        players: gameServerQuery.players.length,
        maxplayers: gameServerQuery.maxplayers
      })
    } else {
      i++
      previousGame = gameServerQuery.query.type
      formatedQueriesResult[i] = [{
        players: gameServerQuery.players.length,
        maxplayers: gameServerQuery.maxplayers
      }]
    }
  })
  return formatedQueriesResult
}

/*
 *  Build a json containing all the game server info to be used by the frontend
 */
let updateGameStatusJson = function (formatedQueriesResult) {
  let readServerMapAndSteamApi = function () {
    let promiseArray = [
      readJson(gameServerMap),
      readJson(gameServersSteamApiJson)
    ]

    return Promise.all(promiseArray)
  }

  let updateGameServersInfo = function (readServerMapAndSteamApiResult) {
    let gameServersInfo = readServerMapAndSteamApiResult[0]
    let gameServersSteamApi = readServerMapAndSteamApiResult[1].response.servers
    let onlineServersPorts = []

    // Populate the onlineServersPorts array
    // in order to detect which servers are online or not
    gameServersSteamApi.map(function (gameServerSteamApi) {
      onlineServersPorts.push(gameServerSteamApi.gameport)
    })

    let setOnlineStatus = function (gamePort) {
      let indexOfGamePort = onlineServersPorts.indexOf(gamePort)

      // log(onlineServersPorts.indexOf(27015))

      // if server can be found in the steam api call
      if (indexOfGamePort >= 0) {
        // if that server has a reject field AND
        // if that field is bad_version then set to updating
        if (gameServersSteamApi[indexOfGamePort].hasOwnProperty('reject')) {
          if (gameServersSteamApi[indexOfGamePort].reject === 'bad_version') {
            return 'updating'
          } else {
            return 'rejected'
          }
        } else {
          return 'join'
        }
      } else {
        return 'offline'
      }
    }

    for (let i = 0, l = gameServersInfo.games.length; i < l; i++) {
      let games = gameServersInfo.games
      for (let j = 0, l = games[i].gameServers.length; j < l; j++) {
        let gamePort = games[i].gameServers[j].port
        // Add a players fields return a string 'numberOfPlayers / maxplayers'
        games[i].gameServers[j].players = `${formatedQueriesResult[i][j].players} / ${formatedQueriesResult[i][j].maxplayers}`

        // Add a "status" field that returns a string : online, offline, updating or rejected
        games[i].gameServers[j].status = setOnlineStatus(gamePort)
      }
    }

    return gameServersInfo
  }

  return readServerMapAndSteamApi()
    .then(updateGameServersInfo)
}

let writeGameServerStatusJson = function (fileContent) {
  return writeJson(gameServerStatusJson, fileContent)
}

readJson(gameServerMap)
  .then(buildGameQueries)
  .then(doGameQueries)
  .then(formatQueriesResult)
  .then(updateGameStatusJson)
  .then(writeGameServerStatusJson)
  .catch(logError)

log('If you see me first congrats. This code is Asynchonous !')
