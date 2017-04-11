<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>FP API!</title>
    <script src="assets/js/app.js"></script>
    <script src="js/lib-fb.js"></script>
    <script src="js/ngApp.js"></script>
</head>
<body ng-app="ngApp">
<div ng-controller="albumCtrl">
    <button ng-click="loadData()">Get foto</button>
    <br>

    <div ng-repeat="item in albumsData" style="cursor:pointer;cursor:hand;display: inline-block;margin: 1px;"
         ng-click="openAlbum(item.id)">
        <img src="{{makeFacebookPhotoURL(item.cover_photo)}}" style="width: auto; height: 100px"/><br>
        {{item.name}}
    </div>
    <hr>
    <div ng-repeat="photo in albumPhotos" style="cursor:pointer;cursor:hand;display: inline-block;margin: 1px;">
        <img src="{{photo.url}}" style="width: auto; height: 100px"/><br>
    </div>
</div>
</body>
</html>


