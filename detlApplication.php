<form class="form-horizontal" method="post">
    <div class="modal-body">
        <div id="alertUpd"></div>
		<div class="form-group">
			<label class="col-md-2 control-label ">Invoice ID:</label>
			<div class="col-md-2">
				<input type="text" id="typeT" class="form-control" value="<?php echo $invoiceID?>" readonly>
			</div>
			<label class="col-md-2 control-label ">DT Account Code:</label>
			<div class="col-md-2">
				<input type="text" id="typeT" class="form-control" value="<?php echo $P->CH_GLACCT_CODE?>" readonly>
			</div>
			<div class="col-md-4">
				<input type="text" class="form-control" value="<?php if($P->CH_CUST_TYPE=='STAF'){echo "STAFF";}else if($P->CH_CUST_TYPE=='SPON'){echo "SPONSOR";}else if($P->CH_CUST_TYPE=='OTHR'){echo "OTHER";}ELSE if($P->CH_CUST_TYPE=='VEND'){echo "VENDOR";}?>" readonly>
			</div>
		</div>
        <div class="form-group">
            <label class="col-md-2 control-label">Invoice Description </label>
            <div class="col-md-10">
                <input type="text" class="form-control" value="<?php echo $P->CH_INVOICE_DESC?>" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label">Customer ID </label>
            <div class="col-md-4">
                <input type="text" class="form-control" value="<?php echo $P->CH_CUST_ID?>" placeholder="" readonly>
            </div>
            <label class="col-md-2 control-label">Customer Name:</label>
            <div class="col-md-4">
			<input type="text" class="form-control" value="<?php echo $P->CH_CUST_NAME?>" placeholder="" readonly>
            </div>
        </div>
		 <div class="form-group">
			<label class="col-md-2 control-label">Address</label>
            <div class="col-md-10">
				<textarea rows="2" cols="50" class="form-control" readonly> <?php echo $P->CH_ADDRESS?> </textarea>
            </div>
		</div>
		<div class="form-group">
			<label class="col-md-2 control-label">Our Ref.:  </label> 
			<div class="col-md-4"><input type="text" class="form-control" value="<?php echo $P->CH_OUR_REF?>" readonly></div>
			<label class="col-md-2 control-label">Your. Ref: </label> 
			<div class="col-md-4"><input type="text" class="form-control" value="<?php echo $P->CH_YOUR_REF?>" readonly></div>
		</div>
		<div class="form-group">
            <label class="col-md-2 control-label">Entry Date</label>
            <div class="col-md-4">
                <input type="text"  class="form-control" value="<?php if($P->CH_INVOICE_DATE!=null){echo date_format(date_create($P->CH_INVOICE_DATE),"d-m-Y H:i:s A");} ?>" placeholder="" readonly>
            </div>
            <label class="col-md-2 control-label">Entry By</label>
            <div class="col-md-4">
				<input type="text"  class="form-control" value="<?php echo $P->CH_ENTER_BY?>" placeholder="" readonly>
            </div>
        </div>
		<div class="form-group">
            <label class="col-md-2 control-label">Verify Date</label>
            <div class="col-md-4">
                <input type="text"  class="form-control" value="<?php  if($P->CH_VERIFY_DATE!=null){echo date_format(date_create($P->CH_VERIFY_DATE),"d-m-Y H:i:s A");}  ?>" placeholder="" readonly>
            </div>
            <label class="col-md-2 control-label">Verify By</label>
            <div class="col-md-4">
				<input type="text"  class="form-control" value="<?php echo $P->CH_VERIFY_BY?>" placeholder="" readonly>
            </div>
        </div>
		<div class="form-group">
            <label class="col-md-2 control-label">Approve Date</label>
            <div class="col-md-4">
                <input type="text"  class="form-control" value="<?php if($P->CH_APPROVE_DATE!=null){echo date_format(date_create($P->CH_APPROVE_DATE),"d-m-Y H:i:s A");}?>" placeholder="" readonly>
            </div>
            <label class="col-md-2 control-label">Approve By</label>
            <div class="col-md-4">
				<input type="text"  class="form-control" value="<?php echo $P->CH_APPROVE_BY?>" placeholder="" readonly>
            </div>
        </div>
				<div class="form-group">
            <label class="col-md-2 control-label">Cancel Date</label>
            <div class="col-md-4">
                <input type="text"  class="form-control" value="<?php echo $P->CH_CANCEL_DATE?>" placeholder="" readonly>
            </div>
            <label class="col-md-2 control-label">Cancel By</label>
            <div class="col-md-4">
				<input type="text"  class="form-control" value="<?php echo $P->CH_CANCEL_BY?>" placeholder="" readonly>
            </div>
        </div>
		<div class="form-group">
            <label class="col-md-2 control-label">Cancel Reason </label>
            <div class="col-md-10">
                <input type="text" class="form-control" value="<?php echo $P->CH_CANCEL_REASON?>" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label">Term</label>
            <div class="col-md-10">
				<textarea  class="form-control" placeholder="" readonly><?php echo $P->CH_TERMS?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label">Gov. Tax:</label>
            <div class="col-md-4">
				<input type="text" class="form-control" value="<?php echo $P->CH_GOVT_TAX ."%"?>" placeholder="" readonly>
            </div>
            <label class="col-md-2 control-label">Rounding Amt:</label>
            <div class="col-md-4">
				<input type="text"  class="form-control" value="<?php echo number_format($P->CH_ROUNDING_AMT,2)?>" placeholder="" readonly>
            </div>
        </div>
       <div class="form-group">
				<label class="col-md-2 control-label">Total Amount:</label>
			<div class="col-md-4">
				<input type="text" class="form-control" value="<?php echo "RM ". number_format($P->CH_TOTAL_AMT,2)?>" placeholder="" readonly>
			</div>
			<label class="col-md-2 control-label">Nett Amount:</label>
			<div class="col-md-4">
				<input type="text" class="form-control" value="<?php echo "RM ". number_format($P->CH_NETT_AMT,2)?>" placeholder="" readonly>
			</div>
				
		</div>
		 <div class="form-group">
            <label class="col-md-2 control-label">Paid Amt:</label>
            <div class="col-md-4">
				<input type="text" class="form-control" value="<?php echo "RM ".number_format($P->CH_PAID_AMT,2)?>" placeholder="" readonly>
            </div>
            <label class="col-md-2 control-label">Balance Amt:</label>
            <div class="col-md-4">
				<input type="text"  class="form-control" value="<?php echo "RM ".number_format($P->CH_BAL_AMT,2)?>" placeholder="" readonly>
            </div>
        </div>
			<div class="form-group">
			<label class="col-md-2 control-label">Status: </label>
			<div class="col-md-4">
				<input type="text" class="form-control" value="<?php echo $P->CH_STATUS?>" placeholder="" readonly>
			</div>
		</div>
		<div class="well">
			<div class="row text-left">
				<h6 class="panel-heading bg-color-blueLight txt-color-white">List of Details</h6>
			</div>
			<div class="row">
				<table class="table table-bordered table-hover">
					<thead>
					<tr>
						<th class="text-center">Seq ID</th>
						<th class="text-center">Description</th>
						<th class="text-center">CR Account Code</th>
						<th class="text-center">Qty</th>
						<th class="text-center">Unit Price</th>
						<th class="text-center">Tax(%)</th>
						<th class="text-center">Total Amount</th>
						<th class="text-center">Rounding</th>
						<th class="text-center">Detail</th>
					</tr>
					</thead>
					<tbody>
					<?php
						if (!empty($doc_rec)) {
							$i = 1;
						   foreach ($doc_rec as $doc) {
							 echo    '<tr>
								<td class="text-center">' . $doc->CD_SEQ_NO . '</td>
								<td class="text-left">' .$doc->CD_DETAIL_DESC . '</td>
								<td class="text-left">' . $doc->CD_ACCOUNT_CODE . '</td>
								<td class="text-center">' . $doc->CD_QTY . '</td>
								<td class="text-center">' . "RM".number_format($doc->CD_UNIT_PRICE,2) . '</td>
								<td class="text-center">' . $doc->CD_GST_TAXAMT*100 . '</td>
								<td class="text-center">' . "RM".number_format($doc->CD_TOTAL_AMT,2) . '</td>
								<td class="text-center">' . number_format($doc->CD_ROUNDING_AMT,2) . '</td>
								<td class="text-center">
									<button type="button"  title="view" type_id="' . $doc->CD_INVOICE_NO . '" doc_id="' . $doc->CD_SEQ_NO . '" class="btn btn-success btn-xs detl_appl_btn"><i class="fa fa-info"></i> Info Detail</button>
								</td>
								</tr>';
								 }
								 } else {
									echo '<tr><td colspan="9" class="text-center">No record found.</td></tr>';
								 }
					?>
					</tbody>
				</table>	
			</div>
		</div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default back_btn">Back</button>
    </div>
</form>	