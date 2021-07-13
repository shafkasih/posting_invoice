<script>
	$('#select-all').click(function(event) {
	if(this.checked) {
		$(':checkbox.acv_Checkbox').prop('checked', true);
	} else {
		$(':checkbox.acv_Checkbox').prop('checked', false);
		}
	}); 
	
	$('.acv_Checkbox').click(function() {
		  if($(this).is(':checked')) 
	  {
			  $(this).closest('tr').addClass('text-primary');
		  } else {
			  $(this).closest('tr').removeClass('text-primary');
		  }
	});
</script>
<form class="form-horizontal" name="form1" method="post">

<table class="table table-bordered table-hover" id="tbl_list_appl">
	<div class="modal-body">
		<div class="alert alert-success"><b>Status Invoice: Verify</b></div>
		<thead>
			<th class="text-center">Entry Date</th>
			<th class="text-center">Invoice No</th>
			<th class="text-center">Enter By</th>
			<th class="text-center">Customer ID</th>
			<th class="text-center">Customer Name</th>
			<th class="text-center">Nett AMT</th>
			<th class="text-center">Select All <br> <input type="checkbox" name="select-all" id="select-all" value="" /></th>
			<th class="text-center">Action</th>
		</thead>
		<tbody>
			<?php
			if (!empty($programs)) {
				$count=0;
				$count2=0;
				$i=1;
					foreach($programs as $P){
						$newDate = date("d/m/Y", strtotime($P->CH_INVOICE_DATE));
						$count++;
						$count2=$count2+$P->CH_NETT_AMT;
							echo '<tr data-invoice-id="'.$P->CH_INVOICE_NO.'">
							<td>'.$newDate.'</td>
							<td>'. $P->CH_INVOICE_NO .'</td>
							<td>'. $P->CH_ENTER_BY .'</td>
							<td>'. $P->CH_CUST_ID .'</td>
							<td>'. $P->CH_CUST_NAME .'</td>
							<td>'. "RM ".number_format($P->CH_NETT_AMT,2) .'</td>
							<td class="text-center"><input name="selector[]" id="ad_Checkbox1" class="acv_Checkbox" type="checkbox" value=" ' . $P->CH_INVOICE_NO . '" />
							<td class="text-center col-md-1">
							<button type="button"  title="view" class="btn btn-primary btn-xs detl_appl_btn"><i class="fa fa-search"></i></button>
							</td>
							</tr>';
									
						$i++;
					}
				}else {
				$count=0;
				$count2=0;
				$i=1;
					echo '<tr><td colspan="8" class="text-center"><font color="red">No record found.</font></td></tr>';
				}
			?>				
		</tbody>
	</div>
</table>
<div class="modal-footer">
	<label class="col-md-1 control-label">Count : </label> <div class="col-md-2"><input type="text" value="<?php echo $count;?>" class="form-control" readonly> </div>
	<label class="col-md-2 control-label">Total : </label> <div class="col-md-2"><input type="text" value="<?php echo "RM ".number_format($count2,2);?>"  class="form-control" readonly> </div>
	<label class="col-md-2 control-label">Posting Date : </label> <div class="col-md-2"><input type="date" name="backdate" id="backdate" value="<?php echo date("Y-m-d"); ?>" class="form-control" > </div>
	<input type="hidden" name="todayDate" id="todayDate"  value="<?php echo date("dmY"); ?>" >
	<input type="hidden" name="todayDate2" id="todayDate2"  value="<?php echo date("Y-m-d"); ?>" >
	<button type="button" class="btn btn-success approve">Approve</button>
    </div>
</form>
