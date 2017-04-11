/**
 * Created by super1 on 028, 28/05/2016.
 */

//settings
var outDateFormat = 'YYYY-MM-DD';

//ng-app
var ngApp = angular.module('aneon-manager', ['ngRoute', 'ngAnimate', 'ngSanitize']);

// common
function resolveFunc($q, $rootScope, $location) {
    var defer = $q.defer();
    $("#ngview").html('<section class="content-header"><h1>' +
        'Loading...<small></small></h1>' +
        '<ol class="breadcrumb">' +
        '<li><a href="#"><i class="fa fa-dashboard"></i></a></li>' +
        '<li class="active"></li></ol></section> ');
    defer.resolve();
    return defer.promise;
}

//if detected login box inside page then reload
function reloadIfNotAuth() {
    if (typeof loginPage != 'undefined') {
        window.location = "?r=" + Math.random();
    }
}

ngApp.config(function($routeProvider, $locationProvider, $httpProvider) {
    $routeProvider.when('/page/:path_module/:title', {
        templateUrl: function(params) {
            document.title = params.title+ " ";

            return "page/" + params.path_module + "?r=" + Math.random();
        },
        controller: 'modController',
        resolve: {
            app: function($q, $rootScope, $location) {
                return resolveFunc($q, $rootScope, $location);
            }
        }
    }).otherwise({
        redirectTo: '/page/home/Beranda'
    });

    $httpProvider.interceptors.push('responseObserver');
});

ngApp.factory('responseObserver', function responseObserver($q, $window) {
    return {
        'response': function(response) {
            // debugger;

            if (response.data && response.data.indexOf('access-denied') != -1) {
                // response.data="<script type='text/javascript'>alert('Sesi
                // login telah habis, silahkan login
                // kembali');window.location='_auth/logout.php';</script>"+response.data;
            }

            return response;
        },
        'responseError': function(errorResponse) {
            // debugger;
            switch (errorResponse.status) {
                case 403:
                    // $window.location = './403.html';
                    break;
                case 500:
                    // $window.location = './500.html';
                    break;
            }
            return $q.reject(errorResponse);
        }
    };
});

function repbooking_ctrl($rootScope, $scope) {
    $scope.getTrxBooking = function() {
        var year = $scope.pickYear;
        var month = $scope.pickMonth;
        $.get("?page=repbooking&ds=1&f=getTrxBooking&month=" + month + "&year=" + year, null, null, "json")
            .done(function(data) {
                $scope.$apply(function() {
                    $scope.trxBookingYearMonth = data.data;
                });
            });
    }

    $scope.exportExcel = function() {
        var a = [
            ['#', 'Tanggal', 'Jenis Transaksi', 'No Induk', 'Sub Kode Sampah', 'Vol', 'Harga/Unit', 'Total', 'Saldo Akhir']
        ];
        var d = $scope.trxBookingYearMonth;

        for (var i = 0; i < d.length; i++) {
            var o = d[i];
            //debugger;
            var subcatprodname_ = o.subcatprodname == null ? "" : o.subcatprodname;
            var citizenname_ = o.citizenname == null ? "" : o.citizenname;
            a.push([(i + 1), o.dt, o.dkbyworker, o.citizenid, o.subcatprod, o.vol, o.priceperunit, o.amount, o.balance]);
        }
        export_excel(a);

    }

    return false;
}

