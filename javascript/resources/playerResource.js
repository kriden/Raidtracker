angular.module('guildgearprogressResources').factory('playerResource', ['$http',
	function($http) {

		_getPlayer = function(name) {
			return $http({
				url: "char.php?member="+name,
				method: "GET",
				cache: true
			});
		}

		_getPlayerHistory = function(name) {
			return $http({
				url: "char.php?history=true&member="+name,
				method: "GET",
				cache: true
			});
		}

		return {
            getPlayer: _getPlayer,
            getPlayerHistory: _getPlayerHistory
        }
	}
]);
