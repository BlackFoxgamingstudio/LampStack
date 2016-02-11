<!-- Page Title -->
<div class="highlight-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Personal Files for <?= $current_user->name();?></h2>
            </div>
        </div>
    </div>
</div>
<!-- View Controls -->
<div class="toolbar">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul class="toolbar-options">
                    <li class="toolbar-options-wrap">
                        <span class="subdued-caps">Actions</span>
                        <ul>
                            <li><span id="newDirBtn" class="btn btn-success"><i class="fa fa-folder"></i> New Directory</span></li>
                            <li><span id="newFileBtn" class="btn btn-success"><i class="fa fa-plus"></i> New File</span></li>
                        </ul>
                    </li>

                    <li class="pull-right"><a href="<?= BASE_URL;?>" title="Dashboard" class="btn btn-primary"><i class="fa fa-home"></i> Dashboard</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="section section-files">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="my-files" class="filemanager"></div>
            </div>
        </div>
    </div>
</div>

<div class="highlight-dark-recessed">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2>Your File Useage</h2>
                <p><?= convert_bytes($userFileUsage); ?> / <?= convert_bytes($userFileLimit);?></p>
            </div>
            <div class="col-md-6">
                <canvas id="file-usage-chart" width="300" height="300"></canvas>
            </div>
        </div>
    </div>
</div>



<script>
    var rootFolderIndex = '';

    $('#newFileBtn').click(function() {
        openForm('<?=BASE_URL;?>app/views/forms/new.file.form.php', {folder: rootFolderIndex});
    });
    $('#newDirBtn').click(function() {
        var directoryName = prompt('Name for the new directory','New Folder');
        if (directoryName != null) {
            $.post('./', {create: "directory", dir_name: directoryName, current_directory: rootFolderIndex}, function(data) {
                if (data == 'success') {
                    refresh_files(rootFolderIndex);
                } else {
                    alert(data);
                }
            });
        }
    });
    // Initialize
    function refresh_files(index) {
        $('#my-files').html('<div class="text-center"><p class="stat"><i class="fa fa-spinner fa-spin"></i></p></div>');
        $.get('<?= BASE_URL;?>files/users/scan.php', {root: index }, function(data) {
            $('#my-files').html(data);
        });
    }

    refresh_files(rootFolderIndex);

    // Chart Data
    var doughnutData = [
        {
            value: <?= round($userSpace, 2);?>,
            color: "#50C878",
            highlight: "#5AD3D1",
            label: "Used Space"
        },
        {
            value: <?= round($unusedSpace, 2);?>,
            color: "#222",
            highlight: "#FF5A5E",
            label: "Unused Space"
        }

    ];

    window.onload = function(){
        var ctx = document.getElementById("file-usage-chart").getContext("2d");
        window.myDoughnut = new Chart(ctx).Doughnut(doughnutData, {responsive : true});
    };

</script>