guildgearprogressControllers.controller('PlayerCtrl', ['$scope', '$routeParams', 'teamResource', 'playerResource',
  function ($scope, $routeParams, teamResource, playerResource) {
    $scope.name = $routeParams.name;
    $scope.member = null;
    $scope.history = null;
    $scope.guildMembers = null;
    $scope.memberClass = null;
    $scope.radarLabels =["Haste", "Crit", "Multistrike", "Versatility", "Armor","Mastery"] //, "Leech", "Speed"];
    $scope.radarData = [
      []
    ];
    $scope.options = {
       scaleGridLineColor : "rgba(255,255,255,.2)",
       scaleLineColor: "rgba(255,255,255,.4)",
       scaleFontColor: "rgba(255,255,255,.8)",
       angleLineColor: "rgba(255,255,255,.8)",
       pointLabelFontColor: "rgba(255,255,255,.8)",
       pointDotRadius: 2,
       datasetStrokeWidth: 2
    };

    
    $scope.labels = [];
    $scope.series = [];
    $scope.data = [
      [],
      []
    ];

    loadView();

    function loadView() {
      loadPlayerData();
      loadTeamsData();
    }


    $scope.getItemQuality = function(item) {
      var RARE = 3;
      var EPIC = 4;
      quality = RARE;
      if(item.quality !== null && item.quality !== undefined) {
        quality = item.quality;
      }
      if(item.itemLevel > 640) {
        quality = EPIC;
      }

      return "qual"+quality;
    }

    function loadPlayerData() {
      // Calculate averages to be displayed on graphs
      // Get raiding team averages for a given timestamp
      playerResource.getPlayerHistory($scope.name).then(function(response) {
          var data = response.data;
          $scope.member = data.character;
          $scope.guildMembers = $scope.teams[$scope.teams.length-1]; // all team
          $scope.memberClass = data.character.class;
          $scope.history = data.history;

          $scope.series = [];
          $scope.series.push(data.character.name+" item level");
          $scope.series.push(data.character.name+" item level equipped");
          $scope.series.push("Raid team average");

          for(var i=0; i<$scope.history.length; i++) {
            var point = $scope.history[i];
            $scope.labels.push(point.formattedDate.replace("-", " "));
            $scope.data[0].push(point.averageItemLevel);
            $scope.data[1].push(point.averageItemLevelEquipped);
          }

          $scope.radarData[0].push(data.character.stats.hasteRating);
          $scope.radarData[0].push(data.character.stats.critRating);
          $scope.radarData[0].push(data.character.stats.multistrikeRating);
          $scope.radarData[0].push(data.character.stats.bonusArmor);
          $scope.radarData[0].push(data.character.stats.versatility);
          $scope.radarData[0].push(data.character.stats.masteryRating);

          // Get class/spec averages for current active spec
          //$scope.getCurrentSpecStatAverages();
          $scope.getCurrentTeamItemlevelAverages(data);
      });
    }

    $scope.getCurrentTeamItemlevelAverages = function(data) {
          // do nothing
          var timestamps = [];
          for(var i=0; i<$scope.history.length; i++) {
            var point = $scope.history[i];
            timestamps.push(point.timestamp);
          }

          var curScope = $scope;
          teamResource.getTeamHistory($scope.name, timestamps).then(function(response) {
              var xhrData = response.data.history;
              var graphData = [];
              var lastKnown = null;
              for(var i =xhrData.length-1;i>=0;i--) {
                var point = xhrData[i];
                
                if(point.averageItemLevelEquipped > 0) {
                  graphData[i] = point.averageItemLevelEquipped;
                  lastKnown = point.averageItemLevelEquipped;
                } else {
                  graphData[i] = lastKnown;
                }
              }

             curScope.data.push(graphData);
          });
      };

    function loadTeamsData() { 
      teamResource.getTeams().then(function(teams) {
        $scope.teams = teams;
      });
    }

    $scope.criteriaMatch = function( criteria ) {
      return function(item) {
        return item.class === criteria.class;
      };
    };

    $scope.onClick = function (points, evt) {
      console.log(points, evt);
    };
}]);