function repcitizen_ctrl($rootScope, $scope) {
    var currentYear;

    $scope.exportExcel = function() {
        var a = [
            ['#', 'Bulan', 'Nasabah', 'Sub Kategori Sampah', 'Total Vol', 'Total Rp', 'N x Trx']
        ]
        var d = $scope.trxCitizensYearMonth;

        for (var i = 0; i < d.length; i++) {
            var o = d[i];
            //debugger;
            var subcatprodname_ = o.subcatprodname == null ? "" : o.subcatprodname;
            var citizenname_ = o.citizenname == null ? "" : o.citizenname;
            a.push([(i + 1), o.month + "-" + currentYear, o.citizenid + "-" + citizenname_, o.subcatprod + '-' + subcatprodname_,
                o.jmlvol, o.jmlrp, o.jmlrec
            ]);
        }
        export_excel(a);

    }

    $scope.getRepCitizen = function() {
        currentYear = $scope.pickYear;
        var month = $scope.pickMonth;

        $scope.trxCitizensYearMonth = [];

        if (month == 'Semua') {
            var mon_ = 1;

            function calcAllMonth(mon_) {
                $.get("?page=repcitizen&ds=1&f=getRepCitizen&month=" + mon_ + "&year=" + currentYear, null, null, "json")
                    .done(function(data) {
                        $scope.$apply(function() {
                            for (var i = 0; i < data.data.length; i++) {
                                data.data[i].month = mon_;
                                $scope.trxCitizensYearMonth.push(data.data[i]);
                            }
                            mon_++;
                            if (mon_ <= 12) {
                                calcAllMonth(mon_);
                            } else {
                                $scope.trxCitizensYearMonth = summarizeRepCitizens($scope.trxCitizensYearMonth);
                                return;
                            }
                        });
                    });
            }

            calcAllMonth(mon_);
        } else {
            $.get("?page=repcitizen&ds=1&f=getRepCitizen&month=" + month + "&year=" + currentYear, null, null, "json")
                .done(function(data) {
                    $scope.$apply(function() {
                        for (var i = 0; i < data.data.length; i++) {
                            data.data[i].month = month;
                            $scope.trxCitizensYearMonth.push(data.data[i]);
                        }
                        $scope.trxCitizensYearMonth = summarizeRepCitizens($scope.trxCitizensYearMonth);
                    });
                });
        }
    }

}

function repsubcat_ctrl($rootScope, $scope) {

    var currentYear;

    $scope.exportExcel = function() {
        var a = [
            ['#', 'Bulan', 'Kode Sampah', 'Total Vol', 'Total Rp']
        ]
        var d = $scope.trxBookingYearMonth;

        for (var i = 0; i < d.length; i++) {
            var o = d[i];
            //debugger;
            a.push([(i + 1), o.month + "-" + currentYear, o.subcatprod, o.jmlvol, o.jmlrp]);
        }
        export_excel(a);

    }

    $scope.getRepSubcat = function() {
        currentYear = $scope.pickYear;
        var month = $scope.pickMonth;

        $scope.trxBookingYearMonth = [];

        if (month == 'Semua') {
            var mon_ = 1;

            function calcAllMonth(mon_) {
                $.get("?page=repsubcat&ds=1&f=getRepSubcat&month=" + mon_ + "&year=" + currentYear, null, null, "json")
                    .done(function(data) {
                        $scope.$apply(function() {
                            for (var i = 0; i < data.data.length; i++) {
                                data.data[i].month = mon_;
                                $scope.trxBookingYearMonth.push(data.data[i]);
                            }
                            mon_++;
                            if (mon_ <= 12) {
                                calcAllMonth(mon_);
                            } else {
                                return;
                            }
                        });
                    });
            }

            calcAllMonth(mon_);
        } else {
            $.get("?page=repsubcat&ds=1&f=getRepSubcat&month=" + month + "&year=" + currentYear, null, null, "json")
                .done(function(data) {
                    for (var i = 0; i < data.data.length; i++) {
                        data.data[i].month = month;
                    }
                    $scope.$apply(function() {
                        $scope.trxBookingYearMonth = data.data;
                    });
                });
        }
    }
}

