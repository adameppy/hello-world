todoApp = angular.module('todoApp', ['ngRoute', 'phonecatAnimations',
  'phonecatControllers',
  'phonecatFilters',
  'phonecatServices',])
  .config(function($routeProvider) {
    $routeProvider
      .when('/', {
        templateUrl: '/partials/todo.html',
        controller: 'TodoCtrl'
      }).otherwise({
        redirectTo: '/'
      });
  });
