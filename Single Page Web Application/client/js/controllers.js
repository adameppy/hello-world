todoApp.controller('TodoCtrl', function($rootScope, $scope, todosFactory, usernameFactory, $interval) {

  $scope.todos = [];
  $scope.isEditable = [];
  $scope.hasUsername = false;
  $scope.users = [];

  

  
  
  // Sign in or Register if Username doesn't exit
  $scope.signin = function($event) {
	  if ($event.which == 13 && $scope.nameInput && $scope.pwdInput) {
		usernameFactory.checkUsername($scope.nameInput).then(function(data){
		var user = data.data.username;
		//alert("user is " + user);
		if(user == null){
			usernameFactory.register({
				"username": $scope.nameInput,
				"pwd": $scope.pwdInput,
				"picture": 'http://showdown.gg/wp-content/uploads/2014/05/default-user.png'
			});
			$scope.username = $scope.nameInput;
			$scope.password = $scope.pwdInput;
			$scope.hasUsername = true;
			$scope.picture = 'http://showdown.gg/wp-content/uploads/2014/05/default-user.png';
			
		}
		else{
			if(data.data.username == $scope.nameInput && data.data.pwd == $scope.pwdInput){
				$scope.username = $scope.nameInput;
				$scope.hasUsername = true;
				$scope.picture = data.data.picture;
			}
			else{
				alert("Password Incorrect");
			}
			
		}
	  });
	 }
  };
  

  // Save a Todo to the server
  $scope.save = function($event) {
    if ($event.which == 13 && $scope.todoInput) {

      todosFactory.saveTodo({
        "todo": $scope.todoInput,
        "name": $scope.nameInput,
		"date": new Date()
      }).then(function(data) {
        $scope.todos.push(data.data);
      });
      $scope.todoInput = '';
	   todosFactory.getTodos().then(function(data) {
		$scope.todos = data.data.reverse();
		});
		$scope.refresh();
    }

  };

  //update the status of the Todo
  $scope.updateStatus = function($event, _id, i) {
    var cbk = $event.target.checked;
    var _t = $scope.todos[i];
    todosFactory.updateTodo({
      _id: _id,
      isCompleted: cbk,
      todo: _t.todo
    }).then(function(data) {
      if (data.data.updatedExisting) {
        _t.isCompleted = cbk;
      } else {
        alert('Oops something went wrong!');
      }
    });
  };

  // Update the edited Todo
  $scope.edit = function($event, i) {
    if ($event.which == 13 && $event.target.value.trim() && $scope.todos[i].name == $scope.username) {
      var _t = $scope.todos.slice()[i];
      todosFactory.updateTodo({
        _id: _t._id,
		//can only edit things that are your username
		name: $scope.username,
        todo: $event.target.value.trim(),
        isCompleted: _t.isCompleted
      }).then(function(data) {
              console.log(data);
        if (data.data.updatedExisting) {
          _t.todo = $event.target.value.trim();
		  _t.name = $scope.username;
          $scope.isEditable[i] = false;
        } else {
          alert('Oops something went wrong!');
        }
      });
    }
  };

  // Delete a Todo
  $scope.delete = function(i) {
		todosFactory.deleteTodo($scope.todos.slice()[i]._id).then(function(data) {
		var length = $scope.todos.length;
		if (data.data) {
			$scope.todos.splice(i, 1);
		}
		});
  };
  
  $scope.refresh = function(){
		todosFactory.getTodos().then(function(data) {
			$scope.todos = data.data.reverse();
			// get all Usernames that exist
			usernameFactory.getUsernames().then(function(data) {
				$scope.users = data.data;
				for(var i = 0; i<$scope.todos.length; i++){
					var re = /#\w+/;
					for(var j = 0; j<$scope.users.length; j++){
						if($scope.todos[i].name == $scope.users[j].username){
							$scope.todos[i].picture = $scope.users[j].picture;
						}	
					}
				}
			});

	
		
		});
  };
  
  $scope.changePic = function($event){
	  if ($event.which == 13 && $scope.picInput) {
		  usernameFactory.updatePic({
			  name: $scope.username,
			  pic: $scope.picInput
		  });
		  
		  $scope.picture = $scope.picInput;
		  $scope.refresh();
		  $scope.picInput = "";
	  }
		  
  };
  /*
  $scope.follow = function(userToFollow){
	todosFactory.follow({
		Me: $scope.username,
		ToFollow: userToFollow
	}).then(function(data){
		$scope.refresh();	
	});
  }
  
  $scope.loadFollowedTodos = function(){
	$scope.username;
	
	usernameFactory.getWhoFollowing({
		name: $scope.username
	}).then(function(data){
		$scope.whoFollowing = data.data;
		/*
		for (var i = 0; i<$scope.whoFollowing.length; ++i){
			todosFactory.getUserTodos({
				name: $scope.whoFollowing[i]
			}).then(function(data){
				$scope.todos.push(data.data.reverse());
				// get all Usernames that exist
				usernameFactory.getUsernames().then(function(data) {
					$scope.users.push(data.data);
					for(var i = 0; i<$scope.todos.length; i++){
						var re = /#\w+/;
						for(var j = 0; j<$scope.users.length; j++){
							if($scope.todos[i].name == $scope.users[j].username){
								$scope.todos[i].picture = $scope.users[j].picture;
							}	
						}
					}
				});
			});
		}
		
	});
	
  }
  
  $scope.loadFollowedTodos = function(){
		usernameFactory.checkUsername($scope.username).then(function(data){
		var user = data.data.username;
		//alert("user is " + user);
		if(user == null){
			alert("Not logged in! Please log in to continue.")
		}
		else{
			if(data.data.username == $scope.nameInput && data.data.pwd == $scope.pwdInput){
				//Yes we are logged in and passed the security check
				
				todosFactory.getFollowedTodos({
				name: $scope.username,
				password: $scope.password
				}).then(function(data) {
					$scope.todos = data.data.reverse();
					// get all Usernames that exist
					usernameFactory.getUsernames().then(function(data) {
						$scope.users = data.data;
						for(var i = 0; i<$scope.todos.length; i++){
							var re = /#\w+/;
							for(var j = 0; j<$scope.users.length; j++){
								if($scope.todos[i].name == $scope.users[j].username){
									$scope.todos[i].picture = $scope.users[j].picture;
								}	
							}
						}
					});
				});
				
			}else{
				alert("Password Incorrect");
			}
			
		}
	  });
	
  }
  */
    // get all Todos on Load
	$scope.refresh();

	$interval(function(){
		//We have the page refresh every second
		$scope.refresh();
	},10000);
	
});
