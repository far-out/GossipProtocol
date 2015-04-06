var gossipApp = angular.module('gossipApp',['ui.router','ngResource','ngCookies']);

gossipApp.config(function($stateProvider, $urlRouterProvider, $httpProvider) {
    //
    // For any unmatched url, redirect to /
    $urlRouterProvider.otherwise("/");
    //
    // Now set up the states
    $stateProvider
        .state('list', {
            url: "/",
            templateUrl: "partials/list.html",
            controller: listController,
            resolve: {
            }
        });

    // Use x-www-form-urlencoded Content-Type
    $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';

    // Override $http service's default transformRequest
    $httpProvider.defaults.transformRequest = [function(data)
    {
        if (data === undefined) return data;

        var clonedData = angular.copy(data);
        for (var property in clonedData)
            if (property.substr(0, 1) == '$')
                delete clonedData[property];

        return $.param(clonedData);
    }];
});

gossipApp.factory('profileMaster', function($resource){
    return $resource('scripts/profileHandler.php',{},
        { update: { method: 'PUT' } });
});

gossipApp.factory('messageMaster', function($resource){
    return $resource('scripts/loadMessages.php',{},
        { update: { method: 'PUT' } });
});

gossipApp.controller('bodyController', ['$scope', '$state', '$cookies', 'profileMaster', bodyController]);

gossipApp.run(function($rootScope){

});
