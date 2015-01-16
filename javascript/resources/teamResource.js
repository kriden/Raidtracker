angular.module('guildgearprogressResources').factory('teamResource', ['$rootScope', '$http', 'playerResource', function($rootScope, $http, playerResource) {
  var teamList = [];
  var $http = $http;

  function _addTeam(newObj) {
      teamList.push(newObj);
  }

  function _getRole(role) {
    var role = capitalize(role);
    if(role === "Dps") {
      return "Damage";
    } else if (role == "Healing"){
      return "Healer";
    }
    return role;
  }

  function _loadTeams() {
      var playerIndex = 0;

      for(playerIndex=0; playerIndex<100; playerIndex++) {
        for(teamIndex=0; teamIndex < teamList.length-1; teamIndex++) {
            _loadMember(teamList[teamIndex], teamList[teamList.length-1], playerIndex);
        }
      }
  }

  function _getTeams() {
    return $http({
        url: "teams.php",
        method: "GET",
        cache: true
      }).then(function(response) {
        teamList = response.data;
        var generalTeam = {
          "name": "All",
          "members": []
        };

        // add a general team
        teamList.push(generalTeam);
        _loadTeams();
        return teamList;
     });
  }

  function _loadMember(team, generalTeam, playerIndex) {
    if(team.members === undefined || team.members === null) team.members = [];

    var playerName = team.memberNames[playerIndex];
    if(playerName !== undefined && playerName !== null) {
        playerResource.getPlayer(playerName).then(function(response) {
          var data = response.data;

          if(data !== undefined && data !== null && data.name != "") {
            var ilvl = parseInt(data.itemLevel);
            if(ilvl > 0 /*&& lvl == 100*/) {
              data.role = _getRole(data.specRole);
              data.altRole = _getRole(data.altSpecRole);
              team.members.push(data);
              generalTeam.members.push(data);
              _calcAverages();
            }
          }
      });
    } else {
    }
  }

  function _calcAverages() {
      for(var t=0; t<teamList.length; t++) {
        var avg=0,min=null,max=0;
        var $team = teamList[t];
        
        for(var m=0; m<$team.members.length; m++) {
          var ilvl = $team.members[m].itemLevel;
          avg += ilvl;

          if(ilvl<min || min == null) {
            min = ilvl;
          }

          if(ilvl>max) {
            max = ilvl;
          }
        }
        if(min == null) {
          min = 0;
        }

        $team.averageItemLevel = Math.floor(avg/$team.members.length);
        $team.lowestItemLevel = min;
        $team.highestItemLevel = max;
      }
  }


    _getTeamHistory = function(name, timestamps) {
      return $http.get("teams.php?member="+name+"&averages="+timestamps.join(","));
    }

    _getTeamsHistory = function() {
      return $http.get("teams.php?averages=auto");
    }

    _getTeamById = function(id) {
      return _getTeams().then(function(teams) {
        for(var i =0; i<teams.length; i++) {
          if(teams[i].id === id) {
            return teams[i];
          }
        }
        return null;
      });
    }

  return {
    getTeams: _getTeams,
    getTeamById: _getTeamById,
    getTeamHistory: _getTeamHistory,
    getTeamsHistory: _getTeamsHistory
  };

}]);