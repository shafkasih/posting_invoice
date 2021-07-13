<?php
/** 
  * @version 1.0
  * @include index(), applicationList(), viewTabFilter(), detlApplication(),approve(),check(),viewDetail()
  * @author Siti Nabilah Huda binti Razak
  * @required Wrf003A.php
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Wrf003A extends MY_Controller {
private $staff_id;
    function __construct() {
        parent::__construct();
        $this->load->model('Wrf003A_model','fee');
		$this->staff_id = $this->lib->userid();
    }

/*--------------------------------------------------------------
  @Run funtion index() for view main page with searching
  @operation get parameter date and cust type for parsing to applicationList
  --------------------------------------------------------------*/
	public function index($program=null,$date=null){
		$this->session->set_userdata('referer',current_url());
		 $this->session->set_userdata('usDept','');
        $this->session->set_userdata('usTerritory','');
		
		if (empty($program)) {
			if (!empty($this->session->usDept)) {
				$program = $this->session->usDept;
			} else {
				$program=NULL;
			}
       	}
        $data['default_dept'] = $program;

    	        
        if (empty($date)) {
			if (!empty($this->session->usTerritory)) {
				$date = $this->session->usTerritory;
			} 
			else {
				$date=NULL;
			}
       	}
        $data['default_territory'] = $date;
		$data['program'] = $this->dropdown($this->fee->getInv(),'CH_CUST_TYPE','CH_CUST_TYPE','---Please Select---');
		$data['date'] = $this->dropdown($this->fee->getInvDate(),'CH_INVOICE_DATE','CH_INVOICE_DATE','---Please Select---');
		
		$this->render($data);
	}

/*--------------------------------------------------------------
  @Run funtion applicationList() view table of invoice
  @operation view invoice 
  --------------------------------------------------------------*/
	public function applicationList(){
		// get parameter values
		$type_id = $this->input->post('dCode',true);	
		$dtINT = $this->input->post('tCode',true);
		
		$parmID= 'BL' . substr($dtINT,2,2). substr($dtINT,5,2);
		// get available records
		$data['programs'] = $this->fee->getInvoiceDetail($type_id,$parmID);
		
        $this->renderAjax($data);
    }// applicationList()

/*--------------------------------------------------------------
  @Run funtion viewTabFilter() 
  @operation sorting data 
  --------------------------------------------------------------*/
	public function viewTabFilter($tabID) {
		// set session
		$this->session->set_userdata('tabID', $tabID);
		
        redirect($this->class_uri('index'));
    } //viewTabFilter()

/*--------------------------------------------------------------
  @Run funtion detlApplication() 
  @operation view detail invoice using No BL
  --------------------------------------------------------------*/
	public function detlApplication(){
		// get parameter values
		$invID = $this->input->post('invoiceID',true);
		
		$data['invoiceID'] = $invID;
		$data['P'] = $this->fee->getInvID($invID);
		$data['doc_rec'] = $this->fee->getDetail($invID);
		
        $this->renderAjax($data);
    }//delApplication()
	
