guildgearprogress.service('teamService', function($http) {
  var teamList = [];
  var $http = $http;

  var addTeam = function(newObj) {
      teamList.push(newObj);
  }

  var getRole = function(role) {
    var role = capitalize(role);
    if(role === "Dps") {
      return "Damage";
    } else if (role == "Healing"){
      return "Healer";
    }
    return role;
  }

  var loadTeams = function() {
      var playerIndex = 0;

      for(playerIndex=0; playerIndex<100; playerIndex++) {
        for(teamIndex=0; teamIndex < teamList.length-1; teamIndex++) {
            loadMember(teamList[teamIndex], teamList[teamList.length-1], playerIndex);
        }
      }
  }

  var setTeams = function(newObj) {
      teamList = newObj;
  }

  var promise = $http.get('teams.php').success(function(data) {
        teamList = data;
        var generalTeam = {
          "name": "All",
          "members": []
        };

        // add a general team
        teamList.push(generalTeam);
        loadTeams();
        console.warn('Completed promise success!!');
   });

  var getTeams = function() {
    return teamList;
  }

  var loadMember = function(team, generalTeam, playerIndex) {
    if(team.members === undefined || team.members === null) team.members = [];
      var playerName = team.memberNames[playerIndex];
      if(playerName !== undefined && playerName !== null) {
        $http.get("char.php?member="+playerName).success(function(data) {
          if(data.name != "") {
            var ilvl = parseInt(data.itemLevel);
            if(ilvl > 0 /*&& lvl == 100*/) {
              data.role = getRole(data.specRole);
              data.altRole = getRole(data.altSpecRole);
              team.members.push(data);
              generalTeam.members.push(data);
              calcAverages();
            }
          }
      });
    } else {
    }
  }

  var calcAverages = function() {
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

  return {
    promise:promise,
    addTeam: addTeam,
    setTeams: setTeams,
    getTeams: getTeams
  };

});