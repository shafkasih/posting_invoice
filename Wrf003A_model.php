<?php
/** 
  * @version 1.0
  * @author Siti Nabilah Huda binti Razak
  * @required Wrf003A_model
*/
defined('BASEPATH') OR exit('No direct script access allowed');
/*--------------------------------------------------------------
  @Load Wrf003A_model 
  --------------------------------------------------------------*/
class Wrf003A_model extends CI_Model {

private $staff_id;
    public function __construct() {
        $this->load->database();
		$this->staff_id = $this->lib->userid();
    }
	
/*--------------------------------------------------------------
  @operation get invoice(first page)
 ---------------------------------------------------------------*/
	public function getInvoiceDetail($deptCode, $territoryCode){
		if($deptCode==NULL && $territoryCode!=NULL){
		$query="select *
		from cinvoice_head
		where ch_status = 'VERIFY'
		and ch_cust_type ='$deptCode'
		and substr(ch_invoice_no,1,6)= '$territoryCode'";
		$del=$this->db->query($query);
		return $del->result_case('UPPER');
		}
		if($deptCode!=NULL && $territoryCode==NULL){
		$query="select *
		from cinvoice_head
		where ch_status = 'VERIFY'
		and ch_cust_type ='$deptCode'
		and substr(ch_invoice_no,1,6)= '$territoryCode'";
		$del=$this->db->query($query);
		return $del->result_case('UPPER');
		}
		if($deptCode!=NULL && $territoryCode!=NULL){
		$query="select *
		from cinvoice_head
		where ch_status = 'VERIFY'
		and ch_cust_type ='$deptCode'
		and substr(ch_invoice_no,1,6)='$territoryCode'";
		$del=$this->db->query($query);
		return $del->result_case('UPPER');
		}
		if($deptCode==NULL && $territoryCode==NULL){
		$query="select *
		from cinvoice_head
		where ch_status = 'VERIFY'
		and ch_cust_type ='$deptCode'
		and substr(ch_invoice_no,1,6)= '$territoryCode'";
		$del=$this->db->query($query);
		return $del->result_case('UPPER');
		}
		
	}
	
/*--------------------------------------------------------------
  @operation get invoice
 ---------------------------------------------------------------*/	
	public function getInv() {
		
       $this->db->select('ch_cust_type');
	   $this->db->from('cinvoice_head');
	   $this->db->order_by('ch_cust_type asc');
	   $this->db->where('ch_cust_type !=','STUD');
	   return $this->db->get()->result_case('UPPER');
    }

/*--------------------------------------------------------------
  @operation get date invoice
 ---------------------------------------------------------------*/
	public function getInvDate() {
		
		$query="select ch_invoice_date
		from cinvoice_head
		where ch_cust_type !='STUD'
		OR ch_status = 'VERIFY'
		ORDER BY ch_invoice_date DESC";
		$del=$this->db->query($query);
		return $del->result_case('UPPER');
    }

/*--------------------------------------------------------------
  @operation get data invoice
 ---------------------------------------------------------------*/
	public function getInvID($invID){
		
       $this->db->select('*');
	   $this->db->from('cinvoice_head');
	   $this->db->where('ch_invoice_no',$invID);
	   return $this->db->get()->row_case('UPPER');
    }

/*--------------------------------------------------------------
  @operation get detail
 ---------------------------------------------------------------*/
	public function getDetail($invID){
		
       $this->db->select('*');
	   $this->db->from('cinvoice_detl');
	   $this->db->where('cd_invoice_no',$invID);
	   return $this->db->get()->result_case('UPPER');
    }

/*--------------------------------------------------------------
  @operation get updte approve
 ---------------------------------------------------------------*/
	public function update($id,$new_status,$verifyDate,$todayDate,$time){
		
            $data = array(
				
				'ch_approve_by' => $this->staff_id,
                );
				
				$a=$verifyDate." ".$time;
				
				$t1="timestamp'$a'";
				$this->db->set("ch_approve_date",$t1,false);
				
				//$trans="TO_DATE('".$todayDate."','yyyy-mm-dd')";
				$b=$todayDate." ".$time;
				$t2="timestamp'$b'";
				$this->db->set("ch_trans_approve_date",$t2,false);
				
				
				/* $verifyDate2="TO_DATE('".$verifyDate."','DD/MM/YYYY')";
				$this->db->set("CH_APPROVE_DATE",$verifyDate2,false);
				$todayDate2="TO_DATE('".$todayDate."','DD/MM/YYYY')";
				$this->db->set("CH_TRANS_APPROVE_DATE",$todayDate2,false); */

                $this->db->where('ch_invoice_no', $id);
				$detail=$this->db->update('cinvoice_head', $data);
				if($detail==true)
				{
					return 1;
				}else {
					return 0;
					}
	}
/*--------------------------------------------------------------
  @operation get updte approve batch
 ---------------------------------------------------------------*/
	public function update2($id,$new_status2){
		
				$data = array(
				'ch_approve_batch' => $new_status2,
				'ch_status' => "APPROVE"
                );

                $this->db->where('ch_invoice_no', $id);
				$detail=$this->db->update('cinvoice_head', $data);
				if($detail==true)
				{
					return 1;
				}else {
					return 0;
				}
	}	
	
