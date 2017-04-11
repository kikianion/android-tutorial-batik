<?php
$this->load->helper('directory');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <link href="assets/css/app.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/gijgo.css" rel="stylesheet" type="text/css"/>

    <script src="assets/js/app.js" type="text/javascript"></script>
    <script src="assets/js/gijgo.js" type="text/javascript"></script>

    <script src="assets/js/ng-app.js" type="text/javascript"></script>
    <script src="assets/js/common.js" type="text/javascript"></script>

    <script src="assets/lib/fabric.js"></script>
    <script src="assets/js/desain.js"></script>

    <script src="assets/js/lib-fb.js"></script>
    <script src="assets/js/ngApp.js"></script>

<!--    <script src="http://code.gijgo.com/1.2.0/js/gijgo.js" type="text/javascript"></script>-->
<!--    <link href="http://code.gijgo.com/1.2.0/css/gijgo.css" rel="stylesheet" type="text/css"/>-->

    <style type="text/css">
        body {
            xbackground-color: #89cdef;
        }

        @media (min-width: 768px) {
            .navbar-nav {
                margin: 0 auto;
                display: table;
                table-layout: fixed;
                float: none;
            }
        }

        .border1 {
            border: 1px dashed black;
        }
    </style>

</head>
<body xng-app="tanimap">

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#">Logo</a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <li class="markStep markStep-1"><a href="javascript:btnStep(1)">Pilih Template</a></li>
                            <li class="markStep markStep-2"><a href="javascript:btnStep(2)">Buat Kalendar</a></li>
                            <li class="markStep markStep-3"><a href="javascript:btnStep(3)">Detail Pesanan</a></li>
                        </ul>
                    </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
            </nav>
        </div>
    </div>
    <div class="row">

        <div class="col-md-12">
            <div class="col-md-3">
                <div class="list-group">
                    <?php
                    $frames = directory_map('assets/images/frame', 1);
                    // $frames = array_map('basename', File::directories(public_path() . '/images/frame'));
                    foreach ($frames as $key => $val) {
                        if (strpos($val, "\\") !== false || strpos($val, "/") !== false) {
                            $val_=substr($val,0,strlen($val)-1);
                            ?>
                            <a href="javascript:listCatFrames('<?php echo $val_ ?>')"
                               id="cal-template-<?php echo $val_ ?>"
                               class="list-group-item cal-template-item"><?php echo $val_ ?></a>
                            <?php
                        }
                        ?>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="col-md-6" style="height: 600px">
                <div class="row" id="box-thumbs" style="xoverflow-y: scroll">

                </div>
                <div class="row" style="position: absolute; bottom: 0px; width: 100%">
                    <div style="text-align: center">
                        <button type="button" class="btn btn-primary" onclick="btnStep(++currentStep)">Selanjutnya
                        </button>

                    </div>
                </div>

            </div>
            <div class="col-md-3" id="box-pages">

            </div>
        </div>
    </div>
</div>


<div id="tmpl-thumb-cat" style="display: none;">
    <div class="col-md-4">
        <a href="javascript:listFramePage('==::item::==','==::page::==')" class="thumbnail thumb-all"
           id="==::item::==-==::page::=="
           onclick="setActive('==::item::==','==::page::==')">
            <img src="http://www.w3schools.com/bootstrap/pulpitrock.jpg" alt="Pulpit Rock"
                 style="width:150px;height:150px">

            <p style="text-align: center; font-weight: bold">==::page::==</p>
        </a>
    </div>
</div>


<div id="tmpl-thumb-page" style="display: none;">
    <div class="col-md-6" style="padding: 3px">
        <a href="#" class="thumbnail" style="width: 100%; margin:0px">
            <img src="assets/images/frame/==::cat::==/==::page::==/==::name::==" alt="#"
                 id="page-==::id::==" style="width:100%;height:auto " onclick="loadPage('page-==::id::==')">
        </a>
    </div>

</div>

<div id="editor-page" style="display: none">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9 xborder1" id="canvas-div" style="padding: 0;">
                <canvas id="c-==::idrandom::==" style="border:1px solid black; "></canvas>
            </div>
            <div class="col-md-3 xborder1" style="padding: 3px;">
                <div class="btn-group-vertical" role="group" aria-label="...">
                    <input type="file" id="imgLoader">
                    <button type="button" class="btn btn-default" onclick="openUploadImg()">Upload Gambar</button>
                    <button type="button" class="btn btn-default">Tambah Text</button>
                    <button type="button" class="btn btn-default">Tambah Art</button>
                    <button type="button" class="btn btn-default" onclick="openSave()">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    $('#myTabs a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
    })

    var dlg_uploadimg = $("#dlg-uploadimg").html();
    //    $("#dlg-uploadimg").html("");
</script>


<div id="dialog1" title="Resizable Bootstrap Modal" width="650" style="display: none">
    <div data-role="body">
        <div style="xdisplay: none" id="dlg-uploadimg">
            <div>

                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist" id="myTabs">
                    <li role="presentation" class="active">
                        <a href="#home" aria-controls="home" role="tab" data-toggle="tab">Upload Foto</a>
                    </li>
                    <li role="presentation">
                        <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Foto Tersimpan</a>
                    </li>
                    <li role="presentation">
                        <a href="#tab3" role="tab" data-toggle="tab">Facebook</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="home">...</div>
                    <div role="tabpanel" class="tab-pane" id="profile">ljlkjkl...</div>
                    <div role="tabpanel" class="tab-pane" id="tab3">
                        <div ng-app="ngApp" ng-controller="albumCtrl">
                            <button ng-click="loadData()">Get foto</button>
                            <br>

                            <div ng-repeat="item in albumsData"
                                 style="cursor:pointer;cursor:hand;display: inline-block;margin: 1px;"
                                 ng-click="openAlbum(item.id)">
                                <img src="{{makeFacebookPhotoURL(item.cover_photo)}}"
                                     style="width: auto; height: 100px"/><br>
                                {{item.name}}
                            </div>
                            <hr>
                            <div ng-repeat="photo in albumPhotos"
                                 style="cursor:pointer;cursor:hand;display: inline-block;margin: 1px;">
                                <img src="{{photo.url}}" style="width: auto; height: 100px"/><br>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <div data-role="footer">
        <button class="btn" data-role="close">Cancel</button>
        <button class="btn" onclick="dialog1.hide()">OK</button>
    </div>
</div>

</body>
</html>
