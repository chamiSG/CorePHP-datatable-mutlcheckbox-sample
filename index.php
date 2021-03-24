<?php require 'inc/_global/config.php'; ?>
<?php require 'inc/backend/config.php'; ?>
<?php require 'inc/_global/views/head_start.php'; ?>

<!-- Page JS Plugins CSS -->
<?php $one->get_css('js/plugins/datatables/dataTables.bootstrap4.css'); ?>
<?php $one->get_css('js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css'); ?>

<?php require 'inc/_global/views/head_end.php'; ?>
<?php require 'inc/connect_db.php'; ?>

<?php
    $employees = getEmployees();
?>
<!-- Hero -->
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">
                DataTables <small class="d-block d-sm-inline-block mt-2 mt-sm-0 font-size-base font-w400 text-muted">Server-Side Process, Multiple Row Select, Checkbox, Submit</small>
            </h1>
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Tables</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">DataTables</a>
                    </li>
                </ol>
            </nav>
        </div>
   </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <div class="container">
        <!-- Dynamic Table Full Pagination -->
        <div class="block">
            <div class="block-header">
                <h3 class="block-title">Status: <span class="badge badge-secondary status">unknown</span></h3>
            </div>
            <div class="block-content block-content-full">

                <form id="save_form" class="mb-5" action="/api/ajax_save_form.php" method="POST">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="project_name">Project Name</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="project_name" name="project_name" placeholder="Your Project Name..." required>
                        </div>
                    </div>

                    <table class="table table-bordered table-striped js-table-checkable js-dataTable-full-pagination" id="employeeTable">
                        <thead>
                            <tr>
                                <th>
                                    <div class="custom-control custom-checkbox d-inline-block">
                                        <input type="checkbox" class="custom-control-input" id="check-all" name="employee_id">
                                        <label class="custom-control-label" for="check-all"></label>
                                    </div>
                                </th>
                                <th class="text-center" style="width: 80px;">ID</th>
                                <th>Name</th>
                                <th class="d-none d-sm-table-cell" style="width: 30%;">Office</th>
                                <th class="d-none d-sm-table-cell" style="width: 15%;">Age</th>
                                <th style="width: 15%;">Start Date</th>
                                <th style="width: 15%;">Position</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($employees as $key => $value) { ?>
                            <tr class="project-list">
                                <td>
                                <div class="custom-control custom-checkbox d-inline-block">
                                    <input type="checkbox" class="custom-control-input" id="employee_<?php echo $key + 1; ?>" name="employee_id[]" value="<?php echo $value['id']; ?>">
                                    <label class="custom-control-label" for="employee_<?php echo $key + 1; ?>"></label>
                                </div>
                                </td>
                                <td class="text-center font-size-sm"><?php echo $key + 1; ?></td>
                                <td class="font-w600 font-size-sm"><?php echo $value['name']; ?></td>
                                <td class="d-none d-sm-table-cell font-size-sm">
                                    <?php echo $value['office']; ?>
                                </td>
                                <td class="d-none d-sm-table-cell">
                                    <?php echo $value['age']; ?>
                                </td>
                                <td>
                                    <?php echo $value['startdate']; ?>
                                </td>
                                <td>
                                    <?php echo $value['position']; ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    <div class="form-group row mt-5">
                        <div class="col-sm-1">
                            <button type="submit" class="btn btn-primary save">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- END Dynamic Table Full Pagination -->
    </div>
</div>
<div id="page-loader" class="show"></div>
<!-- END Page Content -->

<?php require 'inc/_global/views/page_end.php'; ?>
<?php require 'inc/_global/views/footer_start.php'; ?>

<!-- Page JS Plugins -->
<?php $one->get_js('js/plugins/datatables/jquery.dataTables.min.js'); ?>
<?php $one->get_js('js/plugins/datatables/dataTables.bootstrap4.min.js'); ?>
<?php $one->get_js('js/plugins/datatables/buttons/dataTables.buttons.min.js'); ?>
<?php $one->get_js('js/plugins/datatables/buttons/buttons.print.min.js'); ?>
<?php $one->get_js('js/plugins/datatables/buttons/buttons.html5.min.js'); ?>
<?php $one->get_js('js/plugins/datatables/buttons/buttons.flash.min.js'); ?>
<?php $one->get_js('js/plugins/datatables/buttons/buttons.colVis.min.js'); ?>
<?php $one->get_js('js/plugins/bootstrap-notify/bootstrap-notify.min.js'); ?>
<!-- Page JS Code -->
<?php $one->get_js('js/pages/be_tables_datatables.js'); ?>
<script>
    jQuery(function(){
        var idArray = [];
        var table = $('#employeeTable').dataTable();
               
        $('.js-dataTable-full-pagination tbody').on('click', 'tr', function () {
            
            var value = $(this).find(".custom-control-input").val();

            $(this).toggleClass('selected');
            if ($(this).hasClass('table-active')){
                $(this).removeClass("table-active");
                $(this).find(".custom-control-input").prop("checked", false);
                idArray.pop(value);
            }else{
                $(this).addClass("table-active");
                $(this).find(".custom-control-input").prop("checked", true);
                idArray.push(value);
            }
            
        } );

        $("#save_form").submit(function(event){
            event.preventDefault();
            One.loader('show');

            $.ajax({
                url : '/api/ajax_save_form.php',
                type: 'POST',
                data: {
                    'project_name': $('#project_name').val(),
                    'employee_id': idArray
                },
                dataType: "json",
                success: function(response){ 
                    One.loader('hide');
                    One.helpers('notify', {type: 'success', icon: 'fa fa-check mr-1', message: response.msg});
                    $('.status').html(response.status);
                    $('.status').removeClass('badge-secondary');
                    $('.status').addClass('badge-success');
                    idArray = [];
                    var allPages = table.fnGetNodes();
                    if ($(allPages).hasClass('table-active')){
                        $(allPages).removeClass('table-active');
                    }
                    $('input[type="checkbox"]', allPages).prop('checked', false);
                    $('#project_name').val('');
                },
                error: function(error){
                    One.helpers('notify', {type: 'danger', icon: 'fa fa-check mr-1', message: error});
                }
            });
        });
    });
</script>
<?php require 'inc/_global/views/footer_end.php'; ?>
