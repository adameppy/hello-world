todoApp.factory('todosFactory', function($http) {
  var urlBase = '/api/todos';
  var _todoService = {};

  _todoService.getTodos = function() {
    return $http.get(urlBase);
  };
  
  _todoService.saveTodo = function(todo) {
    return $http.post(urlBase, todo);
  };

  _todoService.updateTodo = function(todo) {
    return $http.put(urlBase, todo);
  };

  _todoService.deleteTodo = function(id) {
    return $http.delete(urlBase + '/' + id);
  };
/*
  _todoService.getFollowedTodos = function(user){
    return $http.post(urlBase+'/following', user); 
  };
  */
  return _todoService;
});

todoApp.factory('usernameFactory', function($http){
  var urlBase = '/api/usernames';
  var _usernameService = {};
	
  _usernameService.getUsernames = function() {
	return $http.get(urlBase);
  };
  
  _usernameService.register = function(user) {
  	return $http.post(urlBase, user);
  };
	
  _usernameService.checkUsername = function(user){
	//alert("Base  came through as: " + urlBase + '/' + user);
	return $http.get(urlBase + '/' + user);
  };
  
  _usernameService.updatePic = function(user){
	  return $http.put(urlBase, user);
  };
  /*
  _usernameService.follow = function(users){
    return $http.router.get('/api/todos/nowFollowing/'+users.Me+'/'+users.ToFollow);
  };
  
  
  usernameFactory.getWhoFollowing = function(users){
    return $http.router.get(urlBase+'/getWhoFollowing/'+users.name);
  }
  
  
  */
  return _usernameService;
});
