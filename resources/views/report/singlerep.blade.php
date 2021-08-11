@extends('master')
@section('main-section')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">

            <h4 class="page-title">Reps Report</h4>
        </div>
    </div>
</div>
<style>
    .ranges ul li:nth-child(1),
    .ranges ul li:nth-child(3),
    .ranges ul li:nth-child(4),
    .ranges ul li:nth-child(6) {
        display: none;
    }

    .filter_date {
        position: relative;
        top: 30px;
        width: auto !important;
        display: inline-block;
    }
    .c-loader{
        width: 5% !important;
        display: none;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <label class="form-label filter_date">Filter:</label>
                <div id="reportrange" class="form-control filter_date" data-toggle="date-picker-range" data-target-display="#selectedValue" data-cancel-class="btn-lighter">
                <i class="mdi mdi-calendar"></i>&nbsp;
                    <span id="selectedValue"></span> <i class="mdi mdi-menu-down"></i>
                </div>
                <img class="filter_date c-loader" src="{{ asset('assets/images/Spin-Preloader-1.gif')}}" />
                <div class="tab-content">
                    <div class="tab-pane show active" id="basic-datatable-preview">
                    <table data-paging="false" id="scroll-horizontal-datatable" class="table w-100 nowrap">
                            <thead>
                                <tr>
                                    <th>Reps</th>
                                    <th>Days</th>
                                    <th>Dails</th>
                                    <th>Dails/day Team</th>
                                    <th>Talk Time</th>
                                    <th>TT/day</th>
                                    <th>Decision Maker</th>
                                    <th>Influencer</th>
                                    <th>Connections</th>
                                    <th>Connection rate</th>
                                    <th>Hook Reject</th>
                                    <th>Hook Accepted</th>
                                    <th>%Hook accepted</th>
                                    <th>Pitch Reject</th>
                                    <th>Qualified Pitch</th>
                                    <th>%QP</th>
                                    <th>%QP to close</th>
                                    <th>% Connection to Close</th>
                                    <th>Demo Set</th>
                                    <th>Demos/Day</th>
                                    <th>Dial/Demo</th>
                                </tr>
                            </thead>
                            <tbody id="filter">
                               @include('report.singlerepfilter')
                            </tbody>
                        </table>
                    </div> <!-- end preview-->

                </div> <!-- end tab-content-->
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
<!-- end row-->

<script>
$(function() {
  $('#test').daterangepicker({
    opens: 'left'
  }, function(start, end, label) {
    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });
});
</script>
<script>
    $(document).ready(function() {
        //filter ajaxcall
        $(document).on('click', '.ranges ul li:nth-child(2),.ranges ul li:nth-child(5),.applyBtn',function(event) {
            event.preventDefault();
            var data = jQuery("#selectedValue").text();
            var a = data.split('-');
            //start date
            var day = new Date(Date.parse(a[0])).getDate();
            var month = new Date(Date.parse(a[0])).getMonth() + 1;
            var year = new Date(Date.parse(a[0])).getFullYear();
            var starting_date = year + '-' +
                (month < 10 ? '0' : '') + month + '-' +
                (day < 10 ? '0' : '') + day;
            //End date
            var day = new Date(Date.parse(a[1])).getDate();
            var month = new Date(Date.parse(a[1])).getMonth() + 1;
            var year = new Date(Date.parse(a[1])).getFullYear();
            var ending_date = year + '-' +
                (month < 10 ? '0' : '') + month + '-' +
                (day < 10 ? '0' : '') + day;

            $.ajax({
                type: "get",
                url: "{{route('filter.singlerep')}}",
                data: {
                    starting_date:starting_date,
                    ending_date:ending_date,
                },
                success: function(result) {
                    $('#scroll-horizontal-datatable').DataTable().destroy();
                    $('#filter').html(result);
                    $("#scroll-horizontal-datatable").DataTable({
                        bLengthChange: !1,
                        order: [],
                        columnDefs: [{ orderable: !1, targets: 0 }],
                        scrollX: !0,
                        language: { paginate: { previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>" } },
                        drawCallback: function () {
                            $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                        },
                    });
                    $('#scroll-horizontal-datatable,.table w-100 nowrap').resize();
                    $('.c-loader').css("display","none");
                }
            });
        });
        $(document).on('click', '.ranges ul li:nth-child(2),.ranges ul li:nth-child(5),.applyBtn', function(event) {
            $('.c-loader').css("display","inline");
        });

    });

    // Datetime and date range picker\r\n
</script>

@endsection