function admin_repadmin_unittrx_ctrl($rootScope, $scope, $routeParams) {
    var currentYear;
    var moduleName = $routeParams.path_module;
    $scope.getAdminRepCitizen = function() {
        $scope.ui.btnRepProsesLabel = "Memproses...";
        $scope.ui.btnRepProsesDisabled = true;

        currentYear = $scope.pickYear;
        var month = $scope.pickMonth;

        $scope.trxCitizensYearMonth = [];

        if (month == 'Semua') {
            var mon_ = 1;

            function calcAllMonth(mon_) {
                $.get("?page=" + moduleName + "&ds=1&f=getAdminRepSeller&month=" + mon_ + "&year=" + currentYear,
                        null, null, "json")
                    .done(function(data) {
                        $scope.$apply(function() {
                            // debugger;
                            for (var i = 0; i < data.data.length; i++) {
                                data.data[i].month = mon_;
                                $scope.trxCitizensYearMonth
                                    .push(data.data[i]);
                            }
                            mon_++;
                            if (mon_ <= 12) {
                                calcAllMonth(mon_);
                            } else {
                                $scope.trxCitizensYearMonth = summarizeRepSellers($scope.trxCitizensYearMonth);
                                $scope.ui.btnRepProsesLabel = "Proses";
                                $scope.ui.btnRepProsesDisabled = false;
                                return;
                            }
                        });
                    });
            }

            calcAllMonth(mon_);
        } else {
            $.get("?page=" + moduleName + "&ds=1&f=getAdminRepSeller&month=" + month + "&year=" + currentYear,
                    null, null, "json")
                .done(function(data) {
                    $scope.$apply(function() {
                        for (var i = 0; i < data.data.length; i++) {
                            data.data[i].month = month;
                            $scope.trxCitizensYearMonth.push(data.data[i]);
                        }
                        $scope.trxCitizensYearMonth = summarizeRepSellers($scope.trxCitizensYearMonth);
                        $scope.ui.btnRepProsesLabel = "Proses";
                        $scope.ui.btnRepProsesDisabled = false;
                    });
                });
        }
    }

    $scope.exportExcel = function() {
        var a = [
            ["#", "Bulan", "Unit Bank", "Sub Kode Sampah", "Total Vol", "Total Rp", "N x Trx"]
        ];
        var d = $scope.trxCitizensYearMonth;

        for (var i = 0; i < d.length; i++) {
            var o = d[i];
            //debugger;
            var subcatprod_ = o.subcatprod == null ? "" : o.subcatprod;
            var subcatprodname_ = o.subcatprodname == null ? "" : o.subcatprodname;
            a.push([(i + 1), o.month + "-" + currentYear, o.sellerid + "-" + o.sellername,
                subcatprod_ + "-" + subcatprodname_, o.jmlvol, o.jmlrp, o.jmlrec
            ]);
        }
        export_excel(a);
    }

}

function admin_repadmin_booking_ctrl($rootScope, $scope, $routeParams) {
    $scope.repAdmin_booking_load = function() {
        var year = $scope.pickYear;
        var month = $scope.pickMonth;
        $.get("?page=" + $routeParams.path_module + "&ds=1&f=repAdmin_booking_load&month=" + month + "&year=" + year,
                null, null, "json")
            .done(function(data) {
                $scope.$apply(function() {
                    $scope.trxBookingYearMonth = data.data;
                });
            });
    }

    $scope.exportExcel = function() {
        var a = [
            ['#', 'Tanggal', 'Jenis Transaksi', 'Unit Bank', 'Nasabah', 'Sub Kode Sampah', 'Vol',
                'Harga/Unit', 'Total', 'Saldo Akhir'
            ]
        ];
        var d = $scope.trxBookingYearMonth;

        for (var i = 0; i < d.length; i++) {
            var o = d[i];
            //debugger;
            var subcatprodname_ = o.subcatprodname == null ? "" : o.subcatprodname;
            var citizenname_ = o.citizenname == null ? "" : o.citizenname;
            a.push([(i + 1), o.dt, o.dkbyworker, o.sellerid, o.citizenid, o.subcatprod, o.vol, o.priceperunit,
                o.amount, o.balance
            ]);
        }
        export_excel(a);

    }

}

function admin_repadmin_citizens_ctrl($rootScope, $scope) {
    // init
    $scope.filter.name1 = '';
    $scope.filter.address1 = '';
    $scope.filter.headname1 = '';
    $scope.filter.name = '';
    $scope.filter.address = '';
    $scope.filter.headname = '';

    $scope.data_all_citizens = data_all_citizens;

    $scope.data_all_citizens_filter1 = function() {
        $scope.filter.name = $scope.filter.name1;
        $scope.filter.address = $scope.filter.address1;
        $scope.filter.headname = $scope.filter.headname1;
    };

    $scope.data_all_citizens_filterClear = function() {
        $scope.filter.name = "";
        $scope.filter.name1 = "";
        $scope.filter.address = "";
        $scope.filter.address1 = "";
        $scope.filter.headname = "";
        $scope.filter.headname1 = "";
    };

    $scope.exportExcel = function() {

        var a = [
            ["#", "Nomer Induk", "nama", "Alamat", "Tlp", "Unit Bank Sampah", "Saldo"]
        ];
        for (var i = 0; i < $scope.data_all_citizens.length; i++) {
            var obj = $scope.data_all_citizens[i];
            var arr1 = [];
            arr1.push(i + 1);
            arr1.push(obj.masterid);
            arr1.push(obj.name);
            arr1.push(obj.address);
            arr1.push(obj.phone);
            arr1.push(obj.headname);
            arr1.push(obj.balance);
            a.push(arr1);
        }

        export_excel(a);
    }

    $scope.data_all_citizens_filter = function(item) {
        if ($scope.filter.name == '' && $scope.filter.address == '' &&
            $scope.filter.headname == '') {
            return true;
        }

        item.name = item.name == null ? '' : item.name;
        item.address = item.adress == null ? '' : item.address;
        item.headname = item.headname == null ? '' : item.headname;

        if (($scope.filter.name == '' ||
                item.name.toLowerCase().indexOf($scope.filter.name.toLowerCase()) > -1) &&
            ($scope.filter.address == '' ||
                item.address.toLowerCase().indexOf($scope.filter.address.toLowerCase()) > -1) &&
            ($scope.filter.headname == '' ||
                item.headname.toLowerCase().indexOf($scope.filter.headname.toLowerCase()) > -1)) {

            return true;
        }
        return false;
    };
};