/*--------------------------------------------------------------
  @Run funtion approve() 
  @operation approve processing
  --------------------------------------------------------------*/
	    public function approve(){
		$this->isAjax();
        $id = $this->input->post('inv_id2',true);
        $todayDate = $this->input->post('todayDate',true);
        $todayDate2 = $this->input->post('todayDate2',true);
        $new_status = $this->input->post('new_status',true);
        $backdate = $this->input->post('backdate',true);
		$time=$this->fee->getTimeSys();
		
		
		$idInvs=substr($id,1,11);
        
		if($backdate==NULL){
			
			$todayM=substr($todayDate,2,2);
			$invM=substr($id,5,2);
			
			$todayY=substr($todayDate,6,2);
			$invY=substr($id,3,2);
			
			$convertM=$todayM-$invM;
			$convertY=$todayY-$invY;
		
			if($convertY==0)
			{
				if($convertM==0)
				{
						if($backdate==NULL)
						{
							$verifyDate=$todayDate2;
						}
						else{
							
							$year=substr($backdate,0,4);
							$month=substr($backdate,5,2);
							$day=substr($backdate,8,2);
							
							$verifyDate=substr($backdate,0,4)."-". substr($backdate,5,2)."-".substr($backdate,8,2);
							
						}
						
						$update=$this->fee->update($idInvs,$new_status,$verifyDate,$todayDate2,$time);
						if($update==1){
							//INSERT HEAD
							$checkHead=$this->fee->getCheckHead($idInvs);
							$parmID = 'JL' . substr($id,3,2). substr($id,5,2);
							$seqNo = $this->fee->getNextSeq($parmID);
							
							$a=$verifyDate." ".$time;
							$verifyDate2="timestamp'$a'";
							
							$this->db->set("jh_enter_date",$verifyDate2,false);
							$this->db->set("jh_verify_date",$verifyDate2,false);
							$this->db->set("jh_post_date",$verifyDate2,false);
							$P=$checkHead->CH_TOTAL_AMT;
							//create GL
							$insert = $this->fee->addGL($P,$idInvs,$seqNo);
							
							//INSERT DETAIL
							$count = $this->fee->getCount($idInvs);
							if(empty($count))
							{
								$json = array('sts' => 0, 'msg' => 'Success to Approve. But cant create GL because no Total AMT');
							}else{
								
							/* 	$k=0;
							for($i=1;$i<=$count;$i++){
								$detailCck[$k]= $this->fee->getdetailCck($i,$idInvs);
								$k=$k+1;
							
							} */
							$detailCck2= $this->fee->getdetailCck($idInvs);
							
							foreach($detailCck2 as $detailCck){
									// $MAX=$this->fee->getMAX();
									$JD_CMPY_CODE= $detailCck->CD_COMPANY;
									$JD_GLACCT_CODE= $detailCck->CD_GLACCT_CODE_REPORT;
									$JD_JOURNAL_ID= $seqNo;
									$JD_TRANS_AMT= $detailCck->CD_TOTAL_AMT;
									$JD_PROJECT_CODE= $detailCck->CD_PROJECT_CODE;
									$JD_REFERENCE= $detailCck->CD_DETAIL_DESC;
									$JD_ID_SEQ="nextval('journal_detl_seq')";
									$JD_BRANCH= $detailCck->CD_BRANCH;
									$JD_SUBBRANCH= $detailCck->CD_SUB_BRANCH;
									$JD_FUND= $detailCck->CD_FUND;
									$JD_COSTCTR= $detailCck->CD_COST_CENTER;
									$JD_VOT= $detailCck->CD_VOT;
									$JD_TRANS_DATE= $verifyDate2;
									$JD_DOC_REF= $idInvs;
									$JD_TRANS_TYPE_CR= "CR";
									$JD_TRANS_TYPE_DT= "DT";
									//$JD_INVOICE_SEQ_NO=$z;
									$JD_ACCOUNT_CODE=$detailCck->CD_ACCOUNT_CODE;
									$JD_ACCOUNT_CODE_DT=$detailCck->CH_GLACCT_CODE;
									
									$insertD = $this->fee->addDGL($JD_CMPY_CODE,
									$JD_GLACCT_CODE,
									$JD_JOURNAL_ID,
									$JD_TRANS_AMT,
									$JD_PROJECT_CODE,
									$JD_REFERENCE,
									$JD_ID_SEQ,
									$JD_BRANCH,
									$JD_SUBBRANCH,
									$JD_FUND,
									$JD_COSTCTR,
									$JD_VOT,
									$JD_TRANS_DATE,
									$JD_DOC_REF,
									$JD_TRANS_TYPE_CR,$JD_ACCOUNT_CODE);
									
									$insertD2 = $this->fee->addDGL_DT($JD_CMPY_CODE,
									$JD_GLACCT_CODE,
									$JD_JOURNAL_ID,
									$JD_TRANS_AMT,
									$JD_PROJECT_CODE,
									$JD_REFERENCE,
									$JD_ID_SEQ,
									$JD_BRANCH,
									$JD_SUBBRANCH,
									$JD_FUND,
									$JD_COSTCTR,
									$JD_VOT,
									$JD_TRANS_DATE,
									$JD_DOC_REF,
									$JD_TRANS_TYPE_DT,$JD_ACCOUNT_CODE_DT);
									
									
									if($insertD>0 && $insertD2>0){
									//$new_status2="POSTED";
									$update2=$this->fee->update2($idInvs,$seqNo);
									$update3=$this->fee->updateD($idInvs);
									$json = array('sts' => 0, 'msg' => 'Success to Approve. GL insert');
								}else{
									$json = array('sts' => 1, 'msg' => 'Fail to Record, Please Contact Administator');
								}
							}
								
							}
							
							
						}
						else{
							$json = array('sts' => 1, 'msg' => 'Fail to Record, Please Contact Administator');
						}
				}else{
					$json = array('sts' => 1, 'msg' =>'Please insert Transaction Date:  ' .$idInvs );
				}
				}else{
					$json = array('sts' => 1, 'msg' =>'Please insert Transaction Date:  ' .$idInvs );
					}
		}	
		else if($backdate!=NULL){
					
				$todayM2=substr($backdate,5,2);
				$invM=substr($id,5,2);
				
				$todayY2=substr($backdate,2,2);
				$invY=substr($id,3,2);
				
				$convertM2=$todayM2-$invM;
				$convertY2=$todayY2-$invY;
				if($convertY2==0)
				{
					if($convertM2==0)
						{
							$year=substr($backdate,0,4);
							$month=substr($backdate,5,2);
							$day=substr($backdate,8,2);
							
							$verifyDate=substr($backdate,0,4)."-". substr($backdate,5,2)."-".substr($backdate,8,2);
							
							$update=$this->fee->update($idInvs,$new_status,$verifyDate,$todayDate2,$time);
						
						if($update==1){
							
							//INSERT HEAD
							$checkHead=$this->fee->getCheckHead($idInvs);
							$parmID = 'JL' . substr($id,3,2). substr($id,5,2);
							$seqNo = $this->fee->getNextSeq($parmID);
							
							$a=$verifyDate." ".$time;
							$verifyDate2="timestamp'$a'";
							
							$this->db->set("jh_enter_date",$verifyDate2,false);
							$this->db->set("jh_verify_date",$verifyDate2,false);
							$this->db->set("jh_post_date",$verifyDate2,false);
							$P=$checkHead->CH_TOTAL_AMT;
							//create GL
							$insert = $this->fee->addGL($P,$idInvs,$seqNo);
							
							//INSERT DETAIL
							$count = $this->fee->getCount($idInvs);
							if(empty($count))
							{
								$json = array('sts' => 0, 'msg' => 'Success to Approve. But cant create GL because no Total AMT');
							}else{
								
							/* 	$k=0;
							for($i=1;$i<=$count;$i++){
								$detailCck[$k]= $this->fee->getdetailCck($i,$idInvs);
								$k=$k+1;
							
							}
							$s=0; */
							$detailCck2= $this->fee->getdetailCck($idInvs);
							foreach($detailCck2 as $detailCck){
									// $MAX=$this->fee->getMAX();
									$JD_CMPY_CODE= $detailCck->CD_COMPANY;
									$JD_GLACCT_CODE= $detailCck->CD_GLACCT_CODE_REPORT;
									$JD_JOURNAL_ID= $seqNo;
									$JD_TRANS_AMT= $detailCck->CD_TOTAL_AMT;
									$JD_PROJECT_CODE= $detailCck->CD_PROJECT_CODE;
									$JD_REFERENCE= $detailCck->CD_DETAIL_DESC;
									$JD_ID_SEQ="nextval('journal_detl_seq')";
									$JD_BRANCH= $detailCck->CD_BRANCH;
									$JD_SUBBRANCH= $detailCck->CD_SUB_BRANCH;
									$JD_FUND= $detailCck->CD_FUND;
									$JD_COSTCTR= $detailCck->CD_COST_CENTER;
									$JD_VOT= $detailCck->CD_VOT;
									$JD_TRANS_DATE= $verifyDate2;
									$JD_DOC_REF= $idInvs;
									$JD_TRANS_TYPE_CR= "CR";
									$JD_TRANS_TYPE_DT= "DT";
									//$JD_INVOICE_SEQ_NO=$z;
									$JD_ACCOUNT_CODE=$detailCck->CD_ACCOUNT_CODE;
									$JD_ACCOUNT_CODE_DT=$detailCck->CH_GLACCT_CODE;
									
									$insertD = $this->fee->addDGL($JD_CMPY_CODE,
									$JD_GLACCT_CODE,
									$JD_JOURNAL_ID,
									$JD_TRANS_AMT,
									$JD_PROJECT_CODE,
									$JD_REFERENCE,
									$JD_ID_SEQ,
									$JD_BRANCH,
									$JD_SUBBRANCH,
									$JD_FUND,
									$JD_COSTCTR,
									$JD_VOT,
									$JD_TRANS_DATE,
									$JD_DOC_REF,
									$JD_TRANS_TYPE_CR,$JD_ACCOUNT_CODE);
									
									$insertD2 = $this->fee->addDGL_DT($JD_CMPY_CODE,
									$JD_GLACCT_CODE,
									$JD_JOURNAL_ID,
									$JD_TRANS_AMT,
									$JD_PROJECT_CODE,
									$JD_REFERENCE,
									$JD_ID_SEQ,
									$JD_BRANCH,
									$JD_SUBBRANCH,
									$JD_FUND,
									$JD_COSTCTR,
									$JD_VOT,
									$JD_TRANS_DATE,
									$JD_DOC_REF,
									$JD_TRANS_TYPE_DT,$JD_ACCOUNT_CODE_DT);
									
									
									if($insertD>0 && $insertD2>0){
									//$new_status2="POSTED";
									$update2=$this->fee->update2($idInvs,$seqNo);
									$update3=$this->fee->updateD($idInvs);
									$json = array('sts' => 0, 'msg' => 'Success to Approve. GL insert');
								}else{
									$json = array('sts' => 1, 'msg' => 'Fail to Record, Please Contact Administator');
								}
							}
								
							}
							
						}
						else{
							$json = array('sts' => 1, 'msg' => 'Fail to Record, Please Contact Administator');
						}
					}else{
						$json = array('sts' => 1, 'msg' =>'Please insert Transaction Date Properly:  ' .$idInvs );
					}
				}else{
					$json = array('sts' => 1, 'msg' =>'Please insert Transaction Date Properly:  ' .$idInvs );
					}
			}	
		
        echo json_encode($json);
		
		}//approve()
		
