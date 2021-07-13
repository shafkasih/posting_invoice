<div id="app">
    <div class="row">
        <div class="col-xs-9 col-sm-12">
            <div class="jarviswidget jarviswidget-sortable jarviswidget-color-darken" role="widget">
                <header role="heading" class="ui-sortable-handle">
                    <h2>Invoice Approval</h2>
                </header>
                <div role="content">
                 <div class="widget-body">     
						<div class="row">
								<div class="col-sm-3">
									<div class="form-group text-right">
										<label><b>Customer Type:</b></label>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group text-left">
								        <?php echo form_dropdown('program',$program,$default_dept,'class="form-control listFilter" id="lsDept"');?>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="text-left">   
										&nbsp;
									</div>
								</div>
						</div>
							<div class="row">
								<div class="col-sm-3">
									<div class="form-group text-right">
										<label><b>Month Entry:</b></label>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group text-left">
										<input type="text" id="lsTerritory" name="lsTerritory" class="monthPicker form-control" />
									</div>
								</div>
								<div class="col-sm-3">
									<div class="text-left">   
									<button type="button" title="Search"class="search btn btn-primary btn-sm">Searching</button>
									</div>
								</div>
							</div>
							  <ul id="myTab1" class="nav nav-tabs bordered">
                                <li class="active">
                                    <a style="color:#000 !important" href="#s1" data-toggle="tab" aria-expanded="true">Invoice List <span class=""></span></a>
                                </li>
                                <li class="">
                                    <a style="color:#000 !important" href="#s2" data-toggle="tab" aria-expanded="false"><i class=""></i> Details</a>
                                </li>
                            </ul>
						
						<!-- begin myTabContent1 -->
                            <div id="myTabContent1" class="tab-content padding-10">
                                <div class="tab-pane fade active in" id="s1">
									<div id="application_list">
										<p>
										<table class="table table-bordered table-hover">
											<thead>
											<tr>
												<th class="text-center">No record found.</th>
											</tr>
											</thead>
										</table>
										</p>
									
									</div>
                                </div>
                                <div class="tab-pane fade" id="s2">
									<div id="detail_application">
									<table class="table table-bordered table-hover">
										<thead>
										<tr>
	                						<th class="text-center">Please select invoice from Invoice List Tab</th>
										</tr>
										</thead>
									</table>
									</div>
                                </div>
								
                            </div>
						</div>
					</div>
				</div>
            </div>
        </div>
    </div>
	<!-- ADD / EDIT / DELETE page will be displayed here -->
		<div class="modal fade" id="myModalis" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
			<div class="modal-dialog">
				<div class="modal-content">

				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div>
	<!-- end ADD / EDIT / DELETE -->
</div>
<script>