ngApp.controller('modController', ['$scope', '$rootScope', '$location', '$route', '$routeParams',
    function($scope, $rootScope, $location, $route, $routeParams) {
        reloadIfNotAuth();

        $rootScope.titleDoc=$routeParams.title;
//        debugger;
        //execute layout fix
        if (typeof(layout_start) == 'function') layout_start();

        //main init
        $scope.filter = {};

        var subCtrlModule = $routeParams.path_module;
        subCtrlModule = subCtrlModule.replace(/[\.\-]/g, '_') + "_ctrl";

        //call sub controller as function
        if (typeof window[subCtrlModule] == 'function') {
            var res = window[subCtrlModule]($rootScope, $scope, $routeParams);
            // res=true if finish handled by sub ctrl, otherwise continue processing
            if (res) return;
        }

        // _sys
        if (typeof trxs == 'undefined')
            trxs = {};
        if (typeof trxsellers == 'undefined')
            trxsellers = {};
        $scope.trxs = trxs;
        $scope.trxsellers = trxsellers;

        $scope.doCorrect = function() {
            for (var i = 0; i < $scope.trxs.length; i++) {
                $.get("ds/_syscorrectbal-ds.php?f=setNewBal&newbal=" + $scope.trxs[i]._newbal +
                        "&idRow=" + $scope.trxs[i].id, null, null, "json")
                    .done(function(data) {
                        if (data.result == 'ok') {} else {
                            alert('fail chg user');
                        }
                    });
            }
        }

        $scope.outDateFormat = outDateFormat;

        $scope.repBookingFilter = function(item) {
            if ($scope.filter.dkbyworker == '' &&
                $scope.filter.masterid == '' &&
                $scope.filter.subcatprod == '') {
                return true;
            }

            if (($scope.filter.dkbyworker == '' || item.dkbyworker
                    .toLowerCase() == $scope.filter.dkbyworker
                    .toLocaleLowerCase()) &&
                ($scope.filter.masterid == '' || item.citizenid
                    .toLowerCase() == $scope.filter.masterid
                    .toLocaleLowerCase()) &&
                ($scope.filter.subcatprod == '' || item.subcatprod
                    .toLowerCase() == $scope.filter.subcatprod
                    .toLocaleLowerCase())) {
                return true;
            }
            return false;
        };

        $scope.repAdminBookingFilter = function(item) {
            if ($scope.filter.sellerid == '' &&
                $scope.filter.dkbyworker == '' &&
                $scope.filter.masterid == '' &&
                $scope.filter.subcatprod == '') {
                return true;
            }

            if (($scope.filter.dkbyworker == '' || item.dkbyworker
                    .toLowerCase() == $scope.filter.dkbyworker
                    .toLocaleLowerCase()) &&
                ($scope.filter.masterid == '' || item.citizenid
                    .toLowerCase() == $scope.filter.masterid
                    .toLocaleLowerCase()) &&
                ($scope.filter.sellerid == '' || item.sellerid
                    .toLowerCase() == $scope.filter.sellerid
                    .toLocaleLowerCase()) &&
                ($scope.filter.subcatprod == '' || item.subcatprod
                    .toLowerCase() == $scope.filter.subcatprod
                    .toLocaleLowerCase())) {
                return true;
            }
            return false;
        };

        $scope.repBookingFilter1 = function() {
            $scope.filter.dkbyworker = $scope.filter.dkbyworker1;
            $scope.filter.masterid = $scope.filter.masterid1;
            $scope.filter.sellerid = $scope.filter.sellerid1;
            $scope.filter.subcatprod = $scope.filter.subcatprod1;
        };

        $scope.repBookingFilterCLear = function() {
            $scope.filter.dkbyworker = "";
            $scope.filter.dkbyworker1 = "";
            $scope.filter.masterid = "";
            $scope.filter.masterid1 = "";
            $scope.filter.subcatprod = "";
            $scope.filter.subcatprod1 = "";
            $scope.filter.sellerid = "";
            $scope.filter.sellerid1 = "";
        };

        $scope.doPickSeller = function() {
            $.get(
                "ds/_syscorrectbal-ds.php?f=setUser&user=" +
                $scope.pickSeller, null,
                null, "json").done(function(data) {
                if (data.result == 'ok') {
                    location.reload();
                } // con
                else {
                    alert('fail chg user');
                }
            });
        }

        // init
        $scope.label = {};
        //$scope.filter = {};
        $scope.filter.dkbyworker = "";
        $scope.filter.masterid = "";
        $scope.filter.subcatprod = "";
        $scope.filter.dkbyworker1 = "";
        $scope.filter.masterid1 = "";
        $scope.filter.subcatprod1 = "";
        $scope.filter.sellerid = "";
        $scope.filter.sellerid1 = "";
        $scope.label.test = 'test';
        $scope.label.submit = "Simpan";
        $rootScope.label = {};
        $rootScope.label.test = 'test';
        $scope.val = {};

        $scope.ui = {};
        $scope.ui.btnRepProsesLabel = "Proses";
        $scope.ui.btnRepProsesDisabled = false;

        // Return today's date and time
        var currentTime = new Date()

        // returns the month (from 0 to 11)
        var month = currentTime.getMonth() + 1

        // returns the day of the month (from 1 to 31)
        var day = currentTime.getDate()

        // returns the year (four digits)
        var year = currentTime.getFullYear()
            // $scope.val.dt = year + '-' + month + "-" + day;
        var d = new Date();

        $scope.momentf = momentf;

        $scope.val.dt = moment(d).local().format(
            'YYYY-MM-DD');

        $scope.flag = {};
        $scope.flag.submiting = 0;

        $scope.willPrintedTrxs = {};
        $scope.unprintedTrxs = {};
        $scope.printedTrxLast = 0;

        $scope.getBalanceWorker = function() {
            var id = $scope.masterId;
            $scope.loadUnprintedTrxs(id);
            $.get("?page=sell&ds=1&f=getWorkerBalance&id=" + id, null, null, "json")
                .done(function(data) {
                    //console.log("get balance");
                    $scope.$apply(function() {
                        $scope.label.balanceWorker = data.balance;
                    });
                });
        }

        $scope.checkLimitOnCreditTrx = function() {
            if ($scope.trxKind == "1") {
                if ($scope.label.balanceWorker < $scope.val.totalRp) {
                    $scope.val.totalRp = "";
                    flashMessage(
                        "Jumlah dana yang akan diambil melebihi saldo nasabah",
                        "error");
                }
            }
        }

        $scope.clearForm = function() {
            // $("#cbMasterId").val(" ").trigger('change');
            $("#cbSubCatProd").val(" ").trigger('change');
            $scope.$apply(function() {
                $scope.trxKind = "";
                // $scope.masterId={id:" ", text: "-"};
                $scope.unitMeasure = "";
                $scope.val.vol = "";
                $scope.val.pricePerUnit = "";
                $scope.val.totalRp = "";
                $scope.val.ket = "";
            });
        }

        function checkDataSubmit() {
            if ($scope.trxKind == "-1") {
                if ($scope.val.dt && $scope.val.dt != "" &&
                    $scope.trxKind &&
                    $scope.trxKind != "" &&
                    $scope.masterId &&
                    $scope.masterId != "" &&
                    $scope.unitMeasure &&
                    $scope.unitMeasure != "" &&
                    $scope.val.vol &&
                    $scope.val.vol != "" &&
                    $scope.val.pricePerUnit &&
                    $scope.val.pricePerUnit != "" &&
                    $scope.val.totalRp &&
                    $scope.val.totalRp != "") {
                    return true;
                }
            } else if ($scope.trxKind == "1") {
                if ($scope.val.dt && $scope.val.dt != "" &&
                    $scope.trxKind &&
                    $scope.trxKind != "" &&
                    $scope.masterId &&
                    $scope.masterId != "" &&
                    $scope.val.totalRp &&
                    $scope.val.totalRp != "") {
                    return true;
                }

            } else if ($scope.trxKind == "2") {
                if ($scope.val.dt && $scope.val.dt != "" &&
                    $scope.trxKind &&
                    $scope.trxKind != "" &&
                    $scope.masterId &&
                    $scope.masterId != "" &&
                    $scope.val.totalRp &&
                    $scope.val.totalRp != "") {
                    return true;
                }
            } else if ($scope.trxKind == "3" ||
                $scope.trxKind == "4") {
                if ($scope.val.dt && $scope.val.dt != "" &&
                    $scope.trxKind &&
                    $scope.trxKind != "" &&
                    $scope.masterId &&
                    $scope.masterId != "" &&
                    $scope.val.ket &&
                    $scope.val.ket != "" &&
                    $scope.val.totalRp &&
                    $scope.val.totalRp != "") {
                    return true;
                }
            }
            return false;
        }

        $scope.sellFormSubmit = function() {
            if (!checkDataSubmit()) {
                flashMessage("Mohon lengkapi data transaksi terlebih dulu", "error");
                return;
            }
            $scope.flag.submiting = 1;
            $scope.label.submit = "Mengirim...";
            var data = $("#formtrx").serialize();
            var post = data;

            $.post("?page=sell&ds=1&f=putTrx", post, null, 'json')
                .fail(function() {
                    flashMessage("Koneksi gagal", "error");
                    $scope.$apply(function() {
                        $scope.flag.submiting = 0;
                        $scope.label.submit = "Simpan";
                    });
                })
                .done(function(data) {
                    $scope.$apply(function() {
                        $scope.flag.submiting = 0;
                        $scope.label.submit = "Simpan";
                    });

                    if (data == null) {
                        flashMessage("Transaksi gagal, silahkan lengkapi data terlebih dulu", "error");
                        return;
                    }
                    if (data.result == 'ok') {
                        flashMessage("Transaksi berhasil", "success");
                        $scope.clearForm();
                        $scope.loadUnprintedTrxs($scope.masterId);
                        $scope.getBalanceWorker();
                    } else {
                        alert("Transaksi gagal");
                    }
                });
        }

        $scope.totalRp = function() {
            var ppu = parseFloat($scope.val.pricePerUnit);
            var vol = parseFloat($scope.val.vol);
            $scope.val.totalRp = (ppu * vol);
        }

        // $scope.$watch('val.vol', function () {
        // //$scope.action();
        // console.log('vol act');
        // $scope.totalRp();
        // });

        $scope.updateUnitMeasure = function(unit) {

            console.log(unit);
            newUnit = "";
            newPrice = 0;
            var a = alasql(
                "select unit,price from ? where id='" +
                unit + "' ", [data_select_subcatprods]);
            // debugger;
            if (a.length > 0) {
                newUnit = a[0].unit;
                newPrice = parseFloat(a[0].price);
            }
            $scope.label.unitmeasure = newUnit;
            $scope.val.pricePerUnit = newPrice;

            var old = parseFloat('0' + $scope.val.vol);
            // $scope.val.vol = Math.random();
            $scope.val.vol = old;
            $scope.totalRp();
        }

        $rootScope.reloadPage = function() {
            $("#ngview").html("");
            $route.reload();
        }

        $scope.loadUnprintedTrxs = function(id) {
            $.get("?page=sell&ds=1&f=loadUnprintedTrxs&id=" + id, null, null, "json")
                .done(function(resp) {
                    $scope.$apply(function() {
                        $scope.unprintedTrxs = resp.data;
                        $scope.printedTrxLast = intVal(resp.printedLast);

                        $scope.willPrintedTrxs = [];
                        var maxEntries = 12;
                        var startAt = $scope.printedTrxLast % maxEntries;

                        var serialUp = $scope.printedTrxLast + 1;

                        for (var i = 0; i < startAt; i++) {
                            $scope.willPrintedTrxs.push({
                                dt: " ",
                                dkbyworker: " ",
                                citizenid: " ",
                                subcatprod: " ",
                                vol: " ",
                                priceperunit: " ",
                                amount: " ",
                                balance: " ",
                                serial: "",
                            });
                            // serialUp++;
                        }
                        for (var i = 0; i < $scope.unprintedTrxs.length; i++) {
                            $scope.unprintedTrxs[i].serial = serialUp;
                            $scope.willPrintedTrxs.push($scope.unprintedTrxs[i]);
                            serialUp++;
                        }
                    });
                });
        }

        $scope.arr_datarep = {};

        $scope.genReport1 = function() {
            $scope.arr_datarep = [];
            for (var i = 0; i < 12; i++) {
                $scope.arr_datarep.push({
                    month: i + 1,
                });
            }
        }

        var year = new Date().getFullYear();
        $scope.arr_year = [];
        for (var i = 0; i < 10; i++) {
            $scope.arr_year.push(year - i);
        }


        $scope.printTrx = function() {
            var frame = document.createElement('iframe');
            frame.width = "90%";
            frame.id = "printf";
            frame.height = "300";
            frame.style.position = "absolute";
            frame.style.top = "30px";
            frame.style.zIndex = "3000";
            frame.style.backgroundColor = "white";
            frame.style.left = "30px";
            frame.style.border = "solid 1px red";
            document.getElementById("body").appendChild(
                frame);

            var frm = document.getElementById("printf").contentWindow;
            frm.document.write($("#unprinted").html());
            frm.focus(); // focus on contentWindow is needed
            // on some ie versions
            setTimeout(
                function() {
                    frm.print();
                    document.getElementById("body")
                        .removeChild(frame);

                    var arrPrinted = [];
                    for (var i = 0; i < $scope.unprintedTrxs.length; i++) {
                        arrPrinted
                            .push($scope.unprintedTrxs[i].id);
                    }

                    var post = "id=" +
                        $scope.masterId +
                        "&f=updateTrxWorkersPrinted&data=" +
                        JSON
                        .stringify(arrPrinted);
                    $
                        .post("ds/trx-ds.php",
                            post, null, 'json')
                        .done(
                            function(data) {
                                // debugger;
                                if (data == null) {
                                    flashMessage(
                                        "Transaksi gagal, silahkan lengkapi data terlebih dulu",
                                        "error");
                                    return;
                                }
                                if (data.result == 'ok') {
                                    flashMessage(
                                        "Printing berhasil",
                                        "success");
                                    // $scope.clearForm();
                                    $scope
                                        .loadUnprintedTrxs($scope.masterId);

                                } else {
                                    alert("Transaksi gagal");
                                }
                            });
                }, 1000);
        }


        $scope.printTrxBooking = function() {
            var frame = document.createElement('iframe');
            frame.width = "90%";
            // frame.src="/tes";
            frame.id = "printf";
            frame.height = "300";
            frame.style.position = "absolute";
            frame.style.top = "30px";
            frame.style.zIndex = "3000";
            frame.style.backgroundColor = "white";
            frame.style.left = "30px";
            frame.style.border = "solid 1px red";
            document.getElementById("body").appendChild(
                frame);
            // debugger;

            var frm = document.getElementById("printf").contentWindow;
            var sesname = session.name;
            var onloadPrint = "<script>window.print()</script>";
            var titleDoc = "<h1>Pembukuan transaksi penjualan " +
                $scope.pickYear +
                "-" +
                $scope.pickMonth +
                " oleh " +
                sesname +
                "</h1>"
                // frm.document.write(titleDoc +
                // $("#printdata").html()+onloadPrint);
            frm.document.write(titleDoc +
                $("#printdata").html());
            frm.document.close();
            // frm.document.write("aaa");
            frm.focus(); // focus on contentWindow is needed
            // on some ie versions
            // frm.print();
            setTimeout(function() {
                frm.print();
                document.getElementById("body")
                    .removeChild(frame);
            }, 1000);

        }

    }
]);

