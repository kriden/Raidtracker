guildgearprogressControllers.controller('TeamOverviewCtrl', ['$scope', 'teamResource', function ($scope, teamResource) {
	$scope.predicate = "-itemLevel";
	$scope.armorType = "";
	$scope.armorToken = "";
	$scope.teamId = "";

	teamResource.getTeams().then(function(teams) {
		$scope.teams = teams;
	})
}]); 