<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Controller {

	public $systemsetup = null;
	
	public function __construct() {
		parent::__construct();
        date_default_timezone_set("Asia/Manila");
		ini_set('max_execution_time', 300);
		//ISLOGIN
		$this->load->database();
		$this->load->model('Appmodel');
		$this->systemsetup = $this->Appmodel->systemsetup();
	}
	public function getmac()
	{
		
		ob_start(); // Turn on output buffering
		system('ipconfig /all'); //Execute external program to display output
		$mycom=ob_get_contents(); // Capture the output into a variable
		ob_clean(); // Clean (erase) the output buffer
		$findme = "Physical";
		$pmac = strpos($mycom, $findme); // Find the position of Physical text
		$mac=substr($mycom,($pmac+36),17); // Get Physical Address
		return $mac;
	}
	public function isloggedin()
	{
		$this->load->library('session');
		
		if(!$this->session->userdata('PK_users')):
			return FALSE;
		else:
			return TRUE;
		endif;
	}
	public function getuserphonebook()
	{
		$data = json_decode(file_get_contents("php://input")); 
		$this->load->database();
		$this->load->model('Appmodel');
		echo json_encode($this->Appmodel->getuserphonebook($data->id)); //$this->session->userdata('PK_users'));
	}
	public function getgroupphonebook()
	{
		$data = json_decode(file_get_contents("php://input")); 
		$this->load->database();
		$this->load->model('Appmodel');
		echo json_encode($this->Appmodel->getgroupphonebook($data->id)); //$this->session->userdata('PK_users'));
	}
	public function isloggedinjs()
	{
		$this->load->library('session');
		if(!$this->session->userdata('PK_users')):
			echo json_encode(array('loggedin'=>'0'));
		else:
			echo json_encode(array('loggedin'=>'1'));
		endif;
	}
	public function index()
	{
		$this->load->helper('url');
		$this->load->model('Appmodel');
		$this->Appmodel->updatemac($this->getmac());
		$currurl = current_url();
		$url1 = base_url().'app/';
		$url2 = base_url().'app';
		$url4 = base_url().'app/index';
		$url3 = base_url().'app/index/';
		if($currurl == $url1 || $currurl == $url2 || $currurl == $url3 || $currurl == $url4): 
			redirect('');
		else:
			$systemsetup = $this->Appmodel->systemsetup();
			if(strlen($systemsetup['key']) == 19):
				//$this->load->view('header');
				//$this->load->view('sched');
				$url = getcwd();
				$url = $url."/application/third_party/license.json";
				if(file_exists($url)):
					$data = file_get_contents($url);
					$data = json_decode($data);
					if($data->licensekey == md5($systemsetup['key']) && $data->macaddress == $data->macaddress): //md5($systemsetup['macaddress']) == $data->macaddress):
						//echo $data->macaddress;
						//echo md5($systemsetup['macaddress']);
						if($data->istrial == 1):
							$datetoday = strtotime(date("Y-m-d"));
							$expired = strtotime($data->expired);
							$diff = $expired - $datetoday;
							$diff = $diff/24/60/60;
							if($datetoday < $expired):
								$this->load->view('header');
								$this->load->view('sched');
							else:
								$this->load->view('licensing');
							endif;
						else:
							$this->load->view('header');
							$this->load->view('sched');
						endif;
						
						//$this->load->view('header');
						//$this->load->view('sched');
					else:
						$this->load->view('licensing');
					endif;
				else:
					$this->load->view('licensing');
				endif;
			else:
				$this->load->view('licensing');
			endif;
		endif;
		//
		//$this->load->view('sched');
		//
		//
	}
	public function licensegenerate()
	{
		$rand = strtoupper(substr(md5(microtime()),rand(0,26),4))."-".strtoupper(substr(md5(microtime()),rand(0,26),4))."-".strtoupper(substr(md5(microtime()),rand(0,26),4))."-".strtoupper(substr(md5(microtime()),rand(0,26),4));
		echo $rand;
	}
	public function login()
	{
		$this->load->helper('url');
		$this->load->view('login');
	}
	public function checklogin()
	{
		//AJAX
		$data = json_decode(file_get_contents("php://input")); 
		$this->load->database();
		$this->load->library('session');
		$this->load->model('Appmodel');
		if($this->Appmodel->checklogin($data->username, $data->password) !== FALSE):
			echo json_encode(array('status'=>'1'));
		else:
			echo json_encode(array('status'=>'0'));
		endif;
	}
	public function admin()
	{
		
		if($this->isloggedin() == TRUE):
			$this->load->helper('url');
			$this->load->library('session');
			$var['fullname'] = $this->session->userdata('fullname');
			//print_r($this->session->userdata());
			//echo $var['fullname'];
			$this->load->view('header');
			//$this->load->view('sched');
			$this->load->view('home',$var);
		else:
			$this->login();
		endif;
	}
	public function getphonebook()
	{
		//AJAX REQUEST
		$this->load->library('session');
		$this->load->database();
		$this->load->model('Appmodel');
		$phonebook = $this->Appmodel->getphonebook($this->session->userdata('PK_users'));
		echo json_encode($phonebook);
	}
	
	public function getgroups()
	{
		//AJAX REQUEST
		$this->load->library('session');
		$this->load->database();
		$this->load->model('Appmodel');
		$phonebook = $this->Appmodel->getgroups($this->session->userdata('PK_users'));
		echo json_encode($phonebook);
	}
	public function getmsgdtls()
	{
		//AJAX REQUEST
		$this->load->database();
		$this->load->model('Appmodel');
		$this->load->library('session');
		$msgdtls = $this->Appmodel->getmsgdtls();
		echo json_encode($msgdtls);
	}
	public function reportdetails()
	{
		//AJAX REQUEST
		$this->load->database();
		$this->load->model('Appmodel');
		$msgdtls = $this->Appmodel->reportdetails();
		echo json_encode($msgdtls);
	}
	public function getrptdtls()
	{
		//AJAX REQUEST
		$this->load->database();
		$this->load->model('Appmodel');
		$msgdtls = $this->Appmodel->getrptdtls();
		echo json_encode($msgdtls);
	}
	public function deletemessages()
	{
		//AJAX REQUEST
		$data = json_decode(file_get_contents("php://input")); 
		$this->load->database();
		$this->load->model('Appmodel');
		$this->Appmodel->deletemessages( $data->number );
	}
	public function messages()
	{
		$this->load->helper(array('url','form'));
		$this->load->view('messages');
	}
	public function sendsms()
	{
		$this->load->library('session');
		$data = json_decode(file_get_contents("php://input")); 
		$FK_users = $this->session->userdata('PK_users');
		$this->load->model('Appmodel');
		$this->load->database();
		$number = $data->number;
		$msg = rawurlencode($data->msg);
		$type = $data->type;
		$scheduled = $data->scheduled;
		$datetime = ($scheduled == '1') ? $data->datetime : date('Y-m-d H:i:s');
		//echo $scheduled;
		$numbers = $this->Appmodel->getnumber($type,$number);
		$systemsetup = $this->systemsetup;
		$gateway = "http://".$systemsetup['gateway'].":".$systemsetup['port']."/sendsms?phone=";
		$this->Appmodel->insertnewmsgs($numbers,$msg,$type,$scheduled,$datetime,$FK_users);
	}

	public function showreportdetails()
	{
		$data = json_decode(file_get_contents("php://input")); 
		$this->load->database();
		$this->load->model('Appmodel');
		$retval = $this->Appmodel->showreportdetails($data->id);
		echo json_encode($retval);
	}
	public function selecteduserreport()
	{
		$data = json_decode(file_get_contents("php://input")); 
		$this->load->database();
		$this->load->model('Appmodel');
		$this->Appmodel->saveuserreport($data->reportid, $data->reportusers);
	}
	public function sendschedsms($PK_schedmsgs,$number,$msg, $gateway, $port, $password)
	{
		$number = $number;
		$msg = rawurlencode($msg);
		$systemsetup = $this->systemsetup;

		$url = "http://".$systemsetup['gateway'].":".$systemsetup['port']."/sendsms?phone=".$number."&text=".$msg."&password=".$systemsetup['password'];
		echo $url;
		//$contents =  file_get_contents($url);
		if(strpos($contents,"SENT")!== FALSE):
			$this->load->database();
			$this->load->model('Appmodel');
			$this->Appmodel->updateschedmsgs($PK_schedmsgs,$number,$msg);
			echo json_encode(array('status'=>'1'));
		else:
			echo json_encode(array('status'=>'0'));
		endif;
	}
	public function serverdatetime()
	{
		echo json_encode(array('datetime'=>date('Y-m-d h:i:s')));
	}
	public function schedmsg()
	{
		$this->load->database();
		$this->load->model('Appmodel');
		$rows = $this->Appmodel->schedmsg();
		//print "<pre>";
		//print_r($rows);
		//print "</pre>";
		$systemsetup = $this->systemsetup;
		if($rows[0] !== FALSE):
			if(strlen($rows[0][0]['msg']) <= 160):
				$smsstatus = $this->sendschedmsg($rows[0][0]['PK_schedmsgs'],$rows[0][0]['number'],$rows[0][0]['msg'],$rows[1][0]['gateway'],$rows[1][0]['port'],$rows[1][0]['password']);
				$retval = array(
					'0'=>array('interval'=>5000),
					'1'=>$smsstatus
				);
			else:
				$smsstatus = $this->sendschedmsg($rows[0][0]['PK_schedmsgs'],$rows[0][0]['number'],$rows[0][0]['msg'],$rows[1][0]['gateway'],$rows[1][0]['port'],$rows[1][0]['password']);
				$retval = array(
					'0'=>array('interval'=>20000),
					'1'=>$smsstatus
				);
			endif;
		else:
			$retval = array(
					'0'=>array('interval'=>5000),
					'1'=>array('status'=>'3')
			);
		endif;
		
		
		
		//ADDTIONAL ALGORITHM
		if(isset($smsstatus)):
			if($smsstatus['status'] == '1'):
				$txt = date("Y-m-d H:i:s")." - SMSInfoBlast Sending Message to ".$rows[0][0]['number'].".\n";
			elseif($smsstatus['status'] == '2'):
				$txt = date("Y-m-d H:i:s")." - SMSInfoBlast SMS Gateway not reachable at http://".$systemsetup['gateway'].":".$systemsetup['port']."/.\n";
			elseif($smsstatus['status'] == '0'):
				$txt = date("Y-m-d H:i:s")." - SMSInfoBlast Sending failed with Tracking No: ".$rows[0][0]['PK_schedmsgs'].".\n";
			endif;
		else:
			$txt = date("Y-m-d H:i:s")." - SMSInfoBlast Waiting for Scheduled Messages.\n";
		endif;
		$filename = "logs-".date("Y-m-d").".txt";
		$dir = getcwd()."\\application\\logs\\$filename.txt";
		$data = file_get_contents("$dir");

		$txt = $txt.$data;
		$myfile = file_put_contents("$dir", $txt);
		
		echo json_encode($retval);
	}
	public function addlogs()
	{
		$myfile = file_put_contents("$dir", "", FILE_APPEND | LOCK_EX);
		$txt = date("Y-m-d H:i:s")." - SMSInfoBlast Preventing Page from Sleeping.\n";
		$filename = "logs-".date("Y-m-d").".txt";
		$dir = getcwd()."\\application\\logs\\$filename.txt";
		$data = file_get_contents("$dir");

		$txt = $txt.$data;
		$myfile = file_put_contents("$dir", $txt);
	}
	public function sendschedmsg($PK_schedmsgs,$number,$msg, $gateway, $port, $password)
	{
		
		$systemsetup = $this->systemsetup;
		$msg = rawurldecode($msg);
		$url = "http://".$gateway.":".$port."/sendsms?phone=".$number."&text=".rawurlencode($msg)."&password=".$password;
		//echo $url;
		$contents = curl_init($url);
		curl_setopt($contents, CURLOPT_RETURNTRANSFER, true);
		//print_r(curl_exec($contents));
		$gatewayurl = curl_exec($contents);
		$json = '';
		if( ($json = $gatewayurl ) === false):
			return array('status'=>'2');
		else:
			if(strpos($gatewayurl,"SENT") !== FALSE):
				$this->Appmodel->updateschedmsgs($PK_schedmsgs,$number,$msg);
				return array('status'=>'1');
			else:
				return array('status'=>'0');
			endif;
		endif;
		curl_close($contents);
	}
	public function savephonebook()
	{
		//AJAX
		$data = json_decode(file_get_contents("php://input")); 
		$this->load->database();
		$this->load->model('Appmodel');
		if($this->Appmodel->savephonebook($data->id, $data->number, $data->name)):
			echo "1";
		else:
			echo "0";
		endif;
	}
	public function getphonebookdetails()
	{
		//AJAX
		$data = json_decode(file_get_contents("php://input")); 
		$this->load->database();
		$this->load->model('Appmodel');
		if($this->Appmodel->getphonebookdetails($data->id)):
			echo json_encode($this->Appmodel->getphonebookdetails($data->id));
		endif;
		
	}
	public function getautoinput()
	{
		//AJAX
		$this->load->library('session');
		$data = json_decode(file_get_contents("php://input")); 
		$this->load->database();
		$this->load->model('Appmodel');
		echo json_encode($this->Appmodel->getautoinput($data->id,$this->session->userdata('PK_users')));
	}
	public function showusergroup()
	{
		//AJAX
		$data = json_decode(file_get_contents("php://input")); 
		$id = $data->id;
		$this->load->database();
		$this->load->model('Appmodel');
		echo json_encode($this->Appmodel->showusergroup($id));
	}
	public function saveusergroup()
	{
		//AJAX
		$data = json_decode(file_get_contents("php://input")); 
		$this->load->database();
		$this->load->model('Appmodel');
		echo json_encode($this->Appmodel->saveusergroup($data->id, $data->data));
	
		//print_r((array)$data->data);
	}
	public function deletegroups()
	{
		//AJAX
		$data = json_decode(file_get_contents("php://input")); 
		$id = $data->id;
		$this->load->database();
		$this->load->model('Appmodel');
		$this->Appmodel->deletegroups($id);
	}
	public function addnewgroup()
	{
		//AJAX
		$data = json_decode(file_get_contents("php://input")); 
		$id = $data->id;
		//print_r($data);
		$name = $data->name;
		$this->load->database();
		$this->load->model('Appmodel');
		echo json_encode($this->Appmodel->addnewgroup($id,$name));
	}
	public function getgroupname()
	{
		//AJAX
		$data = json_decode(file_get_contents("php://input")); 
		$id = $data->id;
		$this->load->database();
		$this->load->model('Appmodel');
		echo json_encode($this->Appmodel->getgroupname($id));
	}
	public function msgdetails()
	{
		//AJAX
		$data = json_decode(file_get_contents("php://input")); 
		$number = $data->number;
		$this->load->database();
		$this->load->model('Appmodel');
		//$this->Appmodel->getmsgdetails($number);
		echo json_encode($this->Appmodel->getmsgdetailsbyid($number));
	}
	public function deletephonebook()
	{
		//AJAX
		$data = json_decode(file_get_contents("php://input")); 
		$id = $data->id;
		$this->load->database();
		$this->load->model('Appmodel');
		$this->Appmodel->deletephonebook($id);
	}
	public function getallusers()
	{
		//AJAX
		$this->load->database();
		$this->load->model('Appmodel');
		echo json_encode($this->Appmodel->getallusers());
	}
	public function saveusers()
	{
		//AJAX
		$data = json_decode(file_get_contents("php://input")); 
		$this->load->database();
		$this->load->model('Appmodel');
		echo json_encode($this->Appmodel->saveusers($data->id,$data->fullname,$data->username,$data->password,$data->adminflag));
	}
	public function administrator()
	{
		$this->load->database();
		$this->load->library('session');
		$this->load->model('Appmodel');
		echo json_encode($this->Appmodel->getadminflag($this->session->userdata('PK_users')));
	}
	public function inactiveuser()
	{
		//AJAX
		$data = json_decode(file_get_contents("php://input")); 
		$this->load->database();
		$this->load->model('Appmodel');
		echo json_encode($this->Appmodel->inactiveuser($data->id));
	}
	public function getuserdetails()
	{
		//AJAX
		$data = json_decode(file_get_contents("php://input")); 
		$this->load->database();
		$this->load->model('Appmodel');
		echo json_encode($this->Appmodel->getuserdetails($data->id));
	}
	public function getscheduledsms()
	{
		//AJAX
		$data = json_decode(file_get_contents("php://input")); 
		$this->load->library('session');
		$this->load->database();
		$this->load->model('Appmodel');
		echo json_encode($this->Appmodel->getscheduledsms($this->session->userdata('PK_users')));
	}
	public function deleteselectedmsg()
	{
		$data = json_decode(file_get_contents("php://input")); 
		$this->load->library('session');
		$this->load->database();
		$this->load->model('Appmodel');
		$this->Appmodel->deleteselectedmsg($data->id);
	}
	public function changepassword()
	{
		//AJAX
		$data = json_decode(file_get_contents("php://input")); 
		$this->load->library('session');
		$id = $this->session->userdata('PK_users');
		$this->load->database();
		$this->load->model('Appmodel');
		echo json_encode($this->Appmodel->changepassword($id, $data->old, $data->new, $data->confirm));
	}
	public function logout()
	{
		$this->load->library('session');
		$this->session->sess_destroy();
		$this->admin();
	}
	public function useraccess()
	{
		//AJAX
		$data = json_decode(file_get_contents("php://input")); 
		$this->load->database();
		$this->load->model('Appmodel');
		echo json_encode($this->Appmodel->useraccess($data->PK_users));
	}
	public function saveuserphonebook()
	{
		//AJAX
		$data = json_decode(file_get_contents("php://input")); 
		$this->load->database();
		$this->load->model('Appmodel');
		
		$this->Appmodel->saveuserphonebook($data->data);
	}
	public function saveuserpbgroup()
	{
		//AJAX
		$data = json_decode(file_get_contents("php://input")); 
		$this->load->database();
		$this->load->model('Appmodel');
		$this->Appmodel->saveuserpbgroup($data->data);
	}

	public function edituseraccess()
	{
		$data = json_decode(file_get_contents("php://input")); 
		$this->load->database();
		$this->load->model('Appmodel');
		$this->Appmodel->edituseraccess($data->data);
	}
	public function replycenter()
	{
		
		$number = $this->input->get('phone',TRUE);
		$number2 = '09'.substr($number,4,14);
		$number = substr($number,0,4) == '639' ? $number2 : $number;
		$smscenter = $this->input->get('smscenter',TRUE);
		$text = rawurldecode($this->input->get('text',TRUE));
		$msg = rawurlencode("Invalid Text Code");
		$systemsetup = $this->Appmodel->systemsetup();
		$gateway = "http://".$systemsetup['gateway'].":".$systemsetup['port']."/sendsms?phone=";
		$this->load->database();
		$this->load->model('Appmodel');
		$FK_users = 1001;
		if (preg_match('/^([MAEDR] \d)/', $text)):
			$this->Appmodel->msgcenterinbox($number,$text,'1001');
			if($this->Appmodel->getsmscodes($text,$number) !== FALSE):
				$reply = rawurlencode("Thank you for your reply. Code ".$text." has been successfully acknowledged.");
				$url = $gateway.$number."&text=".$reply."&password=".$systemsetup['password'];
				$contents =  curl_init($url);
				curl_setopt($contents, CURLOPT_RETURNTRANSFER, true);
				$gatewayurl = curl_exec($contents);
				$json = '';
				if( ($json = $gatewayurl ) === false):
					if($this->Appmodel->insertschedsms($number,$msg,$FK_users)):
						echo json_encode(array('status'=>'0'));
					endif;
				else:
					if($this->Appmodel->msgcenteroutbox($number,$msg,$FK_users) !== FALSE):
						echo json_encode(array('status'=>'1'));
					else:
						$this->Appmodel->insertschedsms($number,$msg,$FK_users);
						echo json_encode(array('status'=>'0'));
					endif;
				endif;
				curl_close($contents);
			else:
				$url = $gateway.$number."&text=".$msg."&password=".$systemsetup['password'];
				$contents =  curl_init($url);
				curl_setopt($contents, CURLOPT_RETURNTRANSFER, true);
				$gatewayurl = curl_exec($contents);
				$json = '';
				if( ($json = $gatewayurl ) === false):
					if($this->Appmodel->insertschedsms($number,$msg,$FK_users)):
						echo json_encode(array('status'=>'0'));
					endif;
				else:
					if($this->Appmodel->msgcenteroutbox($number,$msg,$FK_users) !== FALSE):
						echo json_encode(array('status'=>'1'));
					else:
						$this->Appmodel->insertschedsms($number,$msg,$FK_users);
						echo json_encode(array('status'=>'0'));
					endif;
				endif;
				curl_close($contents);
			endif;
		elseif($text == 'UNSUBSCRIBE' || $text == 'unsubscribe'):
			$msg = rawurlencode('You Successfully Unsubscribe to TextConnect. You will no longer received message update. Thank you!');
			$number = (substr($number,0, 4) == '+639') ? $number = '09'.substr($number,4,13) : $number;
			$number = (substr($number,0, 3) == '639') ? $number = '09'.substr($number,3,13) : $number;
			$this->Appmodel->unsubscribe($number);
			
			
			$url = $gateway.$number."&text=".$msg."&password=".$systemsetup['password'];
			
			$contents =  curl_init($url);
			curl_setopt($contents, CURLOPT_RETURNTRANSFER, true);
			$gatewayurl = curl_exec($contents);
			$json = '';
			if( ($json = $gatewayurl ) === false):
				
				if($this->Appmodel->insertschedsms($number,$msg,$FK_users)):
					echo json_encode(array('status'=>'0'));
				endif;
			else:
				if($this->Appmodel->msgcenteroutbox($number,$msg,$FK_users) !== FALSE):
					echo json_encode(array('status'=>'1'));
				else:
					$this->Appmodel->insertschedsms($number,$msg,$FK_users);
					echo json_encode(array('status'=>'0'));
				endif;
			endif;
			curl_close($contents);
			
		else:
			//$text="notmatches";
			$this->Appmodel->msgcenterinbox($number,$text,'1001');
			$url = $gateway.$number."&text=".$msg."&password=".$systemsetup['password'];
			
			$contents =  curl_init($url);
			curl_setopt($contents, CURLOPT_RETURNTRANSFER, true);
			$gatewayurl = curl_exec($contents);
			$json = '';
			if( ($json = $gatewayurl ) === false):
				
				if($this->Appmodel->insertschedsms($number,$msg,$FK_users)):
					echo json_encode(array('status'=>'0'));
				endif;
			else:
				if($this->Appmodel->msgcenteroutbox($number,$msg,$FK_users) !== FALSE):
					echo json_encode(array('status'=>'1'));
				else:
					$this->Appmodel->insertschedsms($number,$msg,$FK_users);
					echo json_encode(array('status'=>'0'));
				endif;
			endif;
			curl_close($contents);
		endif;

	}
	public function licensing()
	{
		$licensekey = $this->input->post('licensekey',TRUE);
		$url = "http://112.199.113.2:7080/smsinfoblast/?licensekey=$licensekey";
		$contents =  curl_init($url);
		curl_setopt($contents, CURLOPT_RETURNTRANSFER, true);
		$gatewayurl = curl_exec($contents);
		$json = '';
		if( ($json = $gatewayurl ) === false):
			$gatewayurl = json_encode(array('status'=>0));
		endif;
		curl_close($contents);
		
		$array = json_decode($gatewayurl);
		if($array->status == 1):
			$mac = $this->getmac();
			$this->load->database();
			$this->db->update('systemsetup',array('licensekey'=>"$licensekey"),array('1'=>'1'));
			$url = getcwd();
			$url = str_replace('\\','/',$url);
			$url = $url."/application/third_party/";
			$this->load->helper('directory');
			$map = directory_map($url);
			$content = json_encode(array('licensekey'=>md5($licensekey),'macaddress'=>md5($mac),'istrial'=>$array->istrial,'expired'=>$array->expired));
			$fp = fopen($url."license.json","wb");
			fwrite($fp,$content);
			fclose($fp);
			echo $gatewayurl;
		else:
			echo $gatewayurl;
		endif;
	}
	public function validatelicense()
	{
		$url = getcwd();
		$url = $url."/application/third_party/license.json";
		if(file_exists($url)):
			$data = file_get_contents($url);
			$data = json_decode($data);
			if($data->istrial == 1):
				$datetoday = strtotime(date("Y-m-d"));
				$expired = strtotime($data->expired);
				$diff = $expired - $datetoday;
				$diff = $diff/24/60/60;
				if($datetoday < $expired):
					echo json_encode(array('days'=>$diff));
				else:
					echo json_encode(array('days'=>$diff));
				endif;
			endif;
		endif;
	}
}