/* use a function for the exact format desired... */
function ISODateString(d) {
    function pad(n) {
        return n < 10 ? '0' + n : n
    }

    return d.getUTCFullYear() + '-' + pad(d.getUTCMonth() + 1) + '-' +
        pad(d.getUTCDate())
}

function UTCtoJakarta(utc) {
    if (utc == "") return "";
    var utc_ = moment.tz(utc, "UTC");
    var jkt = utc_.clone().tz("Asia/Jakarta");
    return jkt.format("YYYY-MM-DD HH:mm:ss ZZ e-dddd");
}

function summarizeRepCitizens(arrayObj) {
    if (arrayObj.length == 0)
        return arrayObj;
    var lastNasabah = "";
    var jmlrec_ = 0;
    var jmlrp_ = 0.0;
    for (var i = 0; i < arrayObj.length; i++) {
        if (lastNasabah != arrayObj[i].citizenid) {
            if (lastNasabah != "") {
                var ins_ = {
                    month: arrayObj[i - 1].month,
                    citizenid: arrayObj[i - 1].citizenid,
                    subcatprod: "",
                    jmlvol: "",
                    jmlrp: "Total Rp. " + jmlrp_,
                    jmlrec: "Total trx: " + jmlrec_,
                }
                arrayObj.splice(i, 0, ins_);
                i++;
            }

            lastNasabah = arrayObj[i].citizenid;
            jmlrec_ = parseInt(arrayObj[i].jmlrec);
            jmlrp_ = parseFloat(arrayObj[i].jmlrp);
        } else {
            jmlrec_ += parseInt(arrayObj[i].jmlrec);
            jmlrp_ += parseFloat(arrayObj[i].jmlrp);
        }
    }
    // total last rec
    var ins_ = {
        month: arrayObj[i - 1].month,
        citizenid: arrayObj[i - 1].citizenid,
        subcatprod: "",
        jmlvol: "",
        jmlrp: "Total Rp. " + jmlrp_,
        jmlrec: "Total trx: " + jmlrec_,
    }
    arrayObj.push(ins_);

    return arrayObj;

}

