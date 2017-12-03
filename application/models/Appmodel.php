<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Appmodel extends CI_Model {

	public function systemsetup()
	{
		$this->db->select('*');
		$this->db->from('systemsetup');
		$query = $this->db->get();
		$data = FALSE;
		if($query->num_rows() == 1):
			foreach($query->result_array() as $rows):
				$data['gateway'] = $rows['gateway'];
				$data['mynumber'] = $rows['mynumber'];
				$data['port'] = $rows['port'];
				$data['password'] = $rows['password'];
				$data['key'] = $rows['licensekey'];
				$data['macaddress'] = $rows['macaddress'];
			endforeach;
		endif;
		return $data;
	}
	public function deletemessages($number)
	{
		$this->db->where('receipient',$number);
		if($this->db->delete('msgcenter')):
			return TRUE;
		else:
			return FALSE;
		endif;
	}
	public function unsubscribe($number){
		$sql =
		"
		UPDATE x SET x.unsubscribeflag = '1'
		FROM
		(
			SELECT
			a.PK_phonebook,
			a.name,
			a.unsubscribeflag,
			CASE
				WHEN SUBSTRING(a.number,0,5) = '+639' THEN CONCAT('09',SUBSTRING(a.number,5,13))
				ELSE a.number
			END as [number]
			FROM
			phonebook a
		) as x
		WHERE
			x.number = ".$this->db->escape($number)."
		";
		$this->db->query($sql);
	}
	public function getphonebook($FK_users)
	{
		$adminflag = $this->getadminflag($FK_users);
		$where = ($adminflag == 1) ?  "b.FK_users = ".$this->db->escape($FK_users) : "b.FK_users = ".$this->db->escape($FK_users)." AND b.selected = '1'";
		$sql = "
		SELECT
		a.PK_phonebook,
		a.name,
		a.number,
		b.datetime,
		a.unsubscribeflag
		FROM
		(
		SELECT
		a.PK_phonebook,
		a.name,
		a.unsubscribeflag,
		CASE
			WHEN SUBSTRING(a.number,0,5) = '+639' THEN CONCAT('09',SUBSTRING(a.number,5,13))
			ELSE a.number
		END as [number]
		FROM
		phonebook a
		INNER JOIN userphonebook b ON a.PK_phonebook = b.FK_phonebook
		WHERE
			a.isdelete = '0' AND
			$where
			
		) a
		LEFT OUTER JOIN
		(	
			SELECT
			CASE
				WHEN SUBSTRING(a.number,0,5) = '+639' THEN CONCAT('09',SUBSTRING(a.number,5,13))
				ELSE a.number
			END as [number],
			a.datetime
			FROM
			schedmsgs a
			WHERE
			a.PK_schedmsgs
			IN
			(
				SELECT
				MAX(PK_schedmsgs) [PK_schedmsgs]
				FROM
				(
					SELECT
					PK_schedmsgs,
					CASE
						WHEN SUBSTRING(x.number,0,5) = '+639' THEN CONCAT('09',SUBSTRING(x.number,5,13))
						ELSE x.number
					END as [number]
					FROM
					schedmsgs x
				) x
				INNER JOIN 
				(
					SELECT
					DISTINCT
					CASE
						WHEN SUBSTRING(x.number,0,5) = '+639' THEN CONCAT('09',SUBSTRING(x.number,5,13))
						ELSE x.number
					END as [number]
					FROM
					schedmsgs x
				) y ON x.number= y.number
				GROUP BY x.number
			)
		) b ON a.number = b.number
		
		";
		//echo nl2br($sql);
		$query = $this->db->query($sql);
		if($query->num_rows() >= 1):
			return $query->result_array();
		endif;
	}
	public function getuserphonebook($FK_users)
	{
		$sql = 
		"
		INSERT INTO userphonebook (FK_users, FK_phonebook, selected)
		SELECT
		a.PK_users,
		b.PK_phonebook,
		0 [selected]
		FROM
		users a
		INNER JOIN phonebook b ON 1=1
		WHERE
			CONCAT(a.PK_users,'-',b.PK_phonebook) NOT IN
			(
				SELECT 
				CONCAT(x.FK_users,'-',x.FK_phonebook)
				FROM
				userphonebook x
			) AND
			b.isdelete <> '1'
		";
		$this->db->query($sql);
		$this->db->select('a.PK_userphonebook, b.name, b.number, a.selected');
		$this->db->from('userphonebook a');
		$this->db->join('phonebook b','a.FK_phonebook = b.PK_phonebook','INNER');
		$this->db->where('a.FK_users',$FK_users);
		$this->db->where('b.isdelete <>','1');
		$query = $this->db->get();
		if($query->num_rows() >= 1):
			foreach($query->result() as $rows):
				$data[0][] = array(
					"PK_userphonebook"=>$rows->PK_userphonebook,
					"name"=>$rows->name,
					"number"=>$rows->number,
					"selected"=>($rows->selected == '1' ? TRUE : FALSE)
				);
				$data[1][$rows->PK_userphonebook] = ($rows->selected == '1' ? TRUE : FALSE);
			endforeach;
			return $data;
		endif;
	}
	public function getgroupphonebook($FK_users)
	{
		$sql = 
		"
		INSERT INTO userpbgroup (FK_users, FK_groups, selected)
		SELECT
		a.PK_users,
		b.PK_groups,
		0 [selected]
		FROM
		users a
		INNER JOIN groups b ON 1=1
		WHERE
			CONCAT(a.PK_users,'-',b.PK_groups) NOT IN
			(
				SELECT 
				CONCAT(x.FK_users,'-',x.FK_groups)
				FROM
				userpbgroup x
			) AND
			b.isactive = '1'
		";
		$this->db->query($sql);
		$this->db->select('a.PK_userpbgroup, b.description, a.selected');
		$this->db->from('userpbgroup a');
		$this->db->join('groups b','a.FK_groups = b.PK_groups','INNER');
		$this->db->where('a.FK_users',$FK_users);
		$query = $this->db->get();
		if($query->num_rows() >= 1):
			foreach($query->result() as $rows):
				$data[0][] = array(
					"PK_userpbgroup"=>$rows->PK_userpbgroup,
					"name"=>$rows->description,
					"selected"=>($rows->selected == '1' ? TRUE : FALSE)
				);
				$data[1][$rows->PK_userpbgroup] = ($rows->selected == '1' ? TRUE : FALSE);
			endforeach;
			return $data;
		endif;
	}
	public function getgroups($FK_users)
	{
		$adminflag = $this->getadminflag($FK_users);
			
		if($adminflag == 1):
			
		else:
			$this->db->where('b.FK_users',$FK_users);
			$this->db->where('b.selected','1');
		endif;
		$this->db->select('*');
		$this->db->from('groups a');
		$this->db->join('userpbgroup b','a.PK_groups = b.FK_groups','LEFT');
		$this->db->where('isactive','1');
		$query = $this->db->get();
		if($query->num_rows() >= 1):
			return $query->result_array();
		endif;
	}
	public function getrptdtls()
	{
		$this->db->select('*');
		$this->db->from('reports');
		$query = $this->db->get();
		if($query->num_rows() >= 1):
			foreach($query->result() as $rows):
				$data[] = array(
					'PK_reports'=>$rows->PK_reports,
					'description'=>$rows->description,
					'name'=>$this->reportgetselected($rows->PK_reports),
					'isactive'=>$rows->isactive
				);
			endforeach;
		else:
			$data = "";
		endif;
		return $data;
	}
	public function reportgetselected($id)
	{
		$data = "";
		$this->db->select('b.name');
		$this->db->from('reportgroup a');
		$this->db->join('phonebook b','a.FK_phonebook = b.PK_phonebook','INNER');
		$this->db->where('b.isdelete','0');
		$this->db->where('a.selected','1');
		$this->db->where('a.FK_reports',$id);
		$query = $this->db->get();
		
		if($query->num_rows() >= 1):
			foreach($query->result() as $rows):
				$data .= $rows->name."; ";
			endforeach;
		endif;
		return $data;
	}
	public function showreportdetails($id)
	{
		$query = "
		INSERT INTO reportgroup (FK_reports, FK_phonebook, selected)
		SELECT
		*
		FROM
		(
		SELECT
			a.PK_reports,
			b.PK_phonebook,
			'0' [selected]
			FROM reports a
			LEFT OUTER JOIN phonebook b ON 1=1
		) as x
		WHERE
			CONCAT(x.PK_reports,' ',x.PK_phonebook) NOT IN 
			(
				SELECT
				CONCAT(xx.FK_reports,' ',xx.FK_phonebook) 
				FROM
				reportgroup xx
			)
		";
		
		$this->db->query($query);
		$this->db->select('a.PK_reportgroup, b.name, b.number, a.selected');
		$this->db->from('reportgroup a');
		$this->db->join('phonebook b', 'a.FK_phonebook = b.PK_phonebook', 'INNER');
		$this->db->where('a.FK_reports',$id);
		$this->db->where('b.isdelete','0');
		$query = $this->db->get();
		//echo $this->db->last_query();
		if($query->num_rows() >= 1):
			$data[0] = $query->result_array();
		endif;
		
		
		$this->db->select('a.PK_reportgroup, a.selected');
		$this->db->from('reportgroup a');
		$this->db->join('phonebook b', 'a.FK_phonebook = b.PK_phonebook', 'INNER');
		$this->db->where('a.FK_reports',$id);
		$query = $this->db->get();
		if($query->num_rows() >= 1):
			foreach($query->result() as $rows):
				$data[1][$rows->PK_reportgroup] = ($rows->selected == 1 ? TRUE : FALSE);
			endforeach;
		endif;
		
		
		$this->db->select('description');
		$this->db->from('reports');
		$this->db->where('PK_reports',$id);
		$query = $this->db->get();
		$data[2]['name'] = $query->row('description');
		
		return $data;
	}
	public function getmsgdtls()
	{
		$sql = "
		SELECT
		yyy.number,
		ISNULL(zzz.name,'Unsaved Number') [fullname],
		aaa.msg,
		aaa.timestamp,
		bbb.count
		FROM
		(
		SELECT
		MAX(yy.PK_msgcenter) [PK_msgcenter],
		yy.number
		FROM
		(
			SELECT
			xx.PK_msgcenter,
			xx.timestamp,
			xx.FK_msgtype,
			CASE
				WHEN xx.FK_msgtype = '1003' THEN xx.receipient
				ELSE xx.sender
			END as [number],
			xx.msg
			FROM
			(
				SELECT
				*
				FROM
				(
					SELECT
					a.PK_msgcenter,
					a.timestamp,
					CASE
						WHEN SUBSTRING(a.receipient,0,5) = '+639' THEN CONCAT('09',SUBSTRING(a.receipient,5,13))
						ELSE a.receipient
					END as [receipient],
					CASE
						WHEN SUBSTRING(a.sender,0,5) = '+639' THEN CONCAT('09',SUBSTRING(a.sender,5,13))
						ELSE a.sender
					END as [sender],
					a.FK_msgtype,
					a.msg,
					a.status,
					a.istrash,
					a.FK_userssend
					FROM
					msgcenter a
					WHERE
						a.FK_msgtype = '1003' AND
						a.istrash = '0'
				) as x
				UNION ALL
				(
					SELECT
					a.PK_msgcenter,
					a.timestamp,
					CASE
						WHEN SUBSTRING(a.receipient,0,5) = '+639' THEN CONCAT('09',SUBSTRING(a.receipient,5,13))
						ELSE a.receipient
					END as [receipient],
					CASE
						WHEN SUBSTRING(a.sender,0,5) = '+639' THEN CONCAT('09',SUBSTRING(a.sender,5,13))
						ELSE a.sender
					END as [sender],
					a.FK_msgtype,
					a.msg,
					a.status,
					a.istrash,
					a.FK_userssend
					FROM
					msgcenter a
					WHERE
						a.FK_msgtype = '1002' AND
						a.istrash = '0'
				)
			) as xx
		) as yy
		GROUP BY yy.number
		) as yyy
		LEFT OUTER JOIN
			(
				SELECT
				a.name,
				CASE
					WHEN SUBSTRING(a.number,0,5) = '+639' THEN CONCAT('09',SUBSTRING(a.number,5,13))
					ELSE a.number
				END as [number]
				FROM
				phonebook a
				WHERE
					a.isdelete = 0
			) zzz ON zzz.number = yyy.number
		INNER JOIN msgcenter aaa ON aaa.PK_msgcenter = yyy.PK_msgcenter
		INNER JOIN 
		(
			SELECT
			COUNT(xx.FK_userssend) [count],
			xx.number
			FROM
			(
				SELECT
				x.FK_userssend,
				CASE
					WHEN x.FK_msgtype = '1003' THEN x.receipient
					ELSE x.sender
				END as [number]
				FROM
				(
					SELECT
					a.FK_msgtype,
					CASE
						WHEN SUBSTRING(a.receipient,0,5) = '+639' THEN CONCAT('09',SUBSTRING(a.receipient,5,13))
						ELSE a.receipient
					END as [receipient],
					CASE
						WHEN SUBSTRING(a.sender,0,5) = '+639' THEN CONCAT('09',SUBSTRING(a.sender,5,13))
						ELSE a.sender
					END as [sender],
					FK_userssend
					FROM
					msgcenter a
				) as x
				WHERE
					x.FK_userssend = ".$this->session->userdata('PK_users')."
			) as xx
			GROUP BY xx.number
		) bbb ON bbb.number = yyy.number
		";

		$query = $this->db->query($sql);
		if($query->num_rows() >= 1):
			//print_r($query->result_array());
			foreach($query->result() as $rows):
				$data[] = array(
					'number'=>$rows->number,
					'fullname'=>$rows->fullname,
					'msg'=>rawurldecode($rows->msg),
					'timestamp'=>$rows->timestamp
				);
			endforeach;
			return $data;
		endif;
	}
	public function schedmsg()
	{
		$sql = 
		"
		SELECT
		TOP 1
		x.PK_schedmsgs,
		x.timestamp,
		x.number2 [number],
		x.msg,
		x.FK_usersadd,
		x.status,
		x.isadmit,
		x.ismgh,
		x.isdisch,
		x.isexam,
		x.isreport,
		x.FK_msgaccess,
		x.isacknowledge
		FROM
		(
		SELECT
		*,
		CASE 
			WHEN SUBSTRING(a.number,0,5) = '+639' THEN CONCAT('09',SUBSTRING(a.number,5,13))
			ELSE a.number
		END as [number2]

		FROM
		schedmsgs a
		) as x
		WHERE
			LEN(x.number2) = 11 AND
			CONVERT(date,x.timestamp) <= CONVERT(date,x.timestamp) AND
			x.status = 0 AND
			LEN(x.msg) <= 1000
		";
		$query = $this->db->query($sql);
		$data = FALSE;
		if($query->num_rows() >= 1):
			$data[0] = $query->result_array();
			$data[1] = $this->systemmodems($query->row('FK_usersadd'));
			//print_r($data);

		endif;
		return $data;
	}
	public function msgcenteroutbox($number,$msg,$FK_users)
	{
		$systemsetup = $this->systemsetup();
		$details = array(
			'receipient'=>$number,
			'sender'=>$systemsetup['mynumber'],
			'msg'=>str_replace("%20"," ",$msg),
			'FK_userssend'=>$FK_users,
			'FK_msgtype'=>'1003',
			'status'=>'1',
			'istrash'=>'0'
		);
		if($this->db->insert('msgcenter',$details)):
			return TRUE;
		else:
			return FALSE;
		endif;
	}
	public function msgcenterinbox($number,$msg,$FK_users)
	{
		$systemsetup = $this->systemsetup();
		$details = array(
			'receipient'=>$systemsetup['mynumber'],
			'sender'=>$number,
			'msg'=>str_replace("%20"," ",$msg),
			'FK_userssend'=>$FK_users,
			'FK_msgtype'=>'1002',
			'status'=>'1',
			'istrash'=>'0'
		);
		if($this->db->insert('msgcenter',$details)):
			return TRUE;
		else:
			return FALSE;
		endif;
	}
	public function systemmodems($FK_users)
	{
		$this->db->select('b.modemname, b.mynumber, b.gateway, b.port, b.password');
		$this->db->from('users a');
		$this->db->join('systemmodems b','b.FK_groups = a.PK_users','INNER');
		$this->db->where('a.PK_users',$FK_users);
		$this->db->where('a.isactive','1');
		$this->db->where('b.isactive','1');
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() == 1):
			return $query->result_array();
		else:
			$this->db->select('a.modemname, a.mynumber, a.gateway, a.port, a.password');
			$this->db->from('systemmodems a');
			$this->db->where('a.FK_groups','1000');
			$query = $this->db->get();
			if($query->num_rows() == 1):
				return $query->result_array();
			else:
				return false;
			endif;
		endif;
	}
	public function saveuserreport($reportid, $reportusers)
	{
		$reportusers = (array)$reportusers;

		foreach($reportusers as $PK_reportgroup => $keys):
			$this->db->update('reportgroup',array('selected'=>$keys),array('PK_reportgroup'=>$PK_reportgroup));
		endforeach;
	}
	public function updateschedmsgs($PK_schedmsgs,$number,$msg)
	{
		$retval = FALSE;
		$systemsetup = $this->systemsetup();
		if($this->db->update('schedmsgs',array('status'=>'1'),array('PK_schedmsgs'=>$PK_schedmsgs))):
			$insertdata = array(
				'FK_msgtype'=>'1002',
				'receipient'=>$number,
				'sender'=>$systemsetup['mynumber'],
				'msg'=>$msg,
				'status'=>'1',
				'istrash'=>'0',
				'FK_userssend'=>'1001',
				'scheduled'=>'1'
			);
			if($this->db->insert('msgcenter',$insertdata)):
				$retval = TRUE;
			endif;
		endif;
		return $retval;
	}
	public function savephonebook($id, $number, $name)
	{

		$data = array(
			'number'=>$number,
			'name'=>$name
		);
		if($id == 0):
			if($this->db->insert('phonebook',$data)):
				return TRUE;
			else:
				return FALSE;
			endif;
		else:
			if($this->db->update('phonebook',$data,array('PK_phonebook'=>$id))):
				return TRUE;
			else:
				return FALSE;
			endif;
		endif;
	}
	public function getphonebookdetails($id)
	{
		$this->db->select('*');
		$this->db->from('phonebook');
		$this->db->where('PK_phonebook',$id);
		$query = $this->db->get();
		if($query->num_rows() == 1):
			$data['number'] = $query->row('number');
			$data['name'] = $query->row('name');
			return $data;
		else:
			return FALSE;
		endif;
	}
	public function getautoinput($id,$FK_users)
	{
		
		if($id == 1):
			$this->db->select('a.PK_phonebook, a.name, a.number');
			$this->db->from('phonebook a');
			$this->db->join('userphonebook b','a.PK_phonebook = b.FK_phonebook','INNER');
			$this->db->where('b.FK_users',$FK_users);
			$this->db->where('b.selected','1');
			$this->db->where('a.unsubscribeflag','0');
			$this->db->where('a.isdelete','0');
			$query = $this->db->get();
			//echo $this->db->last_query();
			if($query->num_rows() >= 1):
				foreach($query->result() as $rows):
					$data[] = array(
						'id'=>$rows->PK_phonebook,
						'text'=>$rows->name." ".$rows->number
					);
				endforeach;
			else:
				$data[0] = array('0'=>"No Assigned Phonebook");
			endif;
		elseif($id == 2):
			$this->db->select('a.PK_groups, a.description as name');
			$this->db->from('groups a');
			$this->db->join('userpbgroup b','b.FK_groups = a.PK_groups','INNER');
			$this->db->where('a.isactive','1');
			$this->db->where('b.selected','1');
			$this->db->where('b.FK_users',$FK_users);
			$query = $this->db->get();
			if($query->num_rows() >= 1):
				foreach($query->result() as $rows):
					$data[] = array(
						'id'=>$rows->PK_groups,
						'text'=>$rows->name
					);
				endforeach;
			else:
				$data[0] = array('0'=>"No Assigned Groups");
			endif;
		endif;
		return $data;
	}
	public function getnumber($type,$number)
	{	
		if($type == 1):
			$this->db->select('number');
			$this->db->from('phonebook');
			$this->db->where('PK_phonebook',$number);
			$this->db->where('unsubscribeflag','0');
			$query = $this->db->get();
			if($query->num_rows() == 1):
				return $query->row('number');
			endif;
		else:
			
			$this->db->select('b.number');
			$this->db->from('pbgroup a');
			$this->db->join('phonebook b','a.FK_phonebook = b.PK_phonebook','INNER');
			$this->db->where('a.FK_groups',$number);
			$this->db->where('a.selected','1');
			$this->db->where('b.isdelete','0');
			$this->db->where('b.unsubscribeflag','0');
			$query = $this->db->get();
			//print_r($this->db->last_query());
			if($query->num_rows() >= 1):
				return $query->result_array();
			endif;
		endif;
	}
	
	public function insertnewmsgs($numbers,$msg,$type,$scheduled,$datetime,$FK_users)
	{
		
		if(!is_array($numbers)):
			$data = array(
				'number'=>$numbers,
				'msg'=>str_replace("%20"," ",$msg),
				'FK_usersadd'=>$FK_users,
				"datetime"=>date("Y-m-d H:i:s", strtotime($datetime)),
				'isscheduled'=>$scheduled,
				'FK_usersadd'=>$FK_users
			);
			$this->db->insert('schedmsgs',$data);
		else:
			foreach($numbers as $number):
				$data = array(
					'number'=>$number['number'],
					'msg'=>str_replace("%20"," ",$msg),
					'FK_usersadd'=>$FK_users,
					"datetime"=>date("Y-m-d H:i:s", strtotime($datetime)),
					'isscheduled'=>$scheduled,
				);
				$this->db->insert('schedmsgs',$data);
			endforeach;
		endif;
	}
	public function showusergroup($id)
	{
		$sql = "
		INSERT INTO pbgroup (FK_groups, FK_phonebook, selected)
		SELECT
		*
		FROM
		(
		SELECT
			a.PK_groups,
			b.PK_phonebook,
			'0' [selected]
			FROM groups a
			LEFT OUTER JOIN phonebook b ON 1=1
		) as x
		WHERE
			CONCAT(x.PK_groups,' ',x.PK_phonebook) NOT IN 
			(
				SELECT
				CONCAT(xx.FK_groups,' ',xx.FK_phonebook) 
				FROM
				pbgroup xx
			)
		";
		$this->db->query($sql);
		
		$data = FALSE;
		$this->db->select('a.PK_pbgroup, b.name, b.number, a.selected');
		$this->db->from('pbgroup a');
		$this->db->join('phonebook b', 'b.PK_phonebook = a.FK_phonebook', 'INNER');
		$this->db->where('a.FK_groups',$id);
		$this->db->where('b.isdelete','0');
		$query = $this->db->get();
		if($query->num_rows() >= 1):
			foreach($query->result() as $rows):
				$data[0][] = array(
					"PK_pbgroup"=>$rows->PK_pbgroup,
					"name"=>$rows->name,
					"number"=>$rows->number,
					"selected"=>($rows->selected == '1' ? TRUE : FALSE)
				);
				
				$data[1][$rows->PK_pbgroup] = ($rows->selected == '1' ? TRUE : FALSE);
			endforeach;
		endif;
		return $data;
	}
	public function saveusergroup($reportid, $reportusers)
	{
		$reportusers = (array)$reportusers;

		foreach($reportusers as $PK_pbgroup => $keys):
			$this->db->update('pbgroup',array('selected'=>$keys),array('PK_pbgroup'=>$PK_pbgroup));
		endforeach;
	}
	public function insertschedsms($number, $msg, $FK_usersadd)
	{
		$data = array(
			'number'=>$number,
			'msg'=>str_replace("%20"," ",$msg),
			'FK_usersadd'=>$FK_usersadd,
			"datetime"=>date("Y-m-d H:i:s"),
			'isscheduled'=>'1'
		);
		$this->db->insert('schedmsgs',$data);
	}
	public function insertschedsmscustom($number, $msg, $FK_usersadd,$datetime)
	{
		$data = array(
			'number'=>$number,
			'msg'=>str_replace("%20"," ",$msg),
			'FK_usersadd'=>$FK_usersadd,
			"datetime"=>date("Y-m-d H:i:s",strtotime($datetime))
		);
		$this->db->insert('schedmsgs',$data);
	}
	public function deletegroups($id)
	{
		$this->db->update('groups',array('isactive'=>'0'),array('PK_groups'=>$id));
	}
	public function addnewgroup($id,$name)
	{
		$data['status'] = 2;
		if($id == '0'):
			
			$this->db->select('*');
			$this->db->from('groups');
			$this->db->where('description',$name);
			$this->db->where('isactive','1');
			$query = $this->db->get();
			$data['status'] = 2;
			if($query->num_rows() >= 1):
				$data['status'] = 0;
			else:
				if($this->db->insert('groups',array('description'=>$name,'isactive'=>'1'))):
					$data['status'] = 1;
				else:
					$data['status'] = 2;
				endif;
			endif;
		else:
			$this->db->select('*');
			$this->db->from('groups');
			$this->db->where('description',$name);
			$this->db->where('isactive','1');
			$this->db->where('PK_groups <>',$id);
			$query = $this->db->get();
			//print_r($this->db->last_query());
			
			if($query->num_rows() == 0):
				if($this->db->update('groups',array('description'=>$name),array('PK_groups'=>$id))):
					$data['status'] = 1;
				else:
					$data['status'] = 2;
				endif;
			endif;
		endif;
		return $data;
	}
	public function getgroupname($id)
	{
		$this->db->select('description');
		$this->db->from('groups');
		$this->db->where('isactive','1');
		$this->db->where('PK_groups',$id);
		$query = $this->db->get();
		$data['name'] = 'No Assigned Groups';
		if($query->num_rows() == 1):
			$data['name'] = $query->row('description');
		endif;
		return $data;
	}
	public function getnamebynumber($number)
	{
		$number = (substr($number,0,4) == '+639') ? "09".substr($number,4,13) : $number;
		$sql = 
		"
		SELECT
		*
		FROM
		(
			SELECT
			a.PK_phonebook,
			a.name,
			CASE
				WHEN SUBSTRING(a.number,0,5) = '+639' THEN CONCAT('09',SUBSTRING(a.number,5,13))
				ELSE a.number
			END as [number]
			FROM
			phonebook a
		) as x
		WHERE x.number = ".$this->db->escape($number)."
		";
		$query = $this->db->query($sql);
		if($query->num_rows() == 1):
			$data = array(
				'name'=>$query->row('name'),
				'number'=>$query->row('number')
			);
		else:
			$data = array(
				'name'=>'Unsaved Number',
				'number'=>$number
			);
		endif;
		return $data;
	}
	public function getmsgdetailsbyid($id)
	{
		$this->db->select('*');
		$this->db->from('schedmsgs');
		$this->db->where('PK_schedmsgs',$id);
		$query = $this->db->get();
		if($query->num_rows() == 1):
			foreach($query->result() as $rows):
				$data[0][] = array(
					'msgposition'=>'1',
					'PK_msgcenter'=>$rows->PK_schedmsgs,
					'msg'=>rawurldecode($rows->msg),
					'datetime'=>$rows->datetime
				);
			endforeach;
			$data[1] = $this->getnamebynumber($query->row('number'));
			return $data;
		endif;
	}
	public function getmsgdetails($number)
	{
		$sql = "
		SELECT
		xxx.PK_msgcenter,
		CASE
			WHEN xxx.FK_msgtype = '1003' THEN '0'
			ELSE '1'
		END as [msgposition],
		xxx.msg,
		xxx.timestamp
		FROM
		(	
			SELECT
			xx.PK_msgcenter,
			xx.timestamp,
			xx.FK_msgtype,
			CASE
				WHEN xx.FK_msgtype = '1003' THEN xx.receipient
				ELSE xx.sender
			END as [number],
			xx.msg
			FROM
			(
				SELECT
				*
				FROM
				(
					SELECT
					a.PK_msgcenter,
					a.timestamp,
					CASE
						WHEN SUBSTRING(a.receipient,0,5) = '+639' THEN CONCAT('09',SUBSTRING(a.receipient,5,13))
						ELSE a.receipient
					END as [receipient],
					CASE
						WHEN SUBSTRING(a.sender,0,5) = '+639' THEN CONCAT('09',SUBSTRING(a.sender,5,13))
						ELSE a.sender
					END as [sender],
					a.FK_msgtype,
					a.msg,
					a.status,
					a.istrash,
					a.FK_userssend
					FROM
					msgcenter a
					WHERE
						a.FK_msgtype = '1003'
				) as x
				UNION ALL
				(
					SELECT
					a.PK_msgcenter,
					a.timestamp,
					CASE
						WHEN SUBSTRING(a.receipient,0,5) = '+639' THEN CONCAT('09',SUBSTRING(a.receipient,5,13))
						ELSE a.receipient
					END as [receipient],
					CASE
						WHEN SUBSTRING(a.sender,0,5) = '+639' THEN CONCAT('09',SUBSTRING(a.sender,5,13))
						ELSE a.sender
					END as [sender],
					a.FK_msgtype,
					a.msg,
					a.status,
					a.istrash,
					a.FK_userssend
					FROM
					msgcenter a
					WHERE
						a.FK_msgtype = '1002'
				)
			) as xx
		) xxx
		WHERE
			xxx.number = ".$this->db->escape($number)."
		ORDER BY
			PK_msgcenter
		";

		$data = FALSE;
		$query = $this->db->query($sql);
		if($query->num_rows() >= 1):
			
			foreach( $query->result() as $rows ):
				$text = preg_replace("/[\r\n]+/", "\n", $rows->msg);
				//$text = wordwrap($text,120, '<br/>', true);
				//$text = nl2br($text);
				$data[0][] = array(
					'msgposition'=>$rows->msgposition,
					'PK_msgcenter'=>$rows->PK_msgcenter,
					'msg'=>rawurldecode($text),
					'timestamp'=>$rows->timestamp //date("M d, Y h:i A",strtotime($rows->timestamp))
				);
			endforeach;
			
			
			$sql = 
			"
			SELECT
			TOP 1
			*
			FROM
			(
			SELECT
			a.PK_phonebook,
			a.name,
			CASE
				WHEN SUBSTRING(a.number,0,5) = '+639' THEN CONCAT('09',SUBSTRING(a.number,5,13))
				ELSE a.number
			END as [number]
			FROM
			phonebook a
			WHERE
				a.isdelete = '0'
			) as x
			WHERE
				x.number = ".$this->db->escape($number)."
			";
			$query = $this->db->query($sql);
			if($query->num_rows() == 1):
				$data[1] = $query->row('name')." - ".$number;
			else:
				$data[1] = "Unsaved Number - ".$number;
			endif;
		endif;
		return $data;
	}
	public function deletephonebook($id)
	{
		if($this->db->update('phonebook',array('isdelete'=>'1'),array('PK_phonebook'=>$id))):
			$data['status'] = '1';
		else:
			$data['status'] = '0';
		endif;
		return $data;
	}
	public function getallusers()
	{
		$this->db->select('*');
		$this->db->from('users');
		$query = $this->db->get();
		if($query->num_rows() >= 1):
			return $query->result_array();
		else:
			return FALSE;
		endif;
	}
	public function saveusers($id,$fullname,$username,$password,$adminflag)
	{
		$data = array(
			'fullname'=>$fullname,
			'username'=>$username,
			'password'=>$password,
			'adminflag'=>$adminflag
		);
		if($id == '0'):
			$this->db->select('username');
			$this->db->from('users');
			$this->db->where('username',$username);
			$query = $this->db->get();
			if($query->num_rows() == 0):
				if($this->db->insert("users",$data)):
					$retval['status'] = '1';
				else:
					$retval['status'] = '0';
				endif;
			else:
				$retval['status'] = '2';
			endif;
		else:
			$this->db->select('username');
			$this->db->from('users');
			$this->db->where('username',$username);
			$this->db->where('PK_users <>',$id);
			$query = $this->db->get();
			if($query->num_rows() == 0):
				if($this->db->update('users',$data,array('PK_users'=>$id))):
					$retval['status'] = '3';
				endif;
			else:
				$retval['status'] = '2';
			endif;
		endif;
		return $retval;
	}
	public function inactiveuser($id)
	{
		$this->db->select('isactive');
		$this->db->from('users');
		$this->db->where('PK_users',$id);
		$query = $this->db->get();
		if($query->num_rows() == 1):
			if($query->row('isactive') == '1'):
				$data = array(
					'isactive'=>'0'
				);
			else:
				$data  = array(
					'isactive'=>'1'
				);
			endif;
		endif;
		
		if($this->db->update('users',$data,array('PK_users'=>$id))):
			return $retval['status'] = '1';
		else:
			return $retval['status'] = '0';
		endif;
	}
	public function getuserdetails($id)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('PK_users',$id);
		$query = $this->db->get();
		if($query->num_rows() == 1):
			$data = array(
				'fullname'=>$query->row('fullname'),
				'username'=>$query->row('username'),
				'password'=>$query->row('password'),
				'adminflag'=>$query->row('adminflag')
			);
			return $data;
		else:
			return FALSE;
		endif;
	}
	public function getscheduledsms($FK_users)
	{
		$where = ($FK_users == 1001) ? "WHERE 1=1 AND isdelete <> 1" : "WHERE a.FK_usersadd = ".$this->db->escape($FK_users)." AND a.isdelete <> '1'";
		$where2 = ($FK_users == 1001) ? "WHERE 1=1 AND isdelete <> 1" : "WHERE a.FK_usersadd = ".$this->db->escape($FK_users)." AND a.selected = '1' AND a.isdelete <> '1'";
		$sql = "
		SELECT
		*
		FROM
		(
		SELECT
		x.*,
		ISNULL(y.name, 'Unsaved Number') [name]
		FROM
		(
		SELECT
		a.PK_schedmsgs,
		a.timestamp,
		CASE
		WHEN SUBSTRING(a.number,0,5) = '+639' THEN CONCAT('09',SUBSTRING(a.number,5,13))
		ELSE a.number
		END as [number],
		a.FK_usersadd,
		a.msg,
		a.datetime,
		a.status,
		a.isscheduled,
		a.isacknowledge,
		a.isdelete
		FROM
		schedmsgs a
		INNER JOIN
		(
		SELECT
		a.PK_users,
		b.FK_msgaccess,
		c.description,
		b.selected
		FROM
		users a
		INNER JOIN useraccess b ON a.PK_users = b.FK_users
		INNER JOIN msgaccess c ON c.PK_msgaccess = b.FK_msgaccess
		WHERE
			b.selected = '1' AND
			b.FK_users = ".$this->db->escape($FK_users)."
		) b ON a.FK_msgaccess = b.FK_msgaccess
		--WHERE a.FK_usersadd = '1017' AND
		WHERE
		(a.isdelete = '0' OR a.isdelete IS NULL) AND
		a.FK_usersadd = '1001'
		) x
		LEFT OUTER JOIN
		(
		SELECT
		a.name,
		CASE
		WHEN SUBSTRING(a.number,0,5) = '+639' THEN CONCAT('09',SUBSTRING(a.number,5,13))
		ELSE a.number
		END as [number]
		FROM
		phonebook a
		WHERE
		a.isdelete <> '1'
		) y ON x.number = y.number
		UNION ALL
		SELECT
		a.PK_schedmsgs,
		a.timestamp,
		ISNULL(b.number, a.number) [number],
		a.FK_usersadd,
		a.msg,
		a.datetime,
		a.status,
		a.isscheduled,
		a.isacknowledge,
		a.isdelete,
		ISNULL(b.name, 'Unsaved Number') [name]
		FROM
		schedmsgs a
		LEFT OUTER JOIN
		(
			SELECT
			a.PK_phonebook,
			a.name,
			CASE
				WHEN SUBSTRING(a.number,0,5) = '+639' THEN CONCAT('09',SUBSTRING(a.number,5,13))
				ELSE a.number
			END as [number]
			FROM
			phonebook a
			WHERE
			a.isdelete = '0'
		) b ON b.number = a.number
		$where
		) as x
		ORDER BY x.PK_schedmsgs DESC
		
		";
		//echo nl2br($sql);
		$counter = 1;
		$query = $this->db->query($sql);
		if($query->num_rows() >= 1):
			foreach($query->result() as $rows):
				$data[0][] = array(
					'counter'=>$counter,
					'PK_schedmsgs'=>$rows->PK_schedmsgs,
					'msg'=>rawurldecode($rows->msg),
					'datetime'=>$rows->datetime,
					'status'=>$rows->status,
					'isacknowledge'=>$rows->isacknowledge,
					'name'=>$rows->name,
					'number'=>$rows->number
				);
				$data[1][$rows->PK_schedmsgs] = FALSE; 
				$counter++;
			endforeach;
			return $data;
		else:
			return $data[0] = null;
		endif;
	}
	public function deleteselectedmsg($id)
	{
		
		if(count($id) >= 1):
			foreach($id as $rows => $key):
				if($key == 1):
					$this->db->update('schedmsgs',array('isdelete'=>'1'),array('PK_schedmsgs'=>$rows));
				endif;
			endforeach;
		endif;
	}
	public function checklogin($username,$password)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('username',$username);
		$this->db->where('password',$password);
		$query = $this->db->get();
	
		if($query->num_rows() == 1):
			foreach($query->result() as $rows):
				$data['PK_users'] = $rows->PK_users;
				$data['fullname'] = $rows->fullname;
				$data['username'] = $rows->username;
				$data['password'] = $rows->password;
				$data['adminflag'] = $rows->adminflag;
			endforeach;
			$this->session->set_userdata($data);
			return $data;
		else:
			return FALSE;
		endif;
	}
	public function getadminflag($PK_users)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('PK_users',$PK_users);
		$query = $this->db->get();
	
		if($query->num_rows() == 1):
			return $query->row('adminflag');
		else:
			return 0;
		endif;
	}
	
	public function changepassword($id, $old, $new, $confirm)
	{
		$data['status'] = '0';
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('PK_users',$id);
		$this->db->where('password',$old);
		$query = $this->db->get();
		if($query->num_rows() == 1):
			if($new == $confirm):
				if($this->db->update('users',array('password'=>$new),array('PK_users'=>$id))):
					$data['status'] = '1'; //SUCCESS
				else:
					$data['status'] = '0'; //ERROR
				endif;
			else:
				$data['status'] = '3'; //CONFIRM NOT MATCH
			endif;
		else:
			$data['status'] = '2'; //WRONG OLD
		endif;
		return $data;
	}
	public function useraccess($PK_users)
	{
		$sql = "
		INSERT INTO useraccess (FK_users, FK_msgaccess, selected)
		SELECT
		a.PK_users,
		b.PK_msgaccess,
		0 [selected]
		FROM
		users a
		INNER JOIN msgaccess b ON 1=1

		WHERE
			CONCAT(a.PK_users,'-',b.PK_msgaccess) NOT IN
			(
				SELECT
				CONCAT(x.FK_users,'-',x.FK_msgaccess)
				FROM
				useraccess x
			)
		";
		if($this->db->query($sql)):
			$this->db->select('a.*, b.description');
			$this->db->from('useraccess a');
			$this->db->join('msgaccess b','a.FK_msgaccess = b.PK_msgaccess','INNER');
			$this->db->where('a.FK_users',$PK_users);
			$query = $this->db->get();
			if($query->num_rows() >= 1):
				foreach($query->result() as $rows):
					$data[0][] = array(
						"PK_useraccess"=>$rows->PK_useraccess,
						"description"=>$rows->description,
						"selected"=>($rows->selected == '1' ? TRUE : FALSE)
					);
					$data[1][$rows->PK_useraccess] = ($rows->selected == '1' ? TRUE : FALSE);
				endforeach;
				return $data;
			endif;
		endif;
	}
	public function edituseraccess($data)
	{
		if(isset($data) && count($data) >= 1):
			foreach($data as $rows => $val):
				$this->db->update("useraccess",array("selected"=>$val),array("PK_useraccess"=>$rows));
			endforeach;
		endif;
	}
	public function saveuserphonebook($data)
	{
		if(isset($data) && count($data) >= 1):
			foreach($data as $rows => $val):
				$this->db->update("userphonebook",array("selected"=>$val),array("PK_userphonebook"=>$rows));
			endforeach;
		endif;
	}
	public function saveuserpbgroup($data)
	{
		if(isset($data) && count($data) >= 1):
			foreach($data as $rows => $val):
				$this->db->update("userpbgroup",array("selected"=>$val),array("PK_userpbgroup"=>$rows));
			endforeach;
		endif;
	}
	public function getsmscodes($text,$number)
	{
		if(substr($number,0, 4) == '+639'):
			$number = '09'.substr($number,4,13);
		endif;
		$this->db->select('*');
		$this->db->from('TextCodes');
		$this->db->where('Code',$text);
		$this->db->where('number',$number);
		$query = $this->db->get();
		echo $this->db->last_query();
		if($query->num_rows() >= 1):
			$sql = "
			UPDATE b SET b.isacknowledge = '1'
			FROM
			textcodes a
			INNER JOIN schedmsgs b ON a.PK_schedmsgs = b.PK_schedmsgs
			WHERE 
				(b.isacknowledge = 0 OR b.isacknowledge IS NULL) AND
				a.Code = ".$this->db->escape($text)." AND
				(
					a.number = CASE WHEN SUBSTRING('".$number."',0,5) = '+639' THEN CONCAT('09',SUBSTRING('".$number."',5,13)) ELSE '".$number."' END
				)
			";
			if($this->db->query($sql)):
				return $sql;
			else:
				return FALSE;
			endif;
		else:
			return FALSE;
		endif;		
	}
	public function updatemac($macaddress)
	{
		$this->db->update('systemsetup',array('macaddress'=>$macaddress),array('1'=>'1'));
	}
}