/*--------------------------------------------------------------
  @Run funtion check() 
  @operation for checking invoice
  --------------------------------------------------------------*/
		public function check(){
		$this->isAjax();
        $id = $this->input->post('inv_id2',true);
        $todayDate = $this->input->post('todayDate',true);
        $new_status = $this->input->post('new_status',true);
        $backdate = $this->input->post('backdate',true);
		
		$idInvs=substr($id,1,11);
        
		if($backdate==NULL){
			
			$todayM=substr($todayDate,2,2);
			$invM=substr($id,5,2);
			
			$todayY=substr($todayDate,6,2);
			$invY=substr($id,3,2);
			
			$convertM=$todayM-$invM;
			$convertY=$todayY-$invY;
		
			if($convertY==0)
			{
				if($convertM==0)
				{
						
					$json = array('sts' => 1, 'msg' => 'Success to Verify.');
				}
				else{
					$json = array('sts' => 0, 'msg' => 'Please insert Posting Date  ' );
				}
			}else{
				$json = array('sts' => 0, 'msg' =>'Please insert Posting Date  ' );
			}
				
		}	
		else if($backdate!=NULL){
					
				$todayM2=substr($backdate,5,2);
				$invM=substr($id,5,2);
				
				$todayY2=substr($backdate,2,2);
				$invY=substr($id,3,2);
				
				$convertM2=$todayM2-$invM;
				$convertY2=$todayY2-$invY;
				if($convertY2==0)
				{
					if($convertM2==0)
						{
							$json = array('sts' => 1, 'msg' => 'Success to Record.');
						}
						else{
							$json = array('sts' => 0, 'msg' => 'Please insert Posting Date Properly  '  );
						}
					}else{
						$json = array('sts' => 0, 'msg' =>'Please insert Posting Date Properly  '  );
					}
				
			}	
		
        echo json_encode($json);
		
		}//check()
		
/*--------------------------------------------------------------
  @Run funtion viewDetail() 
  @operation for view Detail Invoice
  --------------------------------------------------------------*/
	public function viewDetail(){
		$type_id = $this->input->post('type_id',true);
		$doc_id = $this->input->post('doc_id',true);
		$data['P'] = $this->fee->getInvoiceDetail3($type_id,$doc_id);
		$this->render($data);
	}//viewDetail()

}
//---------------------------------------------------------------
// @end of process
// @22/2/2019
//---------------------------------------------------------------	
?>