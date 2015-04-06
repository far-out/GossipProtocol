/**
 * Created by tmanhendy on 2/3/2015.
 */
function bodyController($scope, $state, $cookies, profileMaster){
    $scope.newUser = '';
    $scope.username = '';

    $scope.loggedInUser = {
        name: ''
    };
    $cookies.user = '';

    $scope.login = function(){
        // Only allow login if a valid username is entered
        profileMaster.query(function(data){
            for (var i=0;i<data.length;i++) {
                if (data[i].name == $scope.username) {
                    $cookies.user = $scope.username;

                    $scope.loggedInUser.name = $scope.username;

                    $scope.username = '';

                    $state.go('list',{},{reload:true});
                }
            }
        })
    };

    $scope.logout = function(){
        $cookies.user = '';
        $scope.loggedInUser.name = '';
    };

    $scope.createNewUser = function(){
        profileMaster.save({name: $scope.newUser},function(data){
            $scope.newUser = '';
        });
    };
}