function summarizeRepSellers(arrayObj) {
    if (arrayObj.length == 0)
        return arrayObj;
    var lastSeller = "";
    var jmlrec_ = 0;
    var jmlrp_ = 0.0;
    for (var i = 0; i < arrayObj.length; i++) {
        if (lastSeller != arrayObj[i].sellerid) {
            if (lastSeller != "") {
                var ins_ = {
                    month: arrayObj[i - 1].month,
                    sellerid: arrayObj[i - 1].sellerid,
                    subcatprod: "",
                    jmlvol: "",
                    jmlrp: "Total Rp. " + jmlrp_,
                    jmlrec: "Total trx: " + jmlrec_,
                }
                arrayObj.splice(i, 0, ins_);
                i++;
            }

            lastSeller = arrayObj[i].sellerid;
            jmlrec_ = parseInt(arrayObj[i].jmlrec);
            jmlrp_ = parseFloat(arrayObj[i].jmlrp);
        } else {
            jmlrec_ += parseInt(arrayObj[i].jmlrec);
            jmlrp_ += parseFloat(arrayObj[i].jmlrp);
        }
    }
    // total last rec
    var ins_ = {
        month: arrayObj[i - 1].month,
        sellerid: arrayObj[i - 1].sellerid,
        subcatprod: "",
        jmlvol: "",
        jmlrp: "Total Rp. " + jmlrp_,
        jmlrec: "Total trx: " + jmlrec_,
    }
    arrayObj.push(ins_);

    return arrayObj;
}

