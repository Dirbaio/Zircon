"use strict";

angular.module('app')

.controller('LoginCtrl', function($scope, ajax) {
    $scope.data = {};
    $scope.login = function() {
        ajax('/api/login', $scope.data);
    };
})
.controller('RegisterCtrl', function($scope, ajax) {
    $scope.data = {};
    $scope.register = function() {
        ajax('/api/register', $scope.data);
    };
});