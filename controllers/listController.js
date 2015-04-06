/**
 * Created by tmanhendy on 2/1/2015.
 */

function listController($scope, $state, $interval, $http){
    $scope.vars = {
        formattedMessages: '',
        comment: ''
    };

    if ($scope.loggedInUser.name){
        $interval(function(){
            $http.get("scripts/loadMessages.php").success(function(data){
                $scope.vars.formattedMessages = '';
                for (var i=0;i<data.length;i++){
                    $scope.vars.formattedMessages += data[i].Originator+": \t"+data[i].Text+"\n";
                }
            });
        }, 1000);

        $interval(function(){
            $http.post("scripts/propogate.php",{});
        }, 1000);
    }

    $scope.newPeer = {
        url: ''
    };

    $scope.addNewPeer = function(){
        if (!$scope.newPeer.url){
            return;
        }

        $http.post('scripts/addPeer.php',$scope.newPeer).success(function(response){
            $scope.newPeer.url = '';
        });
    };

    $scope.submitComment = function(){
        $http.post('scripts/submitMessage.php',{"comment":$scope.vars.comment}).success(function(response){
            $scope.vars.comment = '';
        });
    };
}