var momentf = function(d, f) {
    return moment(d).local().format(f);
}

function hotDataSortBy(data, col) {
    var a = alasql("select * from ? order by " + col, [data]);
    return a;
}

function export_excel(data) {
    flashMessage("Mengekspor seluruh data ...", "info");

    var workbook = ExcelBuilder.Builder.createWorkbook();

    var worksheet = workbook.createWorksheet({ name: 'Sheet1' });
    var stylesheet = workbook.getStyleSheet();
    var originalData = data;

    worksheet.setData(originalData);
    workbook.addWorksheet(worksheet);

    ExcelBuilder.Builder.createFile(workbook).then(function(data) {
        if ('download' in document.createElement('a')) {
            $("#downloader").attr({
                href: "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," + data
            });
        } else {
            Downloadify.create('downloader', {
                filename: function() {
                    return "sample.xlsx";
                },
                data: function() {
                    return data;
                },
                //                            onComplete: function(){ alert('Your File Has Been Saved!'); },
                //                            onCancel: function(){ alert('You have cancelled the saving of this file.'); },
                //                            onError: function(){ alert('You must put something in the File Contents or there will be nothing to save!'); },
                swf: 'downloadify/media/downloadify.swf',
                downloadImage: 'downloadify/images/download.png',
                width: 100,
                dataType: 'base64',
                height: 30,
                transparent: true,
                append: false
            });
        }

        $("#downloader")[0].click();
    });
}