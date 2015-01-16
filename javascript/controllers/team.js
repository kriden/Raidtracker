guildgearprogressControllers.controller('TeamCtrl', ['$scope', '$routeParams', 'teamResource',
  function ($scope, $routeParams, teamResource) {
  	$scope.teamRepresentative = [
  		'Kraator',
  		'Greenthy',
  		'Ihavehotdots'
  	];

    // Item levels
    $scope.ilvlSeries = [];
    $scope.ilvlData = [
      [],[],[]
    ];
    $scope.ilvlLabels = [];

  	// Role Distribution
  	$scope.roleData = [5,6,12];
  	$scope.roleLabels = ["Tank", "Healer", "Damage"];
  	$scope.roleOptions = {
  		animationEasing: "none",
  		animateRotate: false
  	};

  	// Class Distribution
  	$scope.classData =[0.1,0.1,0.1,0.1,0.1,0.1,0.1,0.1,0.1,0.1,0.1];
  	$scope.classLabels = ["Warrior", "Paladin","Hunter","Rogue","Priest","Death Knight","Shaman","Mage","Warlock","Monk","Druid"];
    $scope.classColours = Chart.defaults.global.classColours;

  	$scope.tank = {
  		itemLevel: 0,
  		averageItemLevel: 0,
  		members: []
  	};
  	$scope.healer = {
  		itemLevel: 0,
  		averageItemLevel: 0,
  		members: []
  	};;
  	$scope.damage = {
  		itemLevel: 0,
  		averageItemLevel: 0,
  		members: []
  	};;

  	$scope.id = $routeParams.id;

  	teamResource.getTeamById($routeParams.id).then(function(team) {
  		$scope.team = team;
      $scope.$watch("team.members.length", divideTeamsIntoRoles);
  	});

  	// create timestamp for last 9 weeks
   	teamResource.getTeamsHistory().then(function(response) {
      var data = response.data;
      console.log(data);
      for(var t=0; t<data.length; t++){
        var team = data[t];

        var history = team.history;
        $scope.ilvlSeries.push(team.name);
        for(var h=0; h<history.length; h++) {
          $scope.ilvlData[t].push(history[h].averageItemLevelEquipped);
          
          if($scope.ilvlLabels[h] === undefined || $scope.ilvlLabels[h] === null) {
            $scope.ilvlLabels[h] = history[h].formattedDate;
          }
        }
      }
  	});


  	function divideTeamsIntoRoles() {
      var specData = [];
      console.info("Teams members size increased, calculating...");
  		for(var m =0; m<$scope.team.members.length; m++) {
  			var member = $scope.team.members[m];
  			if(member.specActive) {
  				addMemberToRole(member.role, member);
  			}
  			if(member.altSpecActive) {
  				addMemberToRole(member.altRole, member);
  			}

        specData = getClassData(member, specData);
  		}
      //$scope.classData = specData;
  	}

    function getClassData(member, specData) {
        
        return specData;
    }

  	function addMemberToRole(role, member) {
  		var role = role.toLowerCase();
      var classDataIndex = ["Warrior", "Paladin","Hunter","Rogue","Priest","Death Knight","Shaman","Mage","Warlock","Monk","Druid"];
  		var roleDataIndex = ["tank", "healer", "damage"];
  		if(!$scope[role].members.contains(member)) {
  			$scope[role].members.push(member);
  			$scope[role].itemLevel += member.itemLevel;
 			  $scope[role].averageItemLevel = Math.floor($scope[role].itemLevel/$scope[role].members.length);

 			  $scope.roleData[roleDataIndex.indexOf(role)] = $scope[role].members.length;

        $scope.classData[classDataIndex.indexOf(member["class"])] = Math.floor($scope.classData[classDataIndex.indexOf(member["class"])]+1);
      }
  	}
  }
 ]);