	public function updateD($id){
		
				$data = array(
				'cd_status' => "APPROVE",
                );

                $this->db->where('cd_invoice_no', $id);
				$detail=$this->db->update('cinvoice_detl', $data);
				if($detail==true)
				{
					return 1;
				}else {
					return 0;
				}
	}
	
/*--------------------------------------------------------------
  @operation get count total AMT Detail Invoice
 ---------------------------------------------------------------*/
    public function getCheckDetail($idInvs) {
        $query="select sum(cd_total_amt) as cd_total_amt
		from cinvoice_detl
		where cd_invoice_no ='".$idInvs."'";
        $del=$this->db->query($query);
        return $del->row_case('UPPER')->CD_TOTAL_AMT;
    }
	
/*--------------------------------------------------------------
  @operation get detail header
 ---------------------------------------------------------------*/
	public function getCheckHead($idInvs){
		$this->db->select('*');
		$this->db->from('cinvoice_head');
		$this->db->where('ch_invoice_no',$idInvs);
		//$this->db->limit(20);
		  return $this->db->get()->row_case('UPPER');
	}

/*--------------------------------------------------------------
  @operation create JL No
 ---------------------------------------------------------------*/
	 public function getNextSeq($parmID) {
		/* $seqNo = 0;
		$sql = oci_parse($this->db->conn_id, "begin :bindOutput1 := finance.getnumber(:bind1); end;");
		oci_bind_by_name($sql, ":bind1", $parmID, 10);	//IN
		oci_bind_by_name($sql, ":bindOutput1", $seqNo, 12);				//OUT
		oci_execute($sql, OCI_DEFAULT); 
		
		if (!empty($seqNo)) {
			return $seqNo;
		}
		
		return 0;	 */
		
		$query ="select finance_getnumber ('".$parmID."') AS seqNo ";
		$data =$this->db->query($query);
		return $data->row_case('UPPER')->SEQNO;
    }
	
/*--------------------------------------------------------------
  @operation get creat JL header
 ---------------------------------------------------------------*/
	public function addGL($CH_TOTAL_AMT,$InvNo,$seqNo) {
      $data=array(
        "jh_journal_id"=>$seqNo,
		"jh_cmpy_code"=>"UPSI",
		"jh_enter_by"=>strtoupper($this->staff_id),
		"jh_verify_by"=>strtoupper($this->staff_id),
		"jh_post_by"=>strtoupper($this->staff_id),
		"jh_total_amt"=>$CH_TOTAL_AMT,
		"jh_status"=>"POSTED",
		//"JH_DESC"=>$form['CH_STATUS'],
		"jh_system_id"=>"BL"
		);
		
		$desc='INVOICE POSTING FOR '. $InvNo;
		$this->db->set("jh_desc", $desc, TRUE);
        return $this->db->insert('journal_head',$data);
	} 
	
/*--------------------------------------------------------------
  @operation get GL Account Report - > 8 Segment
 ---------------------------------------------------------------*/
	public function getCount($idInvs) {
		
		$query="select cd_glacct_code_report
		from cinvoice_detl
		where cd_invoice_no ='".$idInvs."'
		group by cd_glacct_code_report";
        $del=$this->db->query($query);
        return $del->num_rows();
	
    }
	
/*--------------------------------------------------------------
  @operation get detail invoice
 ---------------------------------------------------------------*/
	/* public function getdetailCck($i,$idInvs){
		$this->db->select('*');
		$this->db->from('V_BL_CTJL');
		$this->db->where('CH_INVOICE_NO',$idInvs);
		$this->db->where('RN',$i);
		//$this->db->limit(20);
		return $this->db->get()->row_case('UPPER');
	} */	
	
