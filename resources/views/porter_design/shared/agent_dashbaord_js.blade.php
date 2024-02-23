<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    // $(document).ready(function () {
    //     $(function () {
    //         $('#checkInDate').daterangepicker({
    //             opens: 'left',
    //             autoApply: true,
    //         }, function (start, end, label) {
    //         });
    //     });
    // })
    // $(document).ready(function () {
    //     $(function () {
    //         $('#checkInIDate').daterangepicker({
    //             opens: 'left',
    //             autoApply: true,
    //         }, function (start, end, label) {
    //         });
    //     });
    // })
    $(document).ready(function () {
            $('#checkInIDate').daterangepicker({
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    // You can add more custom ranges here
                },
                autoApply: true,
                startDate: moment(),  // Set the start date to today
                endDate: moment()     // Set the end date to today
            });
        });
</script>

<script>
    $('input[id=datepicker]').change(function () {
        var val = $("#datepicker").val();
        var splitArr = val.split("-");
        var finalVal = splitArr[0].substring(splitArr[0].length - 2, splitArr[0].length) + splitArr[1].substring(splitArr[1].length - 2, splitArr[1].length);
    });
</script>
    
