var guildgearprogress = angular.module('guildgearprogress', ['ngRoute','guildgearprogressControllers', 'guildgearprogressResources']);
var guildgearprogressControllers = angular.module('guildgearprogressControllers', ["chart.js"]);
var guildgearprogressResources = angular.module('guildgearprogressResources',[]);

/* filters */
guildgearprogress.filter('reverse', function() {
  return function(items) {
    if(items !== null && items.length > 1) {
      return items.slice().reverse();
    } 
    return [];
  };
});


/* configuration */
guildgearprogress.config(['$routeProvider',
  function($routeProvider) {
    $routeProvider.
      when('/teams', {
        templateUrl: 'views/team-overview.html',
        controller: 'TeamOverviewCtrl'
      }).
      when('/player/:name', {
        templateUrl: 'views/player.html',
        controller: 'PlayerCtrl'
      }).
      when('/team/:id', {
        templateUrl: 'views/team.html',
        controller: 'TeamCtrl'
      }).
      otherwise({
        redirectTo: '/teams'
      });
}]);