	public function getdetailCck($idInvs){
		$this->db->select('*');
		$this->db->from('v_bl_ctjl');
		$this->db->where('ch_invoice_no',$idInvs);
		return $this->db->get()->result_case('UPPER');
	}	

/*--------------------------------------------------------------
  @operation get sequance no JL
 ---------------------------------------------------------------*/
	// public function getMAX() {
	// $this->db->select_max('JD_ID_SEQ');
	// $result = $this->db->get('JOURNAL_DETL')->row_case('UPPER');  
	// return $result->JD_ID_SEQ;
	// }

/*--------------------------------------------------------------
  @operation create GL detail CR
 ---------------------------------------------------------------*/
	public function addDGL($JD_CMPY_CODE,$JD_GLACCT_CODE,$JD_JOURNAL_ID,$JD_TRANS_AMT,$JD_PROJECT_CODE,
	$JD_REFERENCE,$JD_ID_SEQ,$JD_BRANCH,$JD_SUBBRANCH,$JD_FUND,$JD_COSTCTR,$JD_VOT,$JD_TRANS_DATE,
	$JD_DOC_REF,$JD_TRANS_TYPE_CR,$JD_ACCOUNT_CODE) {
      $data=array(
        "jd_cmpy_code"=>$JD_CMPY_CODE,
        "jd_glacct_code2"=>$JD_GLACCT_CODE,
        "jd_journal_id"=>$JD_JOURNAL_ID,
        "jd_trans_amt"=>$JD_TRANS_AMT,
        "jd_project_code"=>$JD_PROJECT_CODE,
        "jd_reference"=>$JD_REFERENCE,
        "jd_branch"=>$JD_BRANCH,
        "jd_subbranch"=>$JD_SUBBRANCH,
        "jd_fund"=>$JD_FUND,
        "jd_costctr"=>$JD_COSTCTR,
        "jd_vot"=>$JD_VOT,
        "jd_doc_ref"=>$JD_DOC_REF,
        "jd_trans_type"=>$JD_TRANS_TYPE_CR,
        "jd_account_code"=>$JD_ACCOUNT_CODE
		
		);
		
		$this->db->set("jd_id_seq", $JD_ID_SEQ, false);
		$this->db->set("jd_trans_date",$JD_TRANS_DATE,false);
        return $this->db->insert('journal_detl',$data);
	} 	
	
/*--------------------------------------------------------------
  @operation create GL detail DT
 ---------------------------------------------------------------*/
	public function addDGL_DT($JD_CMPY_CODE,$JD_GLACCT_CODE,$JD_JOURNAL_ID,$JD_TRANS_AMT,$JD_PROJECT_CODE,
	$JD_REFERENCE,$JD_ID_SEQ,$JD_BRANCH,$JD_SUBBRANCH,$JD_FUND,$JD_COSTCTR,$JD_VOT,$JD_TRANS_DATE,
	$JD_DOC_REF,$JD_TRANS_TYPE_DT,$JD_ACCOUNT_CODE_DT) {
		
      $data=array(
        "jd_cmpy_code"=>$JD_CMPY_CODE,
        "jd_journal_id"=>$JD_JOURNAL_ID,
        "jd_trans_amt"=>$JD_TRANS_AMT,
        "jd_project_code"=>$JD_PROJECT_CODE,
        "jd_reference"=>$JD_REFERENCE,
        "jd_branch"=>$JD_BRANCH,
        "jd_subbranch"=>$JD_SUBBRANCH,
        "jd_fund"=>$JD_FUND,
        "jd_costctr"=>$JD_COSTCTR,
        "jd_vot"=>$JD_VOT,
        "jd_doc_ref"=>$JD_DOC_REF,
        "jd_trans_type"=>$JD_TRANS_TYPE_DT,
        "jd_account_code"=>$JD_ACCOUNT_CODE_DT
		
		);
		if($JD_PROJECT_CODE=='-'){
			$dt=$JD_CMPY_CODE."-".$JD_BRANCH."-".$JD_SUBBRANCH."-".$JD_FUND."-".$JD_COSTCTR."-".$JD_VOT."-".$JD_ACCOUNT_CODE_DT;
			$this->db->set("jd_glacct_code2",$dt,true);
		}else{
		$dt=$JD_CMPY_CODE."-".$JD_BRANCH."-".$JD_SUBBRANCH."-".$JD_FUND."-".$JD_COSTCTR."-".$JD_PROJECT_CODE."-".$JD_VOT."-".$JD_ACCOUNT_CODE_DT;
		$this->db->set("jd_glacct_code2",$dt,true);
		}
		$this->db->set("jd_id_seq", $JD_ID_SEQ, false);
		$this->db->set("jd_trans_date",$JD_TRANS_DATE,false);
        return $this->db->insert('journal_detl',$data);
	} 
	
/*--------------------------------------------------------------
  @operation get detail
 ---------------------------------------------------------------*/
	public function getInvoiceDetail3($type_id,$doc_id) {
       $this->db->select('*');
	   $this->db->from('cinvoice_detl');
	   $this->db->where('cd_invoice_no=',$type_id);
	   $this->db->where('cd_seq_no=',$doc_id );
	   return $this->db->get()->row_case('UPPER');
    }
	
	public function getTimeSys() {
        $query="select to_char
			(now(), 'HH24:MI:SS') as NOW";
        $del=$this->db->query($query);
        return $del->row_case('UPPER')->NOW;
    }
}
//---------------------------------------------------------------
// @end of process
// @22/2/2019
//---------------------------------------------------------------
?>