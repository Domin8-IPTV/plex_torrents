app.controller('TorrentController', function($scope, $interval, $http, $sce, API) {

    toggleSideBar();
    getDownloads();
    $scope.interval = 90000;
    $scope.colorState = "danger";

    $interval(function() {
            getDownloads();
        },
        $scope.interval);

    API.getNavBar().success(function(response) {
        $scope.NavBar = $sce.trustAsHtml(response);
    });

    API.getSideBar().success(function(response) {
        $scope.SideBar = $sce.trustAsHtml(response);
    });

    function getDownloads() {
        API.getTimeStamp().success(function(response) {

            if (response == '') {

                returnText = `<span>No items are currently downloading</span>`;
                $scope.Interval = 90000;

            } else {

                returnText = `<span> Updates Every 2 Minutes, Last Updated: ${response["ts"]} </span>`;
                $scope.Interval = 45000;

                API.getProgress().success(function(response) {
                    $scope.Bars = response;
                });
            }
            $scope.Status = $sce.trustAsHtml(returnText);
        });
    }

    $scope.buttonSubmit = function(link_type) {
        var component = $('input#' + link_type);
        var magnet = component.val();
        var data = {
            "link": magnet,
            'link_type': link_type
        };
        $http.post("./api/php/insert_torrent", data).success(function(data, status) {
            component.val("").attr("placeholder", "Link has been added");
        });
    }

    function toggleSideBar() {
        var screenWidth = $(window).width();
        if (screenWidth < 500) {
            $("#wrapper").toggleClass("toggled");
        }
    }
});