$(document).ready(function()
{   
    $(".monthPicker").datepicker({
        dateFormat: 'MM yy',
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,

        onClose: function(dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).val($.datepicker.formatDate('yy-mm', new Date(year, month, 1)));
        }
    });

    $(".monthPicker").focus(function () {
        $(".ui-datepicker-calendar").hide();
        $("#ui-datepicker-div").position({
            my: "center top",
            at: "center bottom",
            of: $(this)
        });
    });
});
var dt_invoice='';
		// populate list of application
		var deptCode = $('#lsDept').val();
		var terrCode = $('#lsTerritory').val();
		$('#application_list').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></div>');
		$.ajax({
			type: 'POST',
			url: '<?php echo $this->lib->class_url('applicationList')?>',
			data: {'departmentCode' : deptCode, 'territoryID' : terrCode},
			success: function(res) {
				$('#application_list').html(res);
				dt_invoice = $('#tbl_list_appl').DataTable({"ordering":false});
				
			}
			
		});		

	var dCode = $('#lsDept').val();
	var tCode = $('#lsTerritory').val();
	
	
	$('.search').click(function () {
		 dCode = $('#lsDept').val();
		 tCode = $('#lsTerritory').val();
		 
		$('.nav-tabs li:eq(0) a').tab('show');
		$('#application_list').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></div>');
		$('#detail_application').html('<table class="table table-bordered table-hover"><thead><tr><th class="text-center">Please select detail from Invoice List Tab</th></tr></thead></table>');
		$.ajax({
			type: 'POST',
			url: '<?php echo $this->lib->class_url('applicationList')?>',
			data: {'dCode' : dCode, 'tCode' : tCode},
			success: function(res) {
				$('#application_list').html(res);
				dt_invoice = $('#tbl_list_appl').DataTable({
					"ordering":true
				});
			}
		});
	
	});
	
	$('#application_list').on('click','.detl_appl_btn', function() {
		var thisBtn = $(this);
		var sid = $(this).parents('tr').data('invoice-id');
		
		$('.nav-tabs li:eq(1) a').tab('show');
		$('#detail_application').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></div>');
		
		$.ajax({
			type: 'POST',
			url: '<?php echo $this->lib->class_url('detlApplication')?>',
			data: {'invoiceID' : sid},
			success: function(res) {
				$('#detail_application').html(res);
			}
		});
	});
	
	$('#detail_application').on('click','.back_btn', function() {
		$('.nav-tabs li:eq(0) a').tab('show');
				$('#detail_application').html('<table class="table table-bordered table-hover"><thead><tr><th class="text-center">Please select detail from Invoice List Tab</th></tr></thead></table>');
	});
	
	$('#application_list').on('click','.approve', function() {
		deptCode = $('#lsDept').val();
		terrCode = $('#lsTerritory').val();
		var todayDate = $('#todayDate').val();
		var todayDate2 = $('#todayDate2').val();
		var backdate = $('#backdate').val();
		var CH_INVOICE_DATE = $('#CH_INVOICE_DATE').val();
		var no;
		var no2;
        var inv_id = [];
        var sttus = 'ENTRY';
        var new_status = 'APPROVE';
        var counter = 0;
        var counter2 = 0;
        check2 = $('.acv_Checkbox') .is(":checked");
        check = $('.acv_Checkbox') .is(":checked");
		
		if(check){
            $('.acv_Checkbox:checked').each(function(i){
                inv_id[i] = $(this).val();
                no = i;
            });
			no= no +1;  
            inv_id2 = inv_id[0];			
            $.ajax({
                    type: 'POST',
                    url: '<?php echo $this->lib->class_url('check')?>',
                    data:{'inv_id2':inv_id2,'new_status':new_status,'departmentCode' : deptCode, 'territoryID' : terrCode, 'todayDate': todayDate,'backdate':backdate},
                    dataType: 'json',
                    success: function(res) {
							if (res.sts == 0) {
							$.alert({title:'',content: res.msg, type: 'red',});
							}else{
									
								if(check2){
								$('.acv_Checkbox:checked').each(function(i){
									inv_id[i] = $(this).val();
									no2 = i;
								});
							   
								no2= no2 +1;
								if(inv_id != ''){
								$.confirm({
									title: 'Confirmation',
									content: 'Are you sure want to Approve?</b>',
									type: 'red',
									//autoClose:'cancel|3000',
									buttons: {
										yes: function () {
											while (counter2 < no2) {    
												inv_id2 = inv_id[counter2];
												$('#application_list').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></div>');
												$.ajax({
													type: 'POST',
													url: '<?php echo $this->lib->class_url('approve')?>',
													data:{'inv_id2':inv_id2,'new_status':new_status,'departmentCode' : deptCode, 'territoryID' : terrCode, 'todayDate': todayDate, 'todayDate2': todayDate2,'backdate':backdate},
													dataType: 'json',
												   success: function(res) {
													if (res.sts == 1) {
													$('#application_list').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></div>');
													$.alert({title:'',content: res.msg, type: 'red',});
													//$('.btn').removeAttr('e');
													}
													else{
														//$.alert({title:'',content: res.msg, type: 'green',});
														$('#application_list').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></div>');
															setTimeout(function() {
																$('#edit').trigger('click');
															}, 5000);
															dCode = $('#lsDept').val();
															tCode =$('#lsTerritory').val();
															$.ajax({
																type: 'POST',
																url: '<?php echo $this->lib->class_url('applicationList')?>',
																data: {'dCode' : dCode, 'tCode' : tCode},
																success: function(res) {
																	//$.alert({title: '',content:'Successful Verify', type:'green',}); 
																	$('#application_list').html(res);
																	dt_invoice = $('#tbl_list_appl').DataTable({"ordering":true});
																	
																}	
															});	
																			
														}
												}
											});
										counter2++;
											} $.alert({title:'',content:"Record successfully approved and posted to general ledger", type: 'green',});	
											
												
											   
											   
											},
											cancel: function () {   
											}
										}
									});
									}
										
								}
								else{
								$.alert({title:'',content:"No data selected. Please select one to proceed.", type: 'red',});
								//alert("No Data Selected");
								}
							}
													
							}
							});
							
            
        }
        else{
		$.alert({title:'',content:"No data selected. Please select one to proceed.", type: 'red',});
        //alert("No Data Selected");
        }

        
	});
	
	$('#detail_application').on('click','.detl_appl_btn', function() {
		  var doc_id = $(this).attr('doc_id');
		 var type_id = $(this).attr('type_id');
        if (doc_id) {
            $('#myModalis .modal-content').empty();
            $('#myModalis').modal('show');
            $.post('<?php echo $this->lib->class_url('viewDetail') ?>', {type_id: type_id ,doc_id: doc_id}, function (res) {
                $('#myModalis .modal-content').html(res);
            });
        }
	});
  
	
</script>


