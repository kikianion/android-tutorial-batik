<?php
namespace aneon\fw\page;

//setting
$pageParams = array(
    'title' => "Cek Stok",
    'menuGroup' => "Analisis",
    'ds' => "contact_log",
    'order' => 3,
    "openNewWindow" => 1,
    "customUrl" => "page/cek_stok_view",
);

//init
$pageInfo = array(
    'filename' => basename(__FILE__, '.php'),
)
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <title>ANEON MANAGER - Cek Stok</title>
    <script type="text/javascript" src="../_libs/angular-1.4.4/angular.js"></script>


</head>

<body>

<style>
    table,
    th,
    td {
        border: 1px solid grey;
        border-collapse: collapse;
        padding: 5px;
    }

    table tr:nth-child(odd) {
        xbackground-color: #f1f1f1;
    }

    table tr:nth-child(even) {
        xbackground-color: #ffffff;
    }
</style>
<div ng-app="cekStok" ng-controller="cekStokController">
    <h2 style="display: inline-block">Cek stok akhir</h2> sampai {$ daysCheck $} hari yang lalu
    <div id="divButtons">
        <button ng-click="loadData(2)">2 hyl</button>
        |
        <button ng-click="loadData(3)">3 hyl</button>
        |
        <button ng-click="loadData(4)">4 hyl</button>
        |
        <button ng-click="loadData(5)">5 hyl</button>
        |
        <button ng-click="loadData(6)">6 hyl</button>
        {$ statInfo $}
    </div>
    <br>

    <div>
        <table style="width: 100%">
            <tr style="font-weight: bold; background-color: #888888">
                <td style="min-width: 100px">nama</td>
                <td style="min-width: 80px">sisa</td>
                <td style="min-width: 120px">tgl deteksi</td>
                <td>catatan</td>
            </tr>
            <tr ng-repeat="x in detects">
                <td>{$ x.name $}</td>
                <td>{$ x.last_ $}</td>
                <td>{$ UTCtoJakarta(x.created) $}</td>
                <td></td>
            </tr>
        </table>

    </div>
</div>
<script src="../_libs/jquery/jquery-1.10.2.min.js"></script>

<script type="text/javascript" src="../_libs/alasql-0.2/alasql.min.js"></script>
<script data-jsfiddle="common" src="../_libs/handsontable-0.18.0/dist/moment/moment.js"></script>
<script data-jsfiddle="common" src="../_libs/handsontable-0.18.0/dist/moment/moment-timezone-with-data.js"></script>
<script data-jsfiddle="common" src="../_libs/handsontable-0.18.0/dist/moment/locale/id.js"></script>
<script type="text/javascript" src="../js/app.js"></script>
<script type="text/javascript" src="../js/app-cekstok.js"></script>
</body>

</html>