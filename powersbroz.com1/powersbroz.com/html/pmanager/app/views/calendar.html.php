<? $calendar = new Calendar($now->format('m'), $now->format('Y'));?>
<!-- Page Title -->
<div class="highlight-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>The Calendar</h2>
            </div>
        </div>
    </div>
</div>

<!-- View Controls -->
<div class="toolbar">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul class="list-unstyled list-inline">
                    <li>Views</li>
                    <li><span id="defaultCalendarBtn" class="btn btn-primary"><i class="fa fa-calendar"></i> Default</span></li>
                    <li><span id="detailCalendarBtn" class="btn btn-primary"><i class="fa fa-bars"></i> Detail</span></li>
                    <li class="pull-right"><a href="<?= BASE_URL;?>" title="Back to the dashboard" class="btn btn-primary"><i class="fa fa-refresh"></i> Dashboard</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row push-vertical">
                    <div class="col-md-6 text-center">
                        <select id="month" name="month">
                            <option value="1"<?= ($now->format('m') == 1) ? ' selected': ''?>>January</option>
                            <option value="2"<?= ($now->format('m') == 2) ? ' selected': ''?>>February</option>
                            <option value="3"<?= ($now->format('m') == 3) ? ' selected': ''?>>March</option>
                            <option value="4"<?= ($now->format('m') == 4) ? ' selected': ''?>>April</option>
                            <option value="5"<?= ($now->format('m') == 5) ? ' selected': ''?>>May</option>
                            <option value="6"<?= ($now->format('m') == 6) ? ' selected': ''?>>June</option>
                            <option value="7"<?= ($now->format('m') == 7) ? ' selected': ''?>>July</option>
                            <option value="8"<?= ($now->format('m') == 8) ? ' selected': ''?>>August</option>
                            <option value="9"<?= ($now->format('m') == 9) ? ' selected': ''?>>September</option>
                            <option value="10"<?= ($now->format('m') == 10) ? ' selected': ''?>>October</option>
                            <option value="11"<?= ($now->format('m') == 11) ? ' selected': ''?>>November</option>
                            <option value="12"<?= ($now->format('m') == 12) ? ' selected': ''?>>December</option>
                        </select>
                    </div>
                    <div class="col-md-6 text-center">
                        <select id="year" name="year">
                            <option value="2015">2015</option>
                            <option value="2016">2016</option>
                            <option value="2017">2017</option>
                            <option value="2018">2018</option>
                            <option value="2019">2019</option>
                            <option value="2020">2020</option>
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                            <option value="2027">2027</option>
                            <option value="2028">2028</option>
                            <option value="2029">2029</option>
                            <option value="2030">2030</option>
                            <option value="2031">2031</option>
                            <option value="2032">2032</option>
                            <option value="2033">2033</option>
                            <option value="2034">2034</option>
                            <option value="2035">2035</option>
                            <option value="2036">2036</option>
                            <option value="2037">2037</option>
                            <option value="2038">2038</option>
                            <option value="2039">2039</option>
                            <option value="2040">2040</option>
                            <option value="2041">2041</option>
                            <option value="2042">2042</option>
                            <option value="2043">2043</option>
                            <option value="2044">2044</option>
                            <option value="2045">2045</option>
                            <option value="2046">2046</option>
                            <option value="2047">2047</option>
                            <option value="2048">2048</option>
                            <option value="2049">2049</option>
                            <option value="2050">2050</option>
                        </select>
                    </div>


                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="well well-lg">
                            <span class="push-right"><input type="checkbox" id="projects" checked /> Project Checkpoints</span>
                            <span class="push-right"><input type="checkbox" id="stages" checked /> Project Stages</span>
                            <span class="push-right"><input type="checkbox" id="stage-tasks" checked /> Tasks</span>
                            <span class="push-right"><input type="checkbox" id="invoices" checked /> Invoices</span>
                        </div>
                    </div>
                </div>
                <div id="calendar">
                    <?= $calendar->render_default();?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var calendarType = 1;
    var currentmonth = <?= $now->format('n');?>;
    var currentyear  = <?= $now->format('Y');?>;
    function updateCalendar(month, year) {
        //$('#calendar').html('<h3><i class="fa fa-cog fa-spin"></i> Loading calendar...</h3>');
        $.get('<?= BASE_URL;?>app/views/collections/calendar.default.html.php', {month: currentmonth, year: currentyear}, function(data) {
            $('#calendar').html(data);
        });
    }

    $('#month').change(function() {
        currentmonth = $(this).val();
        updateCalendar(month, year);
    });
    $('#year').change(function() {
        currentyear = $(this).val();
        updateCalendar(month, year);
    });

</script>