<?php
require_once("database.php");
$pdo = Database::connect();
Class Crud_op
{
    /* Start crud op in years_study tbl*/
    //login file  
    public static function check_valid_usr_login($arr_usr_name_and_pass)
    {
         
        global $pdo;
        $username       = $arr_usr_name_and_pass[0];
        $password       = $arr_usr_name_and_pass[1];
        $encripted_pass = sha1($password);
        $sql            = "SELECT `usr_id`, `password`, `role`, concat(`fname`,' ', `lname`) u_name, `status` FROM `users` where usr_id=:usr_id and password=:password
   and status=:usr_status ";
        $usr_status     = "regular";
        $result         = $pdo->prepare($sql);
         
        $result->bindParam(':usr_id', $username, PDO::PARAM_STR);
        $result->bindParam(':password', $encripted_pass, PDO::PARAM_STR);
        $result->bindParam(':usr_status', $usr_status, PDO::PARAM_STR);
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    /*header.php file*/
    public static function get_Admin_data_row($usr_id)
    {
        global $pdo;
        $sql        = "SELECT `id`, `fname`, `lname`, `status` FROM `admin`  ";
        $usr_status = "regular";
        $result     = $pdo->query($sql);
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    /*add_semesters_into_years.php file*/
    public static function get_semester_rows()
    {
        global $pdo;
        $get_semester_rows_query = "SELECT `id`, `sem_name` FROM `semester_names`;";
        $get_semester_rows_stmt  = $pdo->query($get_semester_rows_query);
        $get_semester_rows_stmt->execute();
        $get_semester_rows_data = $get_semester_rows_stmt->fetchAll();
        return $get_semester_rows_data;
    }
    public static function update_studying_year_state($state_arr_data)
    {
        global $pdo;
        $row      = 0;
        // $state_arr_data = array($sem_id , $state_no);
        $sem_id   = $state_arr_data[0];
        $state_no = $state_arr_data[1];
        try {
            $pdo->beginTransaction();
            if ($state_no == 1) {
                // SET ALL SEMESTER NOT ACTIVE
                $set_not_active = $pdo->prepare("UPDATE `semester` SET `active` = 0 ");
                $not_active     = $set_not_active->execute();
                if ($not_active)
                    $row = 1;
            }
            $update_studying_year_state_query = "UPDATE `semester` SET `active`=:active WHERE `auto_inc_id`=:auto_inc_id;";
            $update_studying_year_state_stmt  = $pdo->prepare($update_studying_year_state_query);
            $update_studying_year_state_stmt->bindParam(':active', $state_no, PDO::PARAM_INT);
            $update_studying_year_state_stmt->bindParam(':auto_inc_id', $sem_id, PDO::PARAM_INT);
            $update_studying_year_state_stmt->execute();
            $row = $update_studying_year_state_stmt->rowCount();
           
            $pdo->commit();
        }
        catch (Exception $e) {
            if (isset($pdo)) {
                return 0;
                $pdo->rollback();
               $err = "ﻟﻢ ﺗﺘﻢ اﻟﻌﻤﻠﻴﺔ ﺑﻨﺠﺎﺡ";
            }
        }
        return $row;
    }
    public static function get_all_semester()
    {
        global $pdo;
        $get_semester_query = "SELECT `sem_name`,`auto_inc_id`, semester_id,`year_val`, `active` FROM `semester_names`,`semester` where semester_id=id ORDER BY year_val DESC, semester_id ;";
        $get_semester_stmt  = $pdo->query($get_semester_query);
        $get_semester_stmt->execute();
        $get_semester_result = $get_semester_stmt->fetchAll();
        return $get_semester_result;
    }
    public static function get_info_of_specific_semester($semester_id_tbl)
    {
        global $pdo;
        $get_info_of_specific_semester_query = "SELECT `semester_id`, `sem_name`,`auto_inc_id`, `year_val`, `active` FROM `semester_names`,`semester`
		where semester_id=id and auto_inc_id=:auto_inc_id;";
        $get_info_of_specific_semester_stmt  = $pdo->prepare($get_info_of_specific_semester_query);
        $get_info_of_specific_semester_stmt->bindParam(':auto_inc_id', $semester_id_tbl, PDO::PARAM_INT);
        $get_info_of_specific_semester_stmt->execute();
        $get_info_of_specific_semester_data = $get_info_of_specific_semester_stmt->fetchAll(PDO::FETCH_ASSOC);
        return $get_info_of_specific_semester_data;
    }
    public static function check_if_semester_valid($arrData)
    {
        global $pdo;
        /*
        $semester_tbl_data_filed = array($year_val  ,$semester_id );
        */
		$counter=0;
        $year_val                      = $arrData[0];
        $semester_id                   = $arrData[1];
        $check_if_semester_valid_query = "SELECT count(*) counter FROM `semester` where semester_id=:semester_id and year_val=:year_val";
        $check_if_semester_valid_stmt  = $pdo->prepare($check_if_semester_valid_query);
        $check_if_semester_valid_stmt->bindParam(':semester_id', $semester_id, PDO::PARAM_INT);
        $check_if_semester_valid_stmt->bindParam(':year_val', $year_val, PDO::PARAM_STR);
        $check_if_semester_valid_stmt->execute();
        $check_if_semester_valid_data = $check_if_semester_valid_stmt->fetchAll();
        $counter                      = $check_if_semester_valid_data[0]['counter'];
        return $counter;
    }
    /* add_semesters_into_years.php file */
    public static function insert_data_into_add_semesters_into_years_tbl($arrData)
    { 
        global $pdo;
        /* $semester_tbl_data_filed = array($year_val  ,$semester_id ); */
        $year_val                                            = $arrData[0];
        $semester_id                                         = $arrData[1];
        /*
        INSERT INTO `semester`(`auto_inc_id`, `semester_id`, `year_val`, `active`) VALUES ([value-1],[value-2],[value-3],[value-4])
        */
        $insert_data_into_add_semesters_into_years_tbl_query = "INSERT INTO `semester` ( `semester_id`, `year_val`, `active`)
		VALUES (:semester_id_ref,".$pdo->quote($year_val).",0)";
        $insert_data_into_add_semesters_into_years_tbl_stmt  = $pdo->prepare($insert_data_into_add_semesters_into_years_tbl_query);
        $insert_data_into_add_semesters_into_years_tbl_stmt->bindParam(':semester_id_ref', $semester_id, PDO::PARAM_INT); 
        $insert_data_into_add_semesters_into_years_tbl_stmt->execute();
        $row = $insert_data_into_add_semesters_into_years_tbl_stmt->rowCount();
        return $row;
    }
    public static function update_data_in_add_semesters_into_years_tbl($semester_tbl_data_filed, $id_auto_inc)
    {
        global $pdo;
         $year_val                                          = $semester_tbl_data_filed[0];
        $semester_id                                       = $semester_tbl_data_filed[1];
         $update_data_in_add_semesters_into_years_tbl_query = "UPDATE `semester`SET `semester_id`=:semester_id_ref, 
		 `year_val`=".$pdo->quote($year_val)." where auto_inc_id=:id";
        $update_data_in_add_semesters_into_years_tbl_stmt  = $pdo->prepare($update_data_in_add_semesters_into_years_tbl_query);
        $update_data_in_add_semesters_into_years_tbl_stmt->bindParam(':semester_id_ref', $semester_id, PDO::PARAM_INT); 
        $update_data_in_add_semesters_into_years_tbl_stmt->bindParam(':id', $id_auto_inc, PDO::PARAM_INT);
         return $update_data_in_add_semesters_into_years_tbl_stmt->execute(); 
        
    }
     
    public static function get_active_semester_tbl_row_count()
    {
        global $pdo; 
        $get_semester_tbl_row_count_query = "SELECT count(*) counter FROM `semester` WHERE semester_id!=0 and active!=0;";
        $get_semester_tbl_row_count_stmt  = $pdo->query($get_semester_tbl_row_count_query);
        $get_semester_tbl_row_count_stmt->execute();
        $get_semester_tbl_row_count_data = $get_semester_tbl_row_count_stmt->fetchAll();
       return $get_semester_tbl_row_count_data[0]['counter'];
        
    }
    public static function get_active_semester_tbl_row_count1()
    {
        global $pdo;
        $get_semester_tbl_row_count_query = "SELECT `id`, `sem_name`,`auto_inc_id`, `semester_id`, `year_val`, `active` FROM `semester_names`,`semester` WHERE  semester_names.id=semester.semester_id AND semester.active=1;";
        $get_semester_tbl_row_count_stmt  = $pdo->query($get_semester_tbl_row_count_query);
        $get_semester_tbl_row_count_stmt->execute();
        $get_semester_tbl_row_count_data = $get_semester_tbl_row_count_stmt->fetchAll(); 
        return $get_semester_tbl_row_count_data;
    }
    public static function get_user_type_tbl_row_count()
    {
        global $pdo;
		$get_user_type_tbl_row_count_res=0;
        $get_user_type_tbl_row_count_query = "SELECT count(*) counter FROM `user_type`;";
        $get_user_type_tbl_row_count_stmt  = $pdo->query($get_user_type_tbl_row_count_query);
        $get_user_type_tbl_row_count_stmt->execute();
        $get_user_type_tbl_row_count_data = $get_user_type_tbl_row_count_stmt->fetchAll();
        $get_user_type_tbl_row_count_res  = $get_user_type_tbl_row_count_data[0]['counter'];
        return $get_user_type_tbl_row_count_res;
    }
    public static function get_user_type_rows()
    {
        global $pdo;
        $get_user_type_rows_query = "SELECT `id`, `type` FROM `user_type`;";
        $get_user_type_rows_stmt  = $pdo->query($get_user_type_rows_query);
        $get_user_type_rows_stmt->execute();
        $get_user_type_rows_data = $get_user_type_rows_stmt->fetchAll();
        return $get_user_type_rows_data;
    }
    public static function get_all_users()
    {
        global $pdo;
        $get_all_users_query = "SELECT  `usr_id`, `role`, `fname`, `lname`, `status`,users.role,user_type.type  from users,user_type
		where user_type.id=users.role ;";
        /*
        $get_all_users_query="SELECT * from users inner join user_type on users.user_type=user_type.id inner join semester on semester.id=users.semester_id inner join semester_names on semester_names.id=semester.semester_id_ref";
        */
        $get_all_users_stmt  = $pdo->query($get_all_users_query);
        $get_all_users_stmt->execute();
        $get_all_users_data = $get_all_users_stmt->fetchAll();
        return $get_all_users_data;
    }
    public static function get_active_semester()
    {
        global $pdo;
        $get_active_semester_query = "SELECT semester.year_val ,semester_names.sem_name,semester.auto_inc_id 
		FROM semester,semester_names WHERE semester_names.id=semester.semester_id and semester.active=1;";
        $get_active_semester_stmt  = $pdo->query($get_active_semester_query);
        $get_active_semester_stmt->execute();
        $get_active_semester_data = $get_active_semester_stmt->fetchAll();
        return $get_active_semester_data;
    }
    public static function insert_into_users_and_rel_usr_tbl($admin_info_arr, $tbl_name)
    {
        global $pdo;
        /*
        $users_tbl_data_filed = array($user_id  ,$user_fname,$user_lname,$user_type,$u_pwd);
        */
        $user_id    = $admin_info_arr[0];
        $user_fname = $admin_info_arr[1];
        $user_lname = $admin_info_arr[2];
        $user_type  = $admin_info_arr[3];
        $u_pwd      = $admin_info_arr[4];
        $usr_status = "regular";
        $row1       = 0;
        $row2       = 0;
		 
        try {
            // First of all, let's begin a transaction
            $pdo->beginTransaction();
            
            $insert_into_users_tbl_query = "INSERT INTO `users` SET 
			`usr_id`=".$pdo->quote($user_id).", `password`=".$pdo->quote($u_pwd).", `role`=:role, `fname`=".$pdo->quote($user_fname).", `lname`=".$pdo->quote($user_lname).", `status`=".$pdo->quote($usr_status)." 
			 ;";
            $insert_into_users_tbl_stmt  = $pdo->prepare($insert_into_users_tbl_query); 
            $insert_into_users_tbl_stmt->bindParam(':role', $user_type, PDO::PARAM_INT);   
             $insert_into_users_tbl_stmt->execute()  ;
		  
  $insert_into_users_tbl_query1 ="INSERT INTO ${tbl_name} SET `id`=:id";
  
  
            $insert_into_users_tbl_stmt1  = $pdo->prepare($insert_into_users_tbl_query1); 
            $insert_into_users_tbl_stmt1->bindParam(':id',$user_id,PDO::PARAM_STR);
            $insert_into_users_tbl_stmt1->execute() ;
            
            $pdo->commit();
             return true;
        }
        catch (PDOException $e) {
			echo $e->getMessage();
                $err = "ﻫﺬا اﻟﻤﺴﺘﺨﺪﻡ ﻣﻮﺟﻮﺩ ﻳﺮﺟﻰ اﺿﺎﻓﺔ ﻏﻴﺮﻩ";
                 $errorInfo = $e->errorInfo;
    echo "MySQL error " . $errorInfo[1] . "\n";
             return false;
            // An exception has been thrown
            // We must rollback the transaction
            if (isset($pdo)) {
                $pdo->rollback();
              
			  }
        }
    return true;
    }
    public static function update_data_of_users_tbl($users_tbl_data_filed, $u_id)
    {
        global $pdo;
        /*
        $users_tbl_data_filed = array($user_id,$user_fname,$user_lname,$user_type,$u_pwd,$user_status);
        
        UPDATE `users` SET `usr_id`=[value-1],`password`=[value-2],`role`=[value-3],`fname`=[value-4],`lname`=[value-5],`status`=[value-6] 
        
        */
        $user_id                        = $users_tbl_data_filed[0];
        $user_fname                     = $users_tbl_data_filed[1];
        $user_lname                     = $users_tbl_data_filed[2];
        $user_type                      = $users_tbl_data_filed[3];
        $user_status                    = $users_tbl_data_filed[5];
        $update_data_in_users_tbl_query = "UPDATE `users` SET `usr_id`=:usr_id,`role`=:role,`status`=:usr_status,
		`lname`=:lname,`fname`=:fname WHERE `usr_id`=:usr_id1;";
        $update_data_in_users_tbl_stmt  = $pdo->prepare($update_data_in_users_tbl_query);
        $update_data_in_users_tbl_stmt->bindParam(':usr_id', $user_id, PDO::PARAM_STR);
        $update_data_in_users_tbl_stmt->bindParam(':role', $user_type, PDO::PARAM_INT);
        $update_data_in_users_tbl_stmt->bindParam(':usr_status', $user_status, PDO::PARAM_STR);
        $update_data_in_users_tbl_stmt->bindParam(':lname', $user_lname, PDO::PARAM_STR);
        $update_data_in_users_tbl_stmt->bindParam(':fname', $user_fname, PDO::PARAM_STR);
        $update_data_in_users_tbl_stmt->bindParam(':usr_id1', $u_id, PDO::PARAM_STR);
         return $update_data_in_users_tbl_stmt->execute();
        
    }
    public static function get_info_about_specific_user($u_id)
    {
        global $pdo;
        $get_info_about_specific_user_query = "SELECT  `usr_id`, `role`, `fname`, `lname`, `status`,users.role,user_type.type ,user_type.id  from users,user_type where user_type.id=users.role and usr_id=:usr_id;";
        /*
        $get_all_users_query="SELECT * from users inner join user_type on users.user_type=user_type.id inner join semester on semester.id=users.semester_id inner join semester_names on semester_names.id=semester.semester_id_ref";
        */
        $get_info_about_specific_user_stmt  = $pdo->prepare($get_info_about_specific_user_query);
        $get_info_about_specific_user_stmt->bindParam(':usr_id', $u_id, PDO::PARAM_STR);
        $get_info_about_specific_user_stmt->execute();
        $get_info_about_specific_user_data = $get_info_about_specific_user_stmt->fetchAll();
        return $get_info_about_specific_user_data;
    }
    public static function delete_usr_from_users_tbl($u_id)
    {
        global $pdo;
        $delete_usr_from_users_tbl_query = "DELETE FROM `users` WHERE  usr_id=:usr_id ;";
        $delete_usr_from_users_tbl_stmt  = $pdo->prepare($delete_usr_from_users_tbl_query);
        $delete_usr_from_users_tbl_stmt->bindParam(':usr_id', $u_id, PDO::PARAM_STR);
        $delete_usr_from_users_tbl_stmt->execute();
        $row = $delete_usr_from_users_tbl_stmt->rowCount();
        return $row;
    }
    public static function reset_usr_pass($u_id)
    {
        global $pdo;
        /**/
        $update_data_in_users_tbl_query = "UPDATE `users` SET password=:password WHERE `usr_id`=:usr_id1;";
        $update_data_in_users_tbl_stmt  = $pdo->prepare($update_data_in_users_tbl_query);
        $update_data_in_users_tbl_stmt->bindParam(':password', $u_id, PDO::PARAM_STR);
        $update_data_in_users_tbl_stmt->bindParam(':usr_id1', $u_id, PDO::PARAM_STR);
          return $update_data_in_users_tbl_stmt->execute();
        
       
        /**/
    }
    /*add_date_for_evt.php file*/
    public static function update_evt_tbl($evt_tbl_data_filed, $auto_inc_id, $evt_id)
    {
        global $pdo;
        /*$evt_tbl_data_filed = array($from_date,$to_date,$evt_name);*/
        $from_date            = $evt_tbl_data_filed[0];
        $to_date              = $evt_tbl_data_filed[1];
        $evt_name             = $evt_tbl_data_filed[2];
        $update_evt_tbl_query = "UPDATE `evt_date` SET `semester_id`=:semester_id,`evt_id`=:evt_id,`from_date`=:from_date,`to_date`=:to_date WHERE semester_id=:semester_id1 and evt_id=:evt_id1;";
        $update_evt_tbl_stmt  = $pdo->prepare($update_evt_tbl_query);
        $update_evt_tbl_stmt->bindParam(':from_date', $from_date, PDO::PARAM_STR);
        $update_evt_tbl_stmt->bindParam(':to_date', $to_date, PDO::PARAM_STR);
        $update_evt_tbl_stmt->bindParam(':semester_id', $auto_inc_id, PDO::PARAM_INT);
        $update_evt_tbl_stmt->bindParam(':evt_id', $evt_name, PDO::PARAM_INT);
        $update_evt_tbl_stmt->bindParam(':semester_id1', $auto_inc_id, PDO::PARAM_INT);
        $update_evt_tbl_stmt->bindParam(':evt_id1', $evt_id, PDO::PARAM_INT);
       return $update_evt_tbl_stmt->execute();
       
    }
    public static function get_all_evt_for_specific_year()
    {
        global $pdo;
        $get_all_evt_for_specific_year_query = "SELECT `evt_id`, `from_date`, `to_date`,`id`, `name` FROM `evt_date`,evt,semester
        WHERE evt_id=id and semester.auto_inc_id=evt_date.semester_id and active=1 ORDER BY from_date DESC,to_date DESC;";
        $get_all_evt_for_specific_year_stmt  = $pdo->query($get_all_evt_for_specific_year_query);
        $get_all_evt_for_specific_year_stmt->execute();
        $get_all_evt_for_specific_year_data = $get_all_evt_for_specific_year_stmt->fetchAll();
        return $get_all_evt_for_specific_year_data;
    }
    public static function get_all_evt_name()
    {
        global $pdo;
        $get_all_evt_name_query = "SELECT `id`, `name`, `name_in_en`  FROM `evt` ";
        $get_all_evt_name_stmt  = $pdo->query($get_all_evt_name_query);
        $get_all_evt_name_stmt->execute();
        $get_all_evt_name_data = $get_all_evt_name_stmt->fetchAll();
        return $get_all_evt_name_data;
    }
    public static function get_event_row_count()
    {
        global $pdo;
        $get_user_type_tbl_row_count_query = "SELECT count(*) counter FROM `evt` ;";

        $get_user_type_tbl_row_count_stmt  = $pdo->query($get_user_type_tbl_row_count_query);
        $get_user_type_tbl_row_count_stmt->execute();
        $get_user_type_tbl_row_count_data = $get_user_type_tbl_row_count_stmt->fetchAll();
        $get_user_type_tbl_row_count_res  = $get_user_type_tbl_row_count_data[0]['counter'];
        return $get_user_type_tbl_row_count_res;
    }
    public static function insert_into_evt_tbl($evt_tbl_data_filed, $auto_inc_id)
    {
        global $pdo;
        /*
        $evt_tbl_data_filed = array($from_date,$to_date,$evt_name);
        */
        $from_date                 = $evt_tbl_data_filed[0];
        $to_date                   = $evt_tbl_data_filed[1];
        $evt_name                  = $evt_tbl_data_filed[2];
        $insert_into_evt_tbl_query = "INSERT INTO `evt_date`(`semester_id`, `evt_id`, `from_date`, `to_date`) VALUES
		(:semester_id,:evt_id,:from_date,:to_date);";
        $insert_into_evt_tbl_stmt  = $pdo->prepare($insert_into_evt_tbl_query);
        $insert_into_evt_tbl_stmt->bindParam(':semester_id', $auto_inc_id, PDO::PARAM_INT);
        $insert_into_evt_tbl_stmt->bindParam(':evt_id', $evt_name, PDO::PARAM_INT);
        $insert_into_evt_tbl_stmt->bindParam(':from_date', $from_date, PDO::PARAM_STR);
        $insert_into_evt_tbl_stmt->bindParam(':to_date', $to_date, PDO::PARAM_STR);
        $insert_into_evt_tbl_stmt->execute();
		$row =0;
        $row = $insert_into_evt_tbl_stmt->rowCount();
        return $row;
    }
    public static function get_info_about_specific_evt($evt_id, $auto_inc_id)
    {
        global $pdo;
        $get_info_about_specific_evt_query = "SELECT `semester_id`, `evt_id`, `from_date`, `to_date` FROM `evt_date` WHERE  semester_id=:semester_id and evt_id=:evt_id;";
        $get_info_about_specific_evt_stmt  = $pdo->prepare($get_info_about_specific_evt_query);
        $get_info_about_specific_evt_stmt->bindParam(':evt_id', $evt_id, PDO::PARAM_INT);
        $get_info_about_specific_evt_stmt->bindParam(':semester_id', $auto_inc_id, PDO::PARAM_INT);
        $get_info_about_specific_evt_stmt->execute();
        $get_info_about_specific_evt_data = $get_info_about_specific_evt_stmt->fetchAll();
        return $get_info_about_specific_evt_data;
    }
    public static function check_timeOverLapping_in_active_semester($from_date, $to_date)
    {
        global $pdo;
        $check_timeOverLapping_query = "SELECT name,from_date,to_date FROM evt_date,semester,evt WHERE
(
(:from_date BETWEEN from_date AND to_date) OR
(:to_date BETWEEN from_date AND to_date) OR
(from_date < :from_date AND to_date > :to_date)) 
AND semester.auto_inc_id=evt_date.semester_id and evt_date.semester_id=(select semester.auto_inc_id from semester where active =1 ) and evt.id=evt_date.evt_id ;";
        $check_timeOverLapping_stmt  = $pdo->prepare($check_timeOverLapping_query);
        $check_timeOverLapping_stmt->bindParam(':from_date', $from_date, PDO::PARAM_STR);
        $check_timeOverLapping_stmt->bindParam(':to_date', $to_date, PDO::PARAM_STR);
        $check_timeOverLapping_stmt->execute();
        $rowData = $check_timeOverLapping_stmt->fetchAll();
        return $rowData;
        //if $row=0 => time input by user is true ,else if $row>0 => time input by user is false
    }
    public static function check_timeOverLapping_in_active_semester1($from_date, $to_date,$evt_id)
    {
        global $pdo;
        $check_timeOverLapping_query = "SELECT name,from_date,to_date FROM evt_date,semester,evt WHERE
(
(:from_date BETWEEN from_date AND to_date) OR
(:to_date BETWEEN from_date AND to_date) OR
(from_date < :from_date AND to_date > :to_date)) 
AND semester.auto_inc_id=evt_date.semester_id and 
evt_id!=:evt_id
and
evt_date.semester_id=(select semester.auto_inc_id from semester where active =1 ) and evt.id=evt_date.evt_id ;";
        $check_timeOverLapping_stmt  = $pdo->prepare($check_timeOverLapping_query);
        $check_timeOverLapping_stmt->bindParam(':from_date', $from_date, PDO::PARAM_STR);
        $check_timeOverLapping_stmt->bindParam(':to_date', $to_date, PDO::PARAM_STR);
		$check_timeOverLapping_stmt->bindParam(':evt_id', $evt_id, PDO::PARAM_INT);
		
        $check_timeOverLapping_stmt->execute();
        $rowData = $check_timeOverLapping_stmt->fetchAll();
        return $rowData;
        //if $row=0 => time input by user is true ,else if $row>0 => time input by user is false
    }
    /*
    SELECT `id`, `name`, `evt_order`, `evt_date`.`semester_id`, `evt_id`, `from_date`, `to_date`  FROM `evt`,`evt_date`,semester where evt_date.evt_id=evt.id and evt_date.semester_id=semester.auto_inc_id and semester.active=1 order by from_date,to_date ASC;
    
    SELECT `id`, `name`, `evt_order` from evt order by evt_order asc;
    */
    public static function delete_evt_from_specific_year($auto_inc_id, $evt_id)
    {
        global $pdo;
        $delete_evt_from_specific_year_query = "DELETE FROM `evt_date` WHERE `semester_id`=:semester_id and `evt_id`=:evt_id;";
        $delete_evt_from_specific_year_stmt  = $pdo->prepare($delete_evt_from_specific_year_query);
        $delete_evt_from_specific_year_stmt->bindParam(':semester_id', $auto_inc_id, PDO::PARAM_INT);
        $delete_evt_from_specific_year_stmt->bindParam(':evt_id', $evt_id, PDO::PARAM_INT);
        $delete_evt_from_specific_year_stmt->execute();
        $row = $delete_evt_from_specific_year_stmt->rowCount();
        return $row;
    }
    public static function get_grp_info_for_specific_user($semester_id, $usr_id, $status)
    {
        global $pdo;
        $get_grp_info_for_specific_user_query = "SELECT `grp_id`, `grp_name`, `owner` FROM `group_members`,groups WHERE grp_id=group_id and student_id=:student_id  and status=:status and semester_id=:semester_id ;";
        $get_grp_info_for_specific_user_stmt  = $pdo->prepare($get_grp_info_for_specific_user_query);
        $get_grp_info_for_specific_user_stmt->bindParam(':semester_id', $semester_id, PDO::PARAM_INT);
        $get_grp_info_for_specific_user_stmt->bindParam(':student_id', $usr_id, PDO::PARAM_STR);
        $get_grp_info_for_specific_user_stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $get_grp_info_for_specific_user_stmt->execute();
        $get_grp_info_for_specific_user_data = $get_grp_info_for_specific_user_stmt->fetchAll();
        return $get_grp_info_for_specific_user_data;
    }
    public static function chk_if_has_other_grp($student_id, $semester_id)
    {
        global $pdo;
        $status                     = "accepted";
        $chk_if_has_other_grp_query = "SELECT `grp_id`, `grp_name`, `owner`, `semester_id`, `thesis`,
		`thesis_submission_date`, `grp_creation_date`,`group_id`, `student_id`, `status` FROM `group_members` ,`groups`
		WHERE  status=:status and grp_id=group_id and owner!=:usr_login1 and student_id=:usr_login and `semester_id`=:semester_id;";
        $chk_if_has_other_grp_stmt  = $pdo->prepare($chk_if_has_other_grp_query);
        $chk_if_has_other_grp_stmt->bindParam(':usr_login1', $student_id, PDO::PARAM_STR);
        $chk_if_has_other_grp_stmt->bindParam(':usr_login', $student_id, PDO::PARAM_STR);
        $chk_if_has_other_grp_stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $chk_if_has_other_grp_stmt->bindParam(':semester_id', $semester_id, PDO::PARAM_INT);
        $chk_if_has_other_grp_stmt->execute();
        $chk_if_has_other_grp_data = $chk_if_has_other_grp_stmt->fetchAll();
        return $chk_if_has_other_grp_data;
    }
    public static function set_status_to_accepted_for_grp_owner($grp_name, $semester_id, $owner)
    {
        global $pdo;
        $row1   = 0;
        $row2   = 0;
        $status = "accepted";
        try {
            $pdo->beginTransaction();
            $set_status_to_accepted_for_grp_owner_query = "INSERT INTO `groups` SET `grp_name`=".$pdo->quote($grp_name).",`owner`=".$pdo->quote($owner).",`semester_id`=:semester_id,`grp_creation_date`=NOW();";
            $add_group = $pdo->prepare($set_status_to_accepted_for_grp_owner_query);
            $add_group->bindParam(':semester_id', $semester_id, PDO::PARAM_INT);
            $add_group->execute() ; 
                 // $add_group->closeCursor();
            $group_id = $pdo->lastInsertId();
        $delete_usr_from_prev_group_query=     "DELETE gm
FROM group_members gm
INNER JOIN groups g
  ON g.grp_id=gm.group_id 
  INNER JOIN semester s
  ON s.auto_inc_id=g.semester_id 
  WHERE
  s.active=1 AND gm.student_id=:std_id";
  $delete_usr_from_prev_group_stmt = $pdo->prepare($delete_usr_from_prev_group_query);
        $delete_usr_from_prev_group_stmt->bindParam(':std_id',$owner,PDO::PARAM_STR);
        $delete_usr_from_prev_group_stmt->execute(); 
         $delete_usr_from_prev_chat_room_query = "DELETE ru
FROM rooms_users ru
INNER JOIN chat_room cr
  ON ru.chat_room_id_fk=cr.chat_room_id
  INNER JOIN groups g
  ON g.chat_room_id_fk=cr.chat_room_id
  INNER JOIN semester s
  ON s.auto_inc_id=g.semester_id 
  WHERE
  s.active=1 AND ru.usr_id=:std_id";
        $delete_usr_from_prev_chat_room_stmt = $pdo->prepare($delete_usr_from_prev_chat_room_query);
        $delete_usr_from_prev_chat_room_stmt->bindParam(':std_id',$owner,PDO::PARAM_STR);
        $delete_usr_from_prev_chat_room_stmt->execute(); 
           $insert_grp_member_query = "INSERT INTO `group_members` SET `group_id`=:group_id,`student_id`=".$pdo->quote($owner).",`status`=".$pdo->quote($status).",join_date=NOW();";
                $add_member  = $pdo->prepare($insert_grp_member_query);
                $add_member->bindParam(':group_id', $group_id, PDO::PARAM_INT);
                $add_member->execute()  ; 
         //  $add_member->closeCursor();
           /**/
          
           /**/
			$insert_chat_into_this_grp_query = 
			"INSERT INTO `chat_room` SET `chat_room_name`=".$pdo->quote($grp_name);
            $insert_chat_into_this_grp_stmt = $pdo->prepare($insert_chat_into_this_grp_query);  
			$insert_chat_into_this_grp_stmt->execute() ; 
          //    $insert_chat_into_this_grp_stmt->closeCursor();
			$lastInsertId= $pdo->lastInsertId();
		 $update_chat_room_id_fk_for_this_usr_query = "UPDATE groups SET  `chat_room_id_fk`=:chat_room_id_fk  WHERE `grp_id`=:group_id ";
		    $update_chat_room_id_fk_for_this_usr_stmt = $pdo->prepare($update_chat_room_id_fk_for_this_usr_query);
			$update_chat_room_id_fk_for_this_usr_stmt->bindParam(':chat_room_id_fk',$lastInsertId,PDO::PARAM_INT);
			$update_chat_room_id_fk_for_this_usr_stmt->bindParam(':group_id',$group_id,PDO::PARAM_INT);
			 $update_chat_room_id_fk_for_this_usr_stmt->execute() ;
// $update_chat_room_id_fk_for_this_usr_stmt->closeCursor();
		$insert_usr_into_chat_room_query = "INSERT INTO `rooms_users` SET `usr_id`=".$pdo->quote($owner).",  `chat_room_id_fk`=:chat_room_id_fk;";
		$insert_usr_into_chat_room_stmt = $pdo->prepare($insert_usr_into_chat_room_query);
		$insert_usr_into_chat_room_stmt->bindParam(':chat_room_id_fk',$lastInsertId,PDO::PARAM_INT);
		$insert_usr_into_chat_room_stmt->execute();
        $pdo->commit();
            return true;
        }
        catch (Exception $e) {
            if (isset($pdo)) {
              $pdo->rollback();
				echo $e->getMessage();
				  return false;
               $err = "ﻟﻢ ﺗﺘﻢ اﻟﻌﻤﻠﻴﺔ ﺑﻨﺠﺎﺡ";
            }
        }
        return $row1 + $row2;
    }
    public static function update_grp_name_for_owner_grp($grp_id, $grp_name, $semester_id, $owner)
    {
        global $pdo;
		try{
        $update_grp_name_for_owner_grp_query = "UPDATE `groups` SET  `grp_name`=".$pdo->quote($grp_name)." where `grp_id`=:grp_id and
		owner=".$pdo->quote($owner)." and semester_id=:semester_id  ;";
        $update_grp_name_for_owner_grp_stmt  = $pdo->prepare($update_grp_name_for_owner_grp_query); 
        $update_grp_name_for_owner_grp_stmt->bindParam(':grp_id', $grp_id, PDO::PARAM_INT); 
        $update_grp_name_for_owner_grp_stmt->bindParam(':semester_id', $semester_id, PDO::PARAM_INT);
        $update_grp_name_for_owner_grp_stmt->execute();
		$get_chat_room_id_fk_query = "SELECT chat_room_id_fk from groups where grp_id=:grp_id;";
		$get_chat_room_id_fk_stmt = $pdo->prepare($get_chat_room_id_fk_query );
		$get_chat_room_id_fk_stmt->bindParam(':grp_id',$grp_id,PDO::PARAM_INT);
		$get_chat_room_id_fk_stmt->execute();
		$data = $get_chat_room_id_fk_stmt->fetchAll();
		$lastInsertId = $data[0]['chat_room_id_fk'];
		$update_chat_room_id_fk_for_this_usr_query = "UPDATE chat_room SET  chat_room_name=".$pdo->quote($grp_name).
        "  WHERE `chat_room_id`=:chat_room_id ";
		    $update_chat_room_id_fk_for_this_usr_stmt = $pdo->prepare($update_chat_room_id_fk_for_this_usr_query); 
			$update_chat_room_id_fk_for_this_usr_stmt->bindParam(':chat_room_id',$lastInsertId,PDO::PARAM_INT);
			
        $update_chat_room_id_fk_for_this_usr_stmt->execute();
		return true;
		}catch(PDOException $ex){
			echo $ex->getMessage();
			return false;
		}
 
    }
    public static function check_if_this_user_is_last_one_in_this_grp($grp_id, $semester_id, $usr_id)
    {
        global $pdo;
        $status                                           = "accepted";
        //get group count
        $check_if_this_user_is_last_one_in_this_grp_query = "SELECT count(*) counter FROM `group_members`,`groups` WHERE grp_id=group_id and grp_id=:grp_id  and semester_id=:semester_id and status=:status and student_id!=:usr_id;";
        $check_if_this_user_is_last_one_in_this_grp_stmt  = $pdo->prepare($check_if_this_user_is_last_one_in_this_grp_query);
        $check_if_this_user_is_last_one_in_this_grp_stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $check_if_this_user_is_last_one_in_this_grp_stmt->bindParam(':usr_id', $usr_id, PDO::PARAM_STR);
        $check_if_this_user_is_last_one_in_this_grp_stmt->bindParam(':semester_id', $semester_id, PDO::PARAM_INT);
        $check_if_this_user_is_last_one_in_this_grp_stmt->bindParam(':grp_id', $grp_id, PDO::PARAM_INT);
        $check_if_this_user_is_last_one_in_this_grp_stmt->execute();
        $grp_count    = $check_if_this_user_is_last_one_in_this_grp_stmt->fetchAll();
       $grp_coun_val =0;
	   $grp_coun_val = $grp_count[0]['counter'];
        return $grp_coun_val;
    }
    public static function update_usr_grp_by_join_into_other_grp($grp_name, $owner, $semester_id)
    {
        //add transaction
        global $pdo;
        $status = "accepted";
        try {
            $pdo->beginTransaction();
            $set_status_to_accepted_for_grp_owner_query = "
            INSERT INTO `groups`
            SET grp_name=:grp_name,
            owner=:owner,
            semester_id=:semester_id,
            grp_creation_date=now();";
            $set_status_to_accepted_for_grp_owner_stmt  = $pdo->prepare($set_status_to_accepted_for_grp_owner_query);
            $set_status_to_accepted_for_grp_owner_stmt->bindParam(':grp_name', $grp_name, PDO::PARAM_STR);
            $set_status_to_accepted_for_grp_owner_stmt->bindParam(':semester_id', $semester_id, PDO::PARAM_INT);
            $set_status_to_accepted_for_grp_owner_stmt->bindParam(':owner', $owner, PDO::PARAM_STR);
            
             $set_status_to_accepted_for_grp_owner_stmt->execute();
            $row1                                       = $set_status_to_accepted_for_grp_owner_stmt->rowCount();
            $grp_id                                     = $pdo->lastInsertId();
            $set_status_to_accepted_for_grp_owner_query = "DELETE gm FROM group_members gm INNER JOIN groups g ON gm.group_id= g.grp_id INNER JOIN semester s ON g.semester_id= s.auto_inc_id AND s.active=1 AND gm.student_id=:student_id";
            $set_status_to_accepted_for_grp_owner_stmt  = $pdo->prepare($set_status_to_accepted_for_grp_owner_query);
             $set_status_to_accepted_for_grp_owner_stmt->bindParam(':student_id', $owner, PDO::PARAM_STR); 
           $set_status_to_accepted_for_grp_owner_stmt->execute(); 
            /**/

              $delete_usr_from_prev_chat_room_query = "DELETE ru
FROM rooms_users ru
INNER JOIN chat_room cr
  ON ru.chat_room_id_fk=cr.chat_room_id
  INNER JOIN groups g
  ON g.chat_room_id_fk=cr.chat_room_id
  INNER JOIN semester s
  ON s.auto_inc_id=g.semester_id 
  WHERE
  s.active=1 AND ru.usr_id=:std_id";
        $delete_usr_from_prev_chat_room_stmt = $pdo->prepare($delete_usr_from_prev_chat_room_query);
        $delete_usr_from_prev_chat_room_stmt->bindParam(':std_id',$owner,PDO::PARAM_STR);
        $delete_usr_from_prev_chat_room_stmt->execute(); 
 $set_status_to_accepted_for_grp_owner_query = "INSERT INTO  `group_members` SET `group_id`=:group_id,`status`=:status 
            , student_id=:student_id;";
            $set_status_to_accepted_for_grp_owner_stmt  = $pdo->prepare($set_status_to_accepted_for_grp_owner_query);
            $set_status_to_accepted_for_grp_owner_stmt->bindParam(':group_id', $grp_id, PDO::PARAM_INT);
            $set_status_to_accepted_for_grp_owner_stmt->bindParam(':student_id', $owner, PDO::PARAM_STR);
            $set_status_to_accepted_for_grp_owner_stmt->bindParam(':status', $status, PDO::PARAM_STR);
            $set_status_to_accepted_for_grp_owner_stmt->execute();
          
            /**/
            /**/
			$insert_chat_into_this_grp_query = 
			"
			INSERT INTO `chat_room` SET 
			`chat_room_name`=".$pdo->quote($grp_name)." ;
			";
		    $insert_chat_into_this_grp_stmt = $pdo->prepare($insert_chat_into_this_grp_query);  
			$insert_chat_into_this_grp_stmt->execute();
			$lastInsertId = $pdo->lastInsertId();
			/**/
			$update_chat_room_id_fk_for_this_usr_query = "UPDATE groups SET  `chat_room_id_fk`=:chat_room_id_fk  WHERE `grp_id`=:group_id ";
		    $update_chat_room_id_fk_for_this_usr_stmt = $pdo->prepare($update_chat_room_id_fk_for_this_usr_query);
			$update_chat_room_id_fk_for_this_usr_stmt->bindParam(':chat_room_id_fk',$lastInsertId,PDO::PARAM_INT);
			$update_chat_room_id_fk_for_this_usr_stmt->bindParam(':group_id',$grp_id,PDO::PARAM_INT);
			$update_chat_room_id_fk_for_this_usr_stmt->execute();

			/**/
			$insert_users_room_query = "
										INSERT INTO `rooms_users` SET
										`usr_id`=".$pdo->quote($owner).", `chat_room_id_fk` =:chat_room_id_fk;";
			$insert_users_room_stmt = $pdo->prepare($insert_users_room_query);
			$insert_users_room_stmt->bindParam(':chat_room_id_fk',$lastInsertId,PDO::PARAM_INT);
			$insert_users_room_stmt->execute();
			
			
			/**/
            $pdo->commit();
           
        }
        catch (Exception $e) {
            if (isset($pdo)) {
             echo    $e->getMessage();
                return false;
                $pdo->rollback();
               $err = "ﻟﻢ ﺗﺘﻢ اﻟﻌﻤﻠﻴﺔ ﺑﻨﺠﺎﺡ";
            }
        }
        return true;
    }
    public static function check_if_this_owner_make_his_own_group_to_prevent_repeated($owner, $semester_id)
    {
        global $pdo;
        $prevent_join_to_another_one_group_query = "SELECT count(*) counter  FROM `groups` where `owner`=:owner and semester_id=:semester_id;";
        $prevent_join_to_another_one_group_stmt  = $pdo->prepare($prevent_join_to_another_one_group_query);
        $prevent_join_to_another_one_group_stmt->bindParam(':owner', $owner, PDO::PARAM_STR);
        $prevent_join_to_another_one_group_stmt->bindParam(':semester_id', $semester_id, PDO::PARAM_INT);
        $prevent_join_to_another_one_group_stmt->execute();
        $row     = $prevent_join_to_another_one_group_stmt->fetchAll();
        $counter = 0;
		$counter = $row[0]['counter'];
        return $counter;
    }
    /*create_my_grp.php*/
    public static function join_into_other_grp_and_del_prev_grp($usr_id, $prev_grp_id, $new_grp_name, $semester_id)
    {
        global $pdo;
        $row1 = 0;
        $row2 = 0;
        try {
            $pdo->beginTransaction();
            $join_into_other_grp_and_del_prev_grp_query = "DELETE FROM `group_members` WHERE group_id=:group_id and student_id=:student_id;";
            $join_into_other_grp_and_del_prev_grp_stmt  = $pdo->prepare($join_into_other_grp_and_del_prev_grp_query);
            $join_into_other_grp_and_del_prev_grp_stmt->bindParam(':group_id', $prev_grp_id, PDO::PARAM_INT);
            $join_into_other_grp_and_del_prev_grp_stmt->bindParam(':student_id', $usr_id, PDO::PARAM_STR);
            $join_into_other_grp_and_del_prev_grp_stmt->execute();
            if ($join_into_other_grp_and_del_prev_grp_stmt) {
                $join_into_other_grp_and_del_prev_grp_query1 = "DELETE FROM `groups` WHERE grp_id=:group_id;";
                $join_into_other_grp_and_del_prev_grp_stmt1  = $pdo->prepare($join_into_other_grp_and_del_prev_grp_query1);
                $join_into_other_grp_and_del_prev_grp_stmt1->bindParam(':group_id', $prev_grp_id, PDO::PARAM_INT);
                $join_into_other_grp_and_del_prev_grp_stmt1->execute();
                if ($join_into_other_grp_and_del_prev_grp_stmt1 && $join_into_other_grp_and_del_prev_grp_stmt1) {
                    $insert_his_new_own_grp_query = "INSERT INTO `groups`
 SET `grp_name`=:grp_name,
  `owner`=:owner, 
  `semester_id`=:semester_id, 
 
  `grp_creation_date`=NOW();";
                    $insert_his_new_own_grp_stmt  = $pdo->prepare($insert_his_new_own_grp_query);
                    $insert_his_new_own_grp_stmt->bindParam(':grp_name', $new_grp_name, PDO::PARAM_STR);
                    $insert_his_new_own_grp_stmt->bindParam(':owner', $usr_id, PDO::PARAM_STR);
                    $insert_his_new_own_grp_stmt->bindParam(':semester_id', $semester_id, PDO::PARAM_INT);
                    $insert_his_new_own_grp_stmt->execute();
                    $row1   = $insert_his_new_own_grp_stmt->rowCount();
                    $grp_id = $pdo->lastInsertId();
                    if ($insert_his_new_own_grp_stmt) {
                        $update_prev_grp_query = "INSERT INTO `group_members` SET `group_id`=:group_id ,
   `student_id`=:student_id,status=:status;";
                        $status                = "accepted";
                        $update_prev_grp_stmt  = $pdo->prepare($update_prev_grp_query);
                        $update_prev_grp_stmt->bindParam(':group_id', $grp_id, PDO::PARAM_INT);
                        $update_prev_grp_stmt->bindParam(':student_id', $usr_id, PDO::PARAM_STR);
                        $update_prev_grp_stmt->bindParam(':status', $status, PDO::PARAM_STR);
                        $update_prev_grp_stmt->execute();
                        $row2 = $update_prev_grp_stmt->rowCount();
                        if ($update_prev_grp_stmt) {
                            return $row1 + $row2;
                        }
                    }
                }
            }
			
			$last_grp_id = $pdo->lastInsertId();
			
			$insert_chat_into_this_grp_query = 
			"
			INSERT INTO `chat_room` SET 
			`chat_room_name`=".$pdo->quote($new_grp_name).";
			";
		    $insert_chat_into_this_grp_stmt = $pdo->prepare($insert_chat_into_this_grp_query); 
			$insert_chat_into_this_grp_stmt->bindParam(':grp_id_fk',$last_grp_id,PDO::PARAM_INT);
			$insert_chat_into_this_grp_stmt->execute();
			$lastInsertId= $pdo->lastInsertId();
			/**/
			$update_chat_room_id_fk_for_this_usr_query = "UPDATE groups SET  `chat_room_id_fk`=:chat_room_id_fk  WHERE `grp_id`=:grp_id ";
		    $update_chat_room_id_fk_for_this_usr_stmt = $pdo->prepare($update_chat_room_id_fk_for_this_usr_query);
			$update_chat_room_id_fk_for_this_usr_stmt->bindParam(':chat_room_id_fk ',$lastInsertId,PDO::PARAM_INT);
			$update_chat_room_id_fk_for_this_usr_stmt->bindParam(':grp_id ',$grp_id,PDO::PARAM_INT);
			$update_chat_room_id_fk_for_this_usr_stmt->execute();

			/**/
			
			$insert_usr_into_users_room_query  = "INSERT INTO `rooms_users` SET 
												`usr_id`=".$pdo->quote($usr_id).",
												`chat_room_id_fk`=:chat_room_id_fk ;";
			$insert_usr_into_users_room_stmt = $pdo->prepare($insert_usr_into_users_room_query);
			$insert_usr_into_users_room_stmt->bindParam(':chat_room_id_fk',$lastInsertId,PDO::PARAM_INT);
			$insert_usr_into_users_room_stmt->execute();
			/**/
            $pdo->commit();
            
        }
        catch (Exception $e) {
            if (isset($pdo)) {
                return 0;
                $pdo->rollback();
               $err = "ﻟﻢ ﺗﺘﻢ اﻟﻌﻤﻠﻴﺔ ﺑﻨﺠﺎﺡ";
            }
        }
        return $row1 + $row2;
    }
    public static function get_all_grp_has_less_than_five_members($student_id, $maximum_no_of_grp_mem)
    {
        global $pdo;
        $status                                          = "accepted";
        // $status1="pending";
        $maximum_no_of_grp_mem                           = $maximum_no_of_grp_mem - 1;
        $res_row_no                                      = 0;
        $get_grps_has_less_than_fixed_no_of_member_query = "SELECT count(*) counter FROM `groups`,`group_members` WHERE (SELECT  COUNT(*) counter FROM `group_members` group by `group_id` between 1 and :maximum_no_of_grp_mem  ) AND (group_id NOT IN (SELECT `grp_id`  FROM `groups` WHERE  `owner`=:student_id ) ) AND grp_id=group_id AND semester_id = (SELECT semester_id FROM semester WHERE active=1) ;
 ";
        $get_grps_has_less_than_fixed_no_of_member_stmt  = $pdo->prepare($get_grps_has_less_than_fixed_no_of_member_query);
        $get_grps_has_less_than_fixed_no_of_member_stmt->bindParam(':maximum_no_of_grp_mem', $maximum_no_of_grp_mem, PDO::PARAM_INT);
        $get_grps_has_less_than_fixed_no_of_member_stmt->bindParam(':student_id', $student_id, PDO::PARAM_STR); 
        // $get_grps_has_less_than_fixed_no_of_member_stmt->bindParam(':status1',$status1,PDO::PARAM_STR);
        $get_grps_has_less_than_fixed_no_of_member_stmt->execute();
        $arr_data   = $get_grps_has_less_than_fixed_no_of_member_stmt->fetchAll();
        $res_row_no = 0;
		$res_row_no = $arr_data[0]['counter'];
        return $res_row_no;
    }
    public static function return_grp_send_request_prev($student_id, $grp_id, $maximum_no_of_grp_mem)
    {
        global $pdo;
        $status                                          = "pending";
        // $status1="pending";
        $maximum_no_of_grp_mem                           = $maximum_no_of_grp_mem - 1;
        $res_row_no                                      = 0;
        $get_grps_has_less_than_fixed_no_of_member_query = "
 SELECT grp_id
FROM `groups`,`group_members` 
WHERE 
(SELECT  COUNT(*) counter FROM `group_members` group by `group_id` between 1 and :maximum_no_of_grp_mem  )
AND 

(
    grp_id IN
 (SELECT group_id FROM group_members WHERE student_id=:student_id AND status=:status ) 

) 
AND 
grp_id=group_id 
 
AND 
semester_id = 
(
    SELECT semester_id FROM semester WHERE active=1
);
 ";
        $get_grps_has_less_than_fixed_no_of_member_stmt  = $pdo->prepare($get_grps_has_less_than_fixed_no_of_member_query);
        $get_grps_has_less_than_fixed_no_of_member_stmt->bindParam(':maximum_no_of_grp_mem', $maximum_no_of_grp_mem, PDO::PARAM_INT);
        $get_grps_has_less_than_fixed_no_of_member_stmt->bindParam(':student_id', $student_id, PDO::PARAM_STR);
        $get_grps_has_less_than_fixed_no_of_member_stmt->bindParam(':status', $status, PDO::PARAM_STR);
        // $get_grps_has_less_than_fixed_no_of_member_stmt->bindParam(':status1',$status1,PDO::PARAM_STR);
        $get_grps_has_less_than_fixed_no_of_member_stmt->execute();
        $arr_data = $get_grps_has_less_than_fixed_no_of_member_stmt->fetchAll();
        return $arr_data;
    }
    public static function get_specific_no_of_grp_member_row($student_id, $maximum_no_of_grp_mem, $this_page_first_result, $results_per_page)
    {
        global $pdo;
        $status                                          = "accepted";
        $maximum_no_of_grp_mem                           = $maximum_no_of_grp_mem - 1;
        $res_row_no                                      = 0;
        $get_grps_has_less_than_fixed_no_of_member_query = "SELECT `grp_id`, `grp_name`, `owner`, `semester_id`, `thesis`, `thesis_submission_date`, `grp_creation_date`,`group_id`, `student_id`,concat(fname,' ',lname) std_name, group_members.status, `new_member_added_by_whom` FROM `groups`,`group_members`,users WHERE (SELECT  COUNT(*) counter FROM `group_members` group by `group_id` between 1 and :maximum_no_of_grp_mem  ) AND (group_id NOT IN (SELECT `grp_id`  FROM `groups` WHERE  `owner`=:student_id ) ) AND grp_id=group_id AND owner=usr_id AND student_id=usr_id  AND semester_id = (SELECT semester_id FROM semester WHERE active=1) LIMIT :this_page_first_result,:results_per_page ;
 ";
        // $status1="pending";
        $get_grps_has_less_than_fixed_no_of_member_stmt  = $pdo->prepare($get_grps_has_less_than_fixed_no_of_member_query);
        $get_grps_has_less_than_fixed_no_of_member_stmt->bindParam(':maximum_no_of_grp_mem', $maximum_no_of_grp_mem, PDO::PARAM_INT);
        $get_grps_has_less_than_fixed_no_of_member_stmt->bindParam(':student_id', $student_id, PDO::PARAM_STR);  
        $get_grps_has_less_than_fixed_no_of_member_stmt->bindParam(':this_page_first_result', $this_page_first_result, PDO::PARAM_INT);
        $get_grps_has_less_than_fixed_no_of_member_stmt->bindParam(':results_per_page', $results_per_page, PDO::PARAM_INT);
        $get_grps_has_less_than_fixed_no_of_member_stmt->execute();
        $arr_data = $get_grps_has_less_than_fixed_no_of_member_stmt->fetchAll();
        // $res_row_no = $get_grps_has_less_than_fixed_no_of_member_stmt->rowCount();
        return $arr_data;
    }
    public static function get_grp_member_for_specific_grp($group_id)
    {
        global $pdo;
        $get_grp_member_for_specific_grp_query = "SELECT `group_id`, `student_id`,  `new_member_added_by_whom`, 
		`usr_id`,concat(`fname`,' ', `lname`) as name FROM `group_members`,users WHERE group_id=:group_id and usr_id=student_id and group_members.status='accepted';";
        $get_grp_member_for_specific_grp_stmt  = $pdo->prepare($get_grp_member_for_specific_grp_query);
        $get_grp_member_for_specific_grp_stmt->bindParam(':group_id', $group_id, PDO::PARAM_INT);
        $get_grp_member_for_specific_grp_stmt->execute();
        $row = $get_grp_member_for_specific_grp_stmt->fetchAll();
        return $row;
    }
    /*join_into_another_grp.php*/
    public static function check_if_he_has_a_grp($student_id)
    {
        global $pdo;
        $status                      = "accepted";
        $row                         = 0;
        $check_if_he_has_a_grp_query = "SELECT count(*) AS counter FROM `group_members`,groups WHERE status=:status and student_id=:student_id AND group_members.group_id=groups.grp_id AND groups.semester_id = (SELECT semester.auto_inc_id FROM semester WHERE semester.active=1);";
        $check_if_he_has_a_grp_stmt  = $pdo->prepare($check_if_he_has_a_grp_query);
        $check_if_he_has_a_grp_stmt->bindParam(':student_id', $student_id, PDO::PARAM_STR);
        $check_if_he_has_a_grp_stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $check_if_he_has_a_grp_stmt->execute();
        $row = $check_if_he_has_a_grp_stmt->fetchAll();
        if ($row!=null) {
           $row = $row[0]['counter'];
        }
       
        return $row;
    }
    /**/
 public static function check_if_he_has_a_grp1($student_id)
    {
        global $pdo;
        $status                      = "accepted";
        $row                         = 0;
        $check_if_he_has_a_grp_query = "SELECT * FROM `group_members`,groups WHERE status=:status and student_id=:student_id AND group_members.group_id=groups.grp_id AND groups.semester_id = (SELECT semester.auto_inc_id FROM semester WHERE semester.active=1);";
        $check_if_he_has_a_grp_stmt  = $pdo->prepare($check_if_he_has_a_grp_query);
        $check_if_he_has_a_grp_stmt->bindParam(':student_id', $student_id, PDO::PARAM_STR);
        $check_if_he_has_a_grp_stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $check_if_he_has_a_grp_stmt->execute();
        return $check_if_he_has_a_grp_stmt->fetchAll();
         
    }
    /**/
    public static function send_a_join_request($grp_id, $std_id)
    {
        global $pdo;


        $status                    = "pending";
        $send_a_join_request_query = "INSERT INTO `group_members`
SET 
`group_id`=:group_id,
`student_id`=:student_id
, `status`=:status,
 join_date=NOW();";
        $send_a_join_request_stmt  = $pdo->prepare($send_a_join_request_query);
        $send_a_join_request_stmt->bindParam(':group_id', $grp_id, PDO::PARAM_STR);
        $send_a_join_request_stmt->bindParam(':student_id', $std_id, PDO::PARAM_STR);
        $send_a_join_request_stmt->bindParam(':status', $status, PDO::PARAM_INT);
        $send_a_join_request_stmt->execute();
        $row = $send_a_join_request_stmt->rowCount();
        return $row;
    }
    public static function update_reject_status_into_pending($student_id,$grp_id){

        global $pdo;
        $status="pending";
        $update_reject_status_into_pending_query = 
        "UPDATE `group_members` 
        SET 
        `status`=:status  
        WHERE  
        `group_id`=:grp_id 
        AND 
        `student_id`=:student_id ;";
        $update_reject_status_into_pending_stmt = $pdo->prepare($update_reject_status_into_pending_query) ;

        $update_reject_status_into_pending_stmt->bindParam(':status', $status,PDO::PARAM_STR);
        
        $update_reject_status_into_pending_stmt->bindParam(':student_id',$student_id,PDO::PARAM_STR);
        $update_reject_status_into_pending_stmt->bindParam(':grp_id',$grp_id,PDO::PARAM_INT);
           return  $update_reject_status_into_pending_stmt->execute();

    }
    public static function delete_grp_req($std_id, $grp_id)
    {
        global $pdo;
        $send_a_join_request_query = "DELETE FROM `group_members`
WHERE
`group_id`=:group_id AND
`student_id`=:student_id
 ;";
        $send_a_join_request_stmt  = $pdo->prepare($send_a_join_request_query);
        $send_a_join_request_stmt->bindParam(':group_id', $grp_id, PDO::PARAM_STR);
        $send_a_join_request_stmt->bindParam(':student_id', $std_id, PDO::PARAM_STR);
        $send_a_join_request_stmt->execute();
        $row = $send_a_join_request_stmt->rowCount();
        return $row;
    }
    //follow up request file
    public static function is_in_array($array, $key, $key_value)
    {
        $within_array = 'no';
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                $within_array = self::is_in_array($v, $key, $key_value);
                if ($within_array == 'yes') {
                    break;
                }
            } else {
                if ($v == $key_value && $k == $key) {
                    $within_array = 'yes';
                    break;
                }
            }
        }
        return $within_array;
    }
    public static function get_status_of_row_grp($grp_id,$student_id){
global $pdo;
$status= null;
$get_status_of_row_grp_query = "SELECT status FROM `group_members` WHERE `group_id`=:grp_id AND `student_id`=:student_id;";
$get_status_of_row_grp_stmt = $pdo->prepare($get_status_of_row_grp_query);
$get_status_of_row_grp_stmt->bindParam(':grp_id',$grp_id,PDO::PARAM_INT);
$get_status_of_row_grp_stmt->bindParam(':student_id',$student_id,PDO::PARAM_STR);
$get_status_of_row_grp_stmt->execute();
$data = $get_status_of_row_grp_stmt->fetchAll();
if($data!=null){
$status=  $data[0]['status'];
}
return $status;
    }
    public static function check_if_this_std_send_a_request_into_another_grp($grp_id,$student_id){

        global $pdo;
        $counter = 0;
        $check_if_this_std_send_a_request_into_another_grp_query = "SELECT COUNT(*) AS counter FROM `group_members` WHERE `group_id`!=:grp_id AND student_id=:student_id;";
$check_if_this_std_send_a_request_into_another_grp_stmt = $pdo->prepare($check_if_this_std_send_a_request_into_another_grp_query);
$check_if_this_std_send_a_request_into_another_grp_stmt->bindParam(':grp_id',$grp_id,PDO::PARAM_INT);
$check_if_this_std_send_a_request_into_another_grp_stmt->bindParam(':student_id',$student_id,PDO::PARAM_STR);
$check_if_this_std_send_a_request_into_another_grp_stmt->execute();
$data = $check_if_this_std_send_a_request_into_another_grp_stmt->fetchAll();
if($data!=null){
$counter =  $data[0]['counter'];
}
return $counter;
    }
    public static function check_if_he_is_a_last_one_in_his_own_grp($owner,$groupID )
    {  
        global $pdo;
        $row=0;
        $status                                         = 'accepted';
        $check_if_he_has_his_own_grp_and_last_one_query = "SELECT count(*) counter  FROM `group_members`,groups  WHERE  status=:status AND group_id=:group_id and student_id!=:owner and groups.grp_id=group_members.group_id   AND groups.semester_id in (SELECT semester.auto_inc_id FROM semester where  semester.active=1);";
        $check_if_he_has_his_own_grp_and_last_one_stmt  = $pdo->prepare($check_if_he_has_his_own_grp_and_last_one_query);
        $check_if_he_has_his_own_grp_and_last_one_stmt->bindParam(':owner', $owner, PDO::PARAM_STR);
        $check_if_he_has_his_own_grp_and_last_one_stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $check_if_he_has_his_own_grp_and_last_one_stmt->bindParam(':group_id', $groupID, PDO::PARAM_INT);  

        $check_if_he_has_his_own_grp_and_last_one_stmt->execute();
        $arr_data = $check_if_he_has_his_own_grp_and_last_one_stmt->fetchAll();
        if ($arr_data!=null) {
          $row=$arr_data[0]['counter'];
        } 
        return $row;

    }

    public static function check_if_he_has_his_own_grp($owner)
    {
        global $pdo;
        $row=0;
        $status                                         = 'accepted';
        $check_if_he_has_his_own_grp_and_last_one_query = "SELECT count(*) as counter  FROM  groups  WHERE 
         owner=:owner
        and semester_id in (SELECT `auto_inc_id`  FROM `semester` WHERE `active`=1)
           ";
        $check_if_he_has_his_own_grp_and_last_one_stmt  = $pdo->prepare($check_if_he_has_his_own_grp_and_last_one_query);
        $check_if_he_has_his_own_grp_and_last_one_stmt->bindParam(':owner', $owner, PDO::PARAM_STR); 
        $check_if_he_has_his_own_grp_and_last_one_stmt->execute();
        $arr_data = $check_if_he_has_his_own_grp_and_last_one_stmt->fetchAll();
        if ($arr_data!=null) {
          $row=$arr_data[0]['counter'];
        }
 
        return $row;

    }
    public static function check_if_he_has_other_grp_and_last_one($owner)
    {
        global $pdo;
        $status                                         = 'accepted';
        $check_if_he_has_his_own_grp_and_last_one_query = "SELECT count(*) counter  FROM `group_members`,`groups` WHERE group_id=grp_id and owner!=:owner and status=:status and student_id!=:owner1;";
        $check_if_he_has_his_own_grp_and_last_one_stmt  = $pdo->prepare($check_if_he_has_his_own_grp_and_last_one_query);
        $check_if_he_has_his_own_grp_and_last_one_stmt->bindParam(':owner', $owner, PDO::PARAM_STR);
        $check_if_he_has_his_own_grp_and_last_one_stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $check_if_he_has_his_own_grp_and_last_one_stmt->bindParam(':owner1', $owner, PDO::PARAM_STR);
        $check_if_he_has_his_own_grp_and_last_one_stmt->execute();
        $arr_data = $check_if_he_has_his_own_grp_and_last_one_stmt->fetchAll();
        return $arr_data[0]['counter'];
    }
    /*follow_up_grp_request.php*/
    public static function get_all_grp_has_a_pending_or_accepted_request_status($student_id)
    {
        global $pdo;
        //AND (grp_m.status  = 'pending' OR grp_m.status  = 'accepted')
        $pending_members = $pdo->prepare("SELECT * FROM `users` us 
                            INNER JOIN `student` st ON( st.id = us.usr_id)
                            INNER JOIN `group_members` grp_m ON( grp_m.student_id = us.usr_id)
                            INNER JOIN `groups` grp ON( grp.grp_id = grp_m.group_id)
                            INNER JOIN `semester` sm ON( sm.semester_id = grp.semester_id)
                            WHERE grp.owner   =:student_id
                             AND student_id!=:student_id1
                             
                            AND sm.active     = 1
                        ");
        $pending_members->bindParam(':student_id', $student_id, PDO::PARAM_STR);
        $pending_members->bindParam(':student_id1', $student_id, PDO::PARAM_STR);
        $pending_members->execute();
        return $pending_members->rowCount();
    }
    public static function get_group_requests_for_specific_group_owner($student_id, $start, $rec_limit)
    {
        $row_count = 0;
        global $pdo;
        // AND (grp_m.status  = 'pending' OR grp_m.status  = 'accepted' )
        $pending_members = $pdo->prepare("SELECT * FROM `users` us 
                            INNER JOIN `student` st ON( st.id = us.usr_id)
                            INNER JOIN `group_members` grp_m ON( grp_m.student_id = us.usr_id)
                            INNER JOIN `groups` grp ON( grp.grp_id = grp_m.group_id)
                            INNER JOIN `semester` sm ON( sm.semester_id = grp.semester_id)
                            WHERE grp.owner   =:student_id
                           
                            AND sm.active     = 1
                            AND student_id!=:student_id1
                            LIMIT :start,:rec_limit;
                        ");
        $pending_members->bindParam(':student_id', $student_id, PDO::PARAM_STR);
        $pending_members->bindParam(':student_id1', $student_id, PDO::PARAM_STR);
        $pending_members->bindParam(':start', $start, PDO::PARAM_INT);
        $pending_members->bindParam(':rec_limit', $rec_limit, PDO::PARAM_INT);
        $pending_members->execute();
        return $pending_members->fetchall();
    }
    public static function check_if_he_is_an_admin_of_any_group($std_id)
    {
        global $pdo;
        $admin_of_grp_query = "SELECT grp_id from groups WHERE owner=".$pdo->quote($std_id);
        $admin_of_grp_stmt  = $pdo->query($admin_of_grp_query);
        //$admin_of_grp_stmt->bindParam(':std_id', $std_id, PDO::PARAM_STR);
        $admin_of_grp_stmt->execute();
        $admin_of_grp_data = $admin_of_grp_stmt->fetchAll();
	 
        return $admin_of_grp_data;
    }
    public static function return_grp_send_request_prev1($student_id, $group_id)
    {
        global $pdo;
        $status                                          = "accepted";
        // $status1="pending";
        $res_row_no                                      = 0;
        $get_grps_has_less_than_fixed_no_of_member_query = "SELECT grp_id
FROM `groups`,`group_members` 
WHERE   

(
    grp_id IN
 (SELECT group_id FROM group_members WHERE student_id=:student_id AND status=:status AND group_id=:group_id ) 

) 
AND 
grp_id=group_id 
 
AND 
semester_id = 
(
    SELECT semester_id FROM semester WHERE active=1
);
 ";
        $get_grps_has_less_than_fixed_no_of_member_stmt  = $pdo->prepare($get_grps_has_less_than_fixed_no_of_member_query);
        $get_grps_has_less_than_fixed_no_of_member_stmt->bindParam(':group_id', $group_id, PDO::PARAM_INT);
        $get_grps_has_less_than_fixed_no_of_member_stmt->bindParam(':student_id', $student_id, PDO::PARAM_STR);
        $get_grps_has_less_than_fixed_no_of_member_stmt->bindParam(':status', $status, PDO::PARAM_STR);
        // $get_grps_has_less_than_fixed_no_of_member_stmt->bindParam(':status1',$status1,PDO::PARAM_STR);
        $get_grps_has_less_than_fixed_no_of_member_stmt->execute();
        $arr_data = $get_grps_has_less_than_fixed_no_of_member_stmt->fetchAll();
        return $arr_data;
    }
    public static function return_grp_send_pending_request_prev1($student_id, $group_id)
    {
        global $pdo;
        $status                                          = "pending";
        // $status1="pending";
        $res_row_no                                      = 0;
        $get_grps_has_less_than_fixed_no_of_member_query = "SELECT grp_id
FROM `groups`,`group_members` 
WHERE   

(
    grp_id IN
 (SELECT group_id FROM group_members WHERE student_id=:student_id AND status=:status and group_id=:group_id ) 

) 
AND 
grp_id=group_id 
 
AND 
semester_id = 
(
    SELECT semester_id FROM semester WHERE active=1
);
 ";
        $get_grps_has_less_than_fixed_no_of_member_stmt  = $pdo->prepare($get_grps_has_less_than_fixed_no_of_member_query);
        $get_grps_has_less_than_fixed_no_of_member_stmt->bindParam(':group_id', $group_id, PDO::PARAM_INT);
        $get_grps_has_less_than_fixed_no_of_member_stmt->bindParam(':student_id', $student_id, PDO::PARAM_STR);
        $get_grps_has_less_than_fixed_no_of_member_stmt->bindParam(':status', $status, PDO::PARAM_STR);
        // $get_grps_has_less_than_fixed_no_of_member_stmt->bindParam(':status1',$status1,PDO::PARAM_STR);
        $get_grps_has_less_than_fixed_no_of_member_stmt->execute();
        $arr_data = $get_grps_has_less_than_fixed_no_of_member_stmt->fetchAll();
        return $arr_data;
    }
    public static function del_all_request_and_change_its_grp_owner_into_oldest_member_and_accept_in_this_grp($std_id, $grp_admin_id, $owner_id, $prev_requests_member_grp_id)
    {
     $arr = array($std_id, $grp_admin_id, $owner_id, $prev_requests_member_grp_id);
     for ($i=0; $i <count($arr ) ; $i++) { 
         echo "<br>".$i.'. '.$arr[$i]; 
     }
     
        $row1 = 0;
        $row2 = 0;
        $row3 = 0;
        global $pdo;

        try {


            $pdo->beginTransaction();
			$get_chat_room_id_fk_for_specific_owner_query = "SELECT chat_room_id_fk,grp_id from groups where owner=".$pdo->quote($std_id)." AND semester_id IN (SELECT `auto_inc_id` FROM `semester` WHERE active=1);";
			$get_chat_room_id_fk_for_specific_owner_stmt = $pdo->prepare($get_chat_room_id_fk_for_specific_owner_query);
			$get_chat_room_id_fk_for_specific_owner_stmt->execute();
			$data = $get_chat_room_id_fk_for_specific_owner_stmt->fetchAll();
            $chat_room_id_fk=0;$std_group_id=0;
            if ($data!=null) {
               $chat_room_id_fk = $data[0]['chat_room_id_fk'];
                $std_group_id = $data[0]['grp_id'];
            }
			  
            

           

             /**/
            $get_oldest_member_joining_into_grp_query = "SELECT student_id FROM group_members WHERE group_id=:group_id1 and student_id!=:std_id and status='accepted'  ORDER BY join_date ASC  LIMIT 1";
            $get_oldest_member_joining_into_grp_stmt  = $pdo->prepare($get_oldest_member_joining_into_grp_query);
            $get_oldest_member_joining_into_grp_stmt->bindParam(':std_id', $std_id, PDO::PARAM_STR);
            $get_oldest_member_joining_into_grp_stmt->bindParam(':group_id1', $std_group_id, PDO::PARAM_INT);
            $get_oldest_member_joining_into_grp_stmt->execute();
            $data              = $get_oldest_member_joining_into_grp_stmt->fetchAll();
            $oldest_student_id = $data[0]['student_id']; 
          //  if ($del_prev_request_and_accept_in_owner_grp_stmt) {
                $del_prev_request_and_accept_in_owner_grp_query1 = "UPDATE  groups SET owner=:owner WHERE grp_id=:grp_id";
                $del_prev_request_and_accept_in_owner_grp_stmt1  = $pdo->prepare($del_prev_request_and_accept_in_owner_grp_query1);
                $del_prev_request_and_accept_in_owner_grp_stmt1->bindParam(':owner', $oldest_student_id, PDO::PARAM_STR);
                $del_prev_request_and_accept_in_owner_grp_stmt1->bindParam(':grp_id', $std_group_id, PDO::PARAM_INT);
                $del_prev_request_and_accept_in_owner_grp_stmt1->execute();
                $set_status_to_accepted_for_grp_owner_query = "DELETE gm FROM group_members gm INNER JOIN groups g ON gm.group_id= g.grp_id INNER JOIN semester s ON g.semester_id= s.auto_inc_id AND s.active=1 AND gm.student_id=:student_id AND g.grp_id!=:group_id";
            $set_status_to_accepted_for_grp_owner_stmt  = $pdo->prepare($set_status_to_accepted_for_grp_owner_query);
             $set_status_to_accepted_for_grp_owner_stmt->bindParam(':student_id', $std_id, PDO::PARAM_STR); 
             $set_status_to_accepted_for_grp_owner_stmt->bindParam(':group_id', $grp_admin_id, PDO::PARAM_INT); 

           $set_status_to_accepted_for_grp_owner_stmt->execute(); 
          
            
              $delete_usr_from_prev_chat_room_query = "DELETE ru
FROM rooms_users ru
INNER JOIN chat_room cr
  ON ru.chat_room_id_fk=cr.chat_room_id
  INNER JOIN groups g
  ON g.chat_room_id_fk=cr.chat_room_id
  INNER JOIN semester s
  ON s.auto_inc_id=g.semester_id 
  WHERE
  s.active=1 AND ru.usr_id=:std_id";
        $delete_usr_from_prev_chat_room_stmt = $pdo->prepare($delete_usr_from_prev_chat_room_query);
        $delete_usr_from_prev_chat_room_stmt->bindParam(':std_id',$std_id,PDO::PARAM_STR);
        $delete_usr_from_prev_chat_room_stmt->execute(); 
                $row2 = $del_prev_request_and_accept_in_owner_grp_stmt1->rowCount();
                if ($del_prev_request_and_accept_in_owner_grp_stmt1) {
                    $accept_this_usr_in_owner_user_query = "
    UPDATE `group_members`
SET  
`status`=:status
WHERE 
`group_id`=:group_id AND 
`student_id`=:student_id
;";
                    $status                              = "accepted";
                    $accept_this_usr_in_owner_user_stmt  = $pdo->prepare($accept_this_usr_in_owner_user_query);
                    $accept_this_usr_in_owner_user_stmt->bindParam(':group_id', $grp_admin_id, PDO::PARAM_INT);
                    $accept_this_usr_in_owner_user_stmt->bindParam(':student_id', $std_id, PDO::PARAM_STR);
                    $accept_this_usr_in_owner_user_stmt->bindParam(':status', $status, PDO::PARAM_STR); 
                    $accept_this_usr_in_owner_user_stmt->execute(); 
                }
          //  }
            
		 
	/**/
 
	 
	  

			/**/
			$insert_users_room_query = "
										INSERT INTO `rooms_users` SET
										`usr_id`=".$pdo->quote($std_id).", `chat_room_id_fk` =:chat_room_id_fk;";
			$insert_users_room_stmt = $pdo->prepare($insert_users_room_query);
			$insert_users_room_stmt->bindParam(':chat_room_id_fk',$chat_room_id_fk,PDO::PARAM_INT);
			$insert_users_room_stmt->execute();
			
			
			
	/**/		
            $pdo->commit();
             
        }
        catch (Exception $e) {
            if (isset($pdo)) {
               
                $pdo->rollback();
                // return null;
                return false;
                $err = "ﻟﻢ ﺗﺘﻢ اﻟﻌﻤﻠﻴﺔ ﺑﻨﺠﺎﺡ";
            }
        } 
        //return $arr_row_count;
        return true;
    }
    public static function del_its_own_grp_and_all_request_and_accept_in_this_owner_grp($std_id, $owner_id, $new_grp)
    {
         
        $arr_row_count = null;
        global $pdo;
        try {

            $pdo->beginTransaction();
            //echo "std is " . $std_id;
                       $owner_grp_id    = 0;
            $std_grp_id      = "SELECT grp_id FROM groups WHERE owner=:owner1 and groups.semester_id IN (SELECT semester.auto_inc_id FROM semester WHERE semester.active=1);";
            $std_grp_id_stmt = $pdo->prepare($std_grp_id);
            $std_grp_id_stmt->bindParam(':owner1', $std_id, PDO::PARAM_STR);
            $std_grp_id_stmt->execute();
            $data = $std_grp_id_stmt->fetchAll();
            if ($data != null) {
                # code...
                $owner_grp_id = $data[0][0]; 
            }
               /**/
               $prev_chat_room_id_Own_std_grp=0;
$del_prev_request_and_accept_in_owner_grp_query = "SELECT chat_room_id  FROM chat_room cr INNER JOIN groups g ON g.chat_room_id_fk=cr.chat_room_id INNER JOIN semester s ON g.semester_id= s.auto_inc_id AND s.active=1 AND g.grp_id=:group_id;";
            $del_prev_request_and_accept_in_owner_grp_stmt  = $pdo->prepare($del_prev_request_and_accept_in_owner_grp_query); 
            $del_prev_request_and_accept_in_owner_grp_stmt->bindParam(':group_id', $owner_grp_id, PDO::PARAM_INT);

           $res1= $del_prev_request_and_accept_in_owner_grp_stmt->execute();
               $data = $del_prev_request_and_accept_in_owner_grp_stmt->fetchAll();
            if ($data != null) {
                # code...
                $prev_chat_room_id_Own_std_grp = $data[0][0]; 
            }
               /**/

            $del_prev_request_and_accept_in_owner_grp_query = "DELETE gm FROM group_members gm INNER JOIN groups g ON gm.group_id= g.grp_id INNER JOIN semester s ON g.semester_id= s.auto_inc_id AND s.active=1 AND gm.student_id=:student_id1 AND gm.group_id!=:group_id;";
            $del_prev_request_and_accept_in_owner_grp_stmt  = $pdo->prepare($del_prev_request_and_accept_in_owner_grp_query);
            $del_prev_request_and_accept_in_owner_grp_stmt->bindParam(':student_id1', $std_id, PDO::PARAM_STR);
            $del_prev_request_and_accept_in_owner_grp_stmt->bindParam(':group_id', $new_grp, PDO::PARAM_INT);

           $res1= $del_prev_request_and_accept_in_owner_grp_stmt->execute();
             $del_prev_request_and_accept_in_owner_grp_query1 = "DELETE FROM supervision WHERE group_id=:owner_grp_id1";
            if ($res1) {
             
            $del_prev_request_and_accept_in_owner_grp_stmt1  = $pdo->prepare($del_prev_request_and_accept_in_owner_grp_query1);
            $del_prev_request_and_accept_in_owner_grp_stmt1->bindParam(':owner_grp_id1', $owner_grp_id, PDO::PARAM_INT);
           $res2=  $del_prev_request_and_accept_in_owner_grp_stmt1->execute();
           
            if ($res2) {
              $del_prev_request_and_accept_in_owner_grp_query2 = "DELETE FROM examination WHERE groups_id=:owner_grp_id2";

            $del_prev_request_and_accept_in_owner_grp_stmt2  = $pdo->prepare($del_prev_request_and_accept_in_owner_grp_query2);
            $del_prev_request_and_accept_in_owner_grp_stmt2->bindParam(':owner_grp_id2', $owner_grp_id, PDO::PARAM_INT);
             $res3=$del_prev_request_and_accept_in_owner_grp_stmt2->execute();
              
            if ($res3) {
           $del_prev_request_and_accept_in_owner_grp_query3 = "DELETE FROM `idea_acceptance` WHERE group_id=:group_id";
            $del_prev_request_and_accept_in_owner_grp_stmt3  = $pdo->prepare($del_prev_request_and_accept_in_owner_grp_query3);
            $del_prev_request_and_accept_in_owner_grp_stmt3->bindParam(':group_id', $owner_grp_id, PDO::PARAM_INT);
            $res4= $del_prev_request_and_accept_in_owner_grp_stmt3->execute();
            


            /**/
            /**/
 $del_all_prev_std_chat_query = "DELETE ru FROM rooms_users ru INNER JOIN groups g ON ru.chat_room_id_fk= g.chat_room_id_fk INNER JOIN semester s ON g.semester_id= s.auto_inc_id AND s.active=1 AND ru.usr_id=".$pdo->quote($std_id);
               
              $del_all_prev_std_chat_stmt = $pdo->prepare($del_all_prev_std_chat_query);
               
              $del_all_prev_std_chat_stmt->execute();
           if ($res4) {
          
          $res5 =self::delete_grp_if_know_its_owner($std_id);

 $del_prev_request_and_accept_in_owner_grp_query = "DELETE  FROM chat_room  WHERE chat_room_id=:chat_room_id;";
            $del_prev_request_and_accept_in_owner_grp_stmt  = $pdo->prepare($del_prev_request_and_accept_in_owner_grp_query); 
            $del_prev_request_and_accept_in_owner_grp_stmt->bindParam(':chat_room_id', $prev_chat_room_id_Own_std_grp, PDO::PARAM_INT);

           $res1= $del_prev_request_and_accept_in_owner_grp_stmt->execute();
         /*
           if ($res5) {
       $res6=   self::add_new_grp_member( $owner_grp_id,$std_id,$owner_id);
     
            
           }
           */

           }
            }
            }
            // $arr_row_count = array($row1,$get_all_row_count);
            /**/
            /**/
			
              $update_member_status_of_group_of_owner_query = 
              "UPDATE  `group_members` SET

`status`='accepted' 
 WHERE `group_id`=:group_id AND 
`student_id`=:student_id";
             $update_member_status_of_group_of_owner_stmt = $pdo->prepare($update_member_status_of_group_of_owner_query );
             $update_member_status_of_group_of_owner_stmt->bindParam(':group_id',$new_grp,PDO::PARAM_INT);
             $update_member_status_of_group_of_owner_stmt->bindParam(':student_id',$std_id,PDO::PARAM_STR); 


              $update_member_status_of_group_of_owner_stmt->execute();
			/*
            $update_chat_room_left_state_query = "UPDATE rooms_users SET chat_room_left_state='left' where chat_room_left_state='exist' and  usr_id=".$pdo->quote($std_id);
			  $update_chat_room_left_state_stmt = $pdo->prepare($update_chat_room_left_state_query);
			  
			  $update_chat_room_left_state_stmt->bindParam(':chat_room_id_fk',$chat_room_id_fk,PDO::PARAM_INT);
			  $update_chat_room_left_state_stmt->execute();
			  */
			$get_chat_room_id_for_specific_grp_query = "SELECT `chat_room_id_fk` FROM `groups` WHERE `grp_id`=:grp_id;";
			$get_chat_room_id_for_specific_grp_stmt = $pdo->prepare($get_chat_room_id_for_specific_grp_query);
			$get_chat_room_id_for_specific_grp_stmt->bindParam(':grp_id',$new_grp,PDO::PARAM_INT);
			$get_chat_room_id_for_specific_grp_stmt->execute();
			$data = $get_chat_room_id_for_specific_grp_stmt->fetchAll();
			$chat_room_id_fk= $data[0]['chat_room_id_fk'];

			$update_left_state_for_last_usr_query = "INSERT INTO `rooms_users` SET 
			 `usr_id`=".$pdo->quote($std_id)."
			,
			 `chat_room_id_fk`=:chat_room_id_fk";
			 $update_left_state_for_last_usr_stmt = $pdo->prepare($update_left_state_for_last_usr_query );
			 $update_left_state_for_last_usr_stmt->bindParam(':chat_room_id_fk',$chat_room_id_fk,PDO::PARAM_INT);
			  $update_left_state_for_last_usr_stmt->execute();
              /**/
                
              /**/
			  $pdo->commit();
        
            }}
        catch (Exception $e) {
            echo $e->getMessage();
            if (isset($pdo)) {
                $pdo->rollback();
                return false;
                echo $e->getMessage();
                $err = "لم تتم العملية بنجاح لأنه هناك مشرف يشرف على هذه المجموعة فحتى يستنى حذفه ينبغي قبولك مع هذا المشرف أو الانضمام إلى مشرف آخر و حينما تنقبل سيتم قبولك";
                echo $err;
            }
        }
        return true;
    }
    public static function add_new_grp_member($grp_admin_id,$std_id, $owner_id){
global $pdo;
         $accept_this_usr_in_owner_user_query = "
    UPDATE `group_members`
SET   
`status`=:status, 
`new_member_added_by_whom`=:new_member_added_by_whom, 
`join_date`=NOW()
WHERE 
`group_id`=:group_id 
AND 
`student_id`=:student_id
;";
            $status                              = "accepted";
            $accept_this_usr_in_owner_user_stmt  = $pdo->prepare($accept_this_usr_in_owner_user_query);
            $accept_this_usr_in_owner_user_stmt->bindParam(':group_id', $grp_admin_id, PDO::PARAM_INT);
            $accept_this_usr_in_owner_user_stmt->bindParam(':student_id', $std_id, PDO::PARAM_STR);
            $accept_this_usr_in_owner_user_stmt->bindParam(':status', $status, PDO::PARAM_STR);
            $accept_this_usr_in_owner_user_stmt->bindParam(':new_member_added_by_whom', $owner_id, PDO::PARAM_STR);
          $res5=  $accept_this_usr_in_owner_user_stmt->execute();
            return $res5;
           
    }
    public static function delete_grp_if_know_its_owner($std_id){
        global $pdo;
$del_prev_request_and_accept_in_owner_grp_query4 = "DELETE FROM groups WHERE owner=:student_id AND semester_id in (SELECT `auto_inc_id`  FROM `semester` WHERE active=1)";
            $del_prev_request_and_accept_in_owner_grp_stmt4  = $pdo->prepare($del_prev_request_and_accept_in_owner_grp_query4);
            $del_prev_request_and_accept_in_owner_grp_stmt4->bindParam(':student_id', $std_id, PDO::PARAM_STR);
            $res4=$del_prev_request_and_accept_in_owner_grp_stmt4->execute();
            return $res4;
    }
    public static function accept_joining_into_this_grp($std_id, $grp_admin_id, $owner_id)
    {
        global $pdo;
        $accept_joining_into_this_grp_query = "INSERT INTO `group_members`
SET `group_id`=:group_id, 
`student_id`=:student_id,
`status`='accepted', 
`new_member_added_by_whom`=:new_member_added_by_whom, 
`join_date`=NOW() ;";
        $accept_joining_into_this_grp_stmt  = $pdo->prepare($accept_joining_into_this_grp_query);
        $accept_joining_into_this_grp_stmt->bindParam(':group_id', $grp_admin_id, PDO::PARAM_INT);
        $accept_joining_into_this_grp_stmt->bindParam(':student_id', $std_id, PDO::PARAM_STR);
        $accept_joining_into_this_grp_stmt->bindParam(':new_member_added_by_whom', $owner_id, PDO::PARAM_STR);
        $accept_joining_into_this_grp_stmt->execute();
        return $accept_joining_into_this_grp_stmt->rowCount();
    }
    public static function delete_all_requst_not_its_owner_grp_and_accept_this_request($std_id, $grp_admin_id, $owner_id)
    {
        global $pdo;
        try {
            $pdo->beginTransaction();
            $count_all_requst_query = "SELECT COUNT(*) counter FROM `group_members` WHERE student_id=:student_id;";
            $count_all_requst_stmt  = $pdo->prepare($count_all_requst_query);
            $count_all_requst_stmt->bindParam(':student_id', $std_id, PDO::PARAM_STR);
            $count_all_requst_stmt->execute();
            $all_requst_data         = $count_all_requst_stmt->fetchAll();
            $row1                    = $all_requst_data[0]['counter'];
			$get_group_id_for_fixed_std_query ="SELECT group_id FROM `group_members` WHERE student_id='accepted' and student_id=:student_id";
			$get_group_id_for_fixed_std_stmt = $pdo->prepare($get_group_id_for_fixed_std_query);
			$get_group_id_for_fixed_std_stmt->bindParam(':student_id',$std_id,PDO::PARAM_INT);
			$get_group_id_for_fixed_std_stmt->execute();
			$data = $get_group_id_for_fixed_std_stmt->fetchAll();
			$get_group_id_for_fixed_std = $data[0]['group_id'];
			
            $delete_all_requst_query = "DELETE FROM `group_members` WHERE student_id=:student_id;";
            $delete_all_requst_stmt  = $pdo->prepare($delete_all_requst_query);
            $delete_all_requst_stmt->bindParam(':student_id', $std_id, PDO::PARAM_STR);
            $delete_all_requst_stmt->execute();
            $row2                               = $delete_all_requst_stmt->rowCount();
            $row_inserted                       = self::accept_joining_into_this_grp($std_id, $grp_admin_id, $owner_id);
            $entire_row                         = $row1 + $row2 + $row_inserted;
            $compare_arr_btn_found_and_executed = array(
                $row1,
                $entire_row
            );
			$get_prev_chat_room_id_fk_query = "SELECT chat_room_id_fk FROM groups WHERE grp_id=:get_group_id_for_fixed_std";
			$get_prev_chat_room_id_fk_stmt = $pdo->prepare($get_prev_chat_room_id_fk_query);
			$get_prev_chat_room_id_fk_stmt->bindParam(':get_group_id_for_fixed_std',$get_group_id_for_fixed_std,PDO::PARAM_INT);
			$get_prev_chat_room_id_fk_stmt->execute();
			$data = $get_prev_chat_room_id_fk_stmt->fetchAll();
			$get_prev_chat_room_id_fk = $data[0]['chat_room_id_fk'];
			$get_admin_chat_room_id_fk_query = "SELECT chat_room_id_fk FROM groups WHERE owner=".$pdo->quote($owner_id);
			$get_admin_chat_room_id_fk_stmt = $pdo->query($get_admin_chat_room_id_fk_query );
			$get_admin_chat_room_id_fk_stmt->execute();
			$data = $get_admin_chat_room_id_fk_stmt->fetchAll();
			$get_admin_chat_room_id_fk = $data[0]['chat_room_id_fk'];
			$get_chat_room_left_state_query = "UPDATE `rooms_users` SET `chat_room_left_state`='left' WHERE `usr_id`=".$pdo->quote($owner_id)." AND
			`chat_room_id_fk`=:chat_room_id_fk;";
			$get_chat_room_left_state_stmt = $pdo->prepare($get_chat_room_left_state_query);
			$get_chat_room_left_state_stmt->bindParam(':chat_room_id_fk',$get_prev_chat_room_id_fk ,PDO::PARAM_INT);
			$get_chat_room_left_state_stmt->execute();
			
			$insert_user_into_specific_chat_room_query = "INSERT INTO `rooms_users` SET `usr_id`=".$pdo->quote($std_id).", `chat_room_id_fk`=:chat_room_id_fk;";
			$insert_user_into_specific_chat_room_stmt = $pdo->prepare($insert_user_into_specific_chat_room_query);
			$insert_user_into_specific_chat_room_stmt->bindParam(':chat_room_id_fk',$get_admin_chat_room_id_fk,PDO::PARAM_INT);
			$insert_user_into_specific_chat_room_stmt->execute();
			
			$pdo->commit();
            
        }
        catch (Exception $e) {
            if (isset($pdo)) {
                return null;
                $pdo->rollback();
               $err = "ﻟﻢ ﺗﺘﻢ اﻟﻌﻤﻠﻴﺔ ﺑﻨﺠﺎﺡ";
            }
        }
        return $compare_arr_btn_found_and_executed;
    }
    public static function return_grp_member_status($std_id, $grp_admin_id)
    {
        global $pdo;
        $status=null;
        $return_grp_member_status_query = "SELECT status FROM group_members WHERE student_id=:student_id AND group_id=:group_id";
        $return_grp_member_status_stmt  = $pdo->prepare($return_grp_member_status_query);
        $return_grp_member_status_stmt->bindParam(':student_id', $std_id, PDO::PARAM_STR);
        $return_grp_member_status_stmt->bindParam(':group_id', $grp_admin_id, PDO::PARAM_STR);
        $return_grp_member_status_stmt->execute();
        $return_grp_member_status_data = $return_grp_member_status_stmt->fetchAll();
if ($return_grp_member_status_data!=null) {
     $status                        = $return_grp_member_status_data[0]['status'];
}
       

        return $status;
    }
    public static function change_status_to_reject($std_id, $grp_admin_id)
    {
		
        global $pdo;
		 try {
            $pdo->beginTransaction();
            $status = "reject";
        $change_status_to_reject_query = "UPDATE group_members SET status=:status WHERE group_id=:group_id AND student_id=:student_id";
        $change_status_to_reject_stmt  = $pdo->prepare($change_status_to_reject_query);
        $change_status_to_reject_stmt->bindParam(':student_id', $std_id, PDO::PARAM_STR);
        $change_status_to_reject_stmt->bindParam(':status', $status, PDO::PARAM_STR);

        $change_status_to_reject_stmt->bindParam(':group_id', $grp_admin_id, PDO::PARAM_INT);
        $change_status_to_reject_stmt->execute();  

		$get_chat_room_id_fk_query ="SELECT chat_room_id_fk FROM groups WHERE grp_id=:grp_id";
		$get_chat_room_id_fk_stmt = $pdo->prepare($get_chat_room_id_fk_query);
		$get_chat_room_id_fk_stmt->bindParam(':grp_id',$grp_admin_id,PDO::PARAM_INT);
		$get_chat_room_id_fk_stmt->execute();
		$data = $get_chat_room_id_fk_stmt->fetchAll();
		$chat_room_id_fk=$data[0]['chat_room_id_fk'];

       
        $chat_room_id_fk = (int)str_replace(' ', '', $chat_room_id_fk);
		  // var_dump( $chat_room_id_fk);
         $update_room_users_query = "
         UPDATE 
          rooms_users  
         SET  
          chat_room_left_state =:chat_room_state 
         WHERE 
          usr_id =:std_id 
		and 
         chat_room_id_fk =:chat_room_id_fk;";
        $chat_room_state="left";
// var_dump($update_room_users_query);
		$update_room_users_stmt = $pdo->prepare($update_room_users_query);
        $update_room_users_stmt->bindParam(':chat_room_id_fk',$chat_room_id_fk,PDO::PARAM_INT);
        $update_room_users_stmt->bindParam(':std_id',$std_id,PDO::PARAM_STR);
        $update_room_users_stmt->bindParam(':chat_room_state',$chat_room_state,PDO::PARAM_STR);
        

        $update_room_users_stmt->execute();
         return true;
		 }
		 catch(PDOException $ex){
			 
		if (isset($pdo)) {
                return false;
                $pdo->rollback();
               $err = "ﻟﻢ ﺗﺘﻢ اﻟﻌﻤﻠﻴﺔ ﺑﻨﺠﺎﺡ";
            }	 
		 }
         return true;
    }
    public static function reject_accepted_request_in_group($std_id, $grp_admin_id){
global $pdo;
     try {
            $pdo->beginTransaction();

            /**/
            
              $delete_usr_from_prev_chat_room_query = "DELETE ru
FROM rooms_users ru
INNER JOIN chat_room cr
  ON ru.chat_room_id_fk=cr.chat_room_id
  INNER JOIN groups g
  ON g.chat_room_id_fk=cr.chat_room_id
  INNER JOIN semester s
  ON s.auto_inc_id=g.semester_id 
  WHERE
  s.active=1 AND ru.usr_id=:std_id";
        $delete_usr_from_prev_chat_room_stmt = $pdo->prepare($delete_usr_from_prev_chat_room_query);
        $delete_usr_from_prev_chat_room_stmt->bindParam(':std_id',$std_id,PDO::PARAM_STR);
        $delete_usr_from_prev_chat_room_stmt->execute(); 
        $change_status_to_reject_query = "UPDATE group_members SET status='reject' WHERE group_id=:group_id AND student_id=".$pdo->quote($std_id);
        $change_status_to_reject_stmt  = $pdo->prepare($change_status_to_reject_query); 
        $change_status_to_reject_stmt->bindParam(':group_id', $grp_admin_id, PDO::PARAM_INT);
        $change_status_to_reject_stmt->execute();

           
               $pdo->commit();    
        return true;
         }
         catch(PDOException $ex){
             echo $ex->getMessage();
        if (isset($pdo)) {
            $pdo->rollback();
                return false;
                
               $err = "ﻟﻢ ﺗﺘﻢ اﻟﻌﻤﻠﻴﺔ ﺑﻨﺠﺎﺡ";
            }    
         } 
        


    }
    public static function change_status_to_accept($std_id, $grp_admin_id)
    {
         
        global $pdo;
	 try {
            $pdo->beginTransaction();

            /**/
            
              $delete_usr_from_prev_chat_room_query = "DELETE ru
FROM rooms_users ru
INNER JOIN chat_room cr
  ON ru.chat_room_id_fk=cr.chat_room_id
  INNER JOIN groups g
  ON g.chat_room_id_fk=cr.chat_room_id
  INNER JOIN semester s
  ON s.auto_inc_id=g.semester_id 
  WHERE
  s.active=1 AND ru.usr_id=:std_id";
        $delete_usr_from_prev_chat_room_stmt = $pdo->prepare($delete_usr_from_prev_chat_room_query);
        $delete_usr_from_prev_chat_room_stmt->bindParam(':std_id',$std_id,PDO::PARAM_STR);
        $delete_usr_from_prev_chat_room_stmt->execute(); 
        $change_status_to_reject_query = "UPDATE group_members SET status='accepted' WHERE group_id=:group_id AND student_id=".$pdo->quote($std_id);
        $change_status_to_reject_stmt  = $pdo->prepare($change_status_to_reject_query); 
        $change_status_to_reject_stmt->bindParam(':group_id', $grp_admin_id, PDO::PARAM_INT);
        $change_status_to_reject_stmt->execute();

            $set_status_to_accepted_for_grp_owner_query = "DELETE gm FROM group_members gm INNER JOIN groups g ON gm.group_id= g.grp_id INNER JOIN semester s ON g.semester_id= s.auto_inc_id AND s.active=1 AND gm.student_id=:student_id AND gm.group_id!=:group_id";
            $set_status_to_accepted_for_grp_owner_stmt  = $pdo->prepare($set_status_to_accepted_for_grp_owner_query);
             $set_status_to_accepted_for_grp_owner_stmt->bindParam(':student_id', $std_id, PDO::PARAM_STR); 
             $set_status_to_accepted_for_grp_owner_stmt->bindParam(':group_id', $grp_admin_id, PDO::PARAM_INT); 

           $set_status_to_accepted_for_grp_owner_stmt->execute(); 
/**/ /*
    	$get_room_id_fk_for_specific_grp_query = "UPDATE `rooms_users` SET `chat_room_left_state`='left'
		WHERE `usr_id`=".$pdo->quote($std_id)." AND `chat_room_left_state`='exist' ;";
		$get_room_id_fk_for_specific_grp_stmt = $pdo->prepare($get_room_id_fk_for_specific_grp_query);
		$get_room_id_fk_for_specific_grp_stmt->execute();
        var_dump("5555555 ".$get_room_id_fk_for_specific_grp_stmt->rowCount());
        */
        /**/
               
         
		 $get_chat_room_id_query = "SELECT chat_room_id_fk from groups where grp_id=:grp_id";
        $get_chat_room_id_stmt = $pdo->prepare($get_chat_room_id_query);
        $get_chat_room_id_stmt->bindParam(':grp_id',$grp_admin_id,PDO::PARAM_INT);
        $get_chat_room_id_stmt->execute();
        $data = $get_chat_room_id_stmt->fetchAll();
        $get_chat_room_id = $data[0]['chat_room_id_fk'];
         $get_room_id_fk_for_specific_grp_query = "INSERT INTO`rooms_users` SET `usr_id`=".$pdo->quote($std_id)." , chat_room_id_fk=:chat_room_id_fk ;";
		$get_room_id_fk_for_specific_grp_stmt = $pdo->prepare($get_room_id_fk_for_specific_grp_query);
		$get_room_id_fk_for_specific_grp_stmt->bindParam(':chat_room_id_fk',$get_chat_room_id,PDO::PARAM_INT);
		$get_room_id_fk_for_specific_grp_stmt->execute();
           
               $pdo->commit();    
		return true;
		 }
		 catch(PDOException $ex){
			 echo $ex->getMessage();
		if (isset($pdo)) {
			$pdo->rollback();
                return false;
                
               $err = "ﻟﻢ ﺗﺘﻢ اﻟﻌﻤﻠﻴﺔ ﺑﻨﺠﺎﺡ";
            }	 
		 } 
		 }
    public static function change_status_to_accept_for_member_dont_have_any_grp($std_id, $grp_admin_id)
    {
        global $pdo;
        try {
            $pdo->beginTransaction();
            $get_all_grp_request_count_query = "SELECT count(*) counter FROM group_members WHERE student_id=:student_id";
            $get_all_grp_request_count_stmt  = $pdo->prepare($get_all_grp_request_count_query);
            $get_all_grp_request_count_stmt->bindParam(':student_id', $std_id, PDO::PARAM_STR);
            $get_all_grp_request_count_stmt->execute();
            $data                                           = $get_all_grp_request_count_stmt->fetchAll();
            $row1                                           = $data[0]['counter'];
            $del_prev_request_and_accept_in_owner_grp_query = "DELETE FROM group_members WHERE student_id=:student_id";
            $del_prev_request_and_accept_in_owner_grp_stmt  = $pdo->prepare($del_prev_request_and_accept_in_owner_grp_query);
            $del_prev_request_and_accept_in_owner_grp_stmt->bindParam(':student_id', $std_id, PDO::PARAM_STR);
            $del_prev_request_and_accept_in_owner_grp_stmt->execute();
            $change_status_to_accept_for_member_dont_have_any_grp_query =
			"
			UPDATE group_members SET status='accepted' WHERE student_id=:student_id 
			AND group_id=:group_id";
            $change_status_to_accept_for_member_dont_have_any_grp_stmt  = $pdo->prepare($change_status_to_accept_for_member_dont_have_any_grp_query);
            $change_status_to_accept_for_member_dont_have_any_grp_stmt->bindParam(':student_id', $std_id, PDO::PARAM_STR);
            $change_status_to_accept_for_member_dont_have_any_grp_stmt->bindParam(':group_id', $grp_admin_id, PDO::PARAM_INT);
            $change_status_to_accept_for_member_dont_have_any_grp_stmt->execute();
            $row2            = $change_status_to_accept_for_member_dont_have_any_grp_stmt->rowCount();
            $rowCount_entire = $row1 + $row2;
            $arr_row_count   = array(
                $rowCount_entire,
                $row1
            );
			$get_chat_room_id_fk_for_specific_grp_query = "SELECT chat_room_id_fk FROM groups WHERE grp_id=:grp_id";
			$get_chat_room_id_fk_for_specific_grp_stmt = $pdo->prepare($get_chat_room_id_fk_for_specific_grp_query);
			$get_chat_room_id_fk_for_specific_grp_stmt->bindParam(':grp_id',$grp_admin_id,PDO::PARAM_INT);
			$get_chat_room_id_fk_for_specific_grp_stmt->execute();
			$data = $get_chat_room_id_fk_for_specific_grp_stmt->fetchAll();
			$get_chat_room_id_fk = $data[0]['chat_room_id_fk'];
			$chat_room_left_state_query = "UPDATE `rooms_users` SET `chat_room_left_state`='left' 
			WHERE `usr_id`=".$pdo->quote($std_id)." AND `chat_room_id_fk`=:chat_room_id_fk;";
			$chat_room_left_state_stmt = $pdo->prepare($chat_room_left_state_query);
			$chat_room_left_state_stmt->bindParam(':chat_room_id_fk',$get_chat_room_id_fk ,PDO::PARAM_INT);
			$chat_room_left_state_stmt->execute();
			
			
			
            $pdo->commit();
        }
        catch (Exception $e) {
            if (isset($pdo)) {
                $pdo->rollback();
               $err = "ﻟﻢ ﺗﺘﻢ اﻟﻌﻤﻠﻴﺔ ﺑﻨﺠﺎﺡ";
            }
        }
        return $arr_row_count;
    }
    public static function check_if_admin_grp_in_member_no_bounded($grp_adminn_id, $maximum_no_of_grp_mem)
    {
        global $pdo;
        $check_if_admin_grp_in_member_no_bounded_query = "SELECT COUNT(*) counter FROM group_members WHERE group_id=:group_id";
        $check_if_admin_grp_in_member_no_bounded_stmt  = $pdo->prepare($check_if_admin_grp_in_member_no_bounded_query);
        $check_if_admin_grp_in_member_no_bounded_stmt->bindParam(':group_id', $grp_adminn_id, PDO::PARAM_INT);
        $check_if_admin_grp_in_member_no_bounded_stmt->execute();
        $check_no_of_grp_member         = $check_if_admin_grp_in_member_no_bounded_stmt->fetchAll();
        $check_no_of_grp_member_counter = $check_no_of_grp_member[0]['counter'];
        if ($check_no_of_grp_member_counter > 0 && $check_no_of_grp_member_counter <= $maximum_no_of_grp_mem) {
            return true;
        }
        return false;
    }
    
    /*send_grp_to_supervisor.php file*/
     public static function check_if_this_usr_admin_of_this_grp($user_id)
    {
        global $pdo;
        $check_if_this_usr_admin_of_this_grp_query = "SELECT grp_id  FROM groups WHERE owner=:owner";
        $check_if_this_usr_admin_of_this_grp_stmt  = $pdo->prepare($check_if_this_usr_admin_of_this_grp_query);
        $check_if_this_usr_admin_of_this_grp_stmt->bindParam(':owner', $user_id, PDO::PARAM_STR);
        $check_if_this_usr_admin_of_this_grp_stmt->execute();
        $check_if_this_usr_admin_data = $check_if_this_usr_admin_of_this_grp_stmt->fetchAll();
        return $check_if_this_usr_admin_data;
    }
 
    public static function get_all_supervisor_counter_for_this_semester($grp_id)
    {
        global $pdo;
        $row                                        = 0;
        $get_all_supervisor_for_this_semester_query = "SELECT count(*) counter FROM `groups`,`supervision` WHERE  
  (group_id IN 
  (SELECT grp_id FROM groups WHERE grp_id=:grp_id) )
   AND grp_id=group_id 
   AND semester_id = (SELECT semester_id FROM semester WHERE active=1) ;";
        $get_all_supervisor_for_this_semester_stmt  = $pdo->prepare($get_all_supervisor_for_this_semester_query);
        $get_all_supervisor_for_this_semester_stmt->bindParam(':grp_id', $grp_id, PDO::PARAM_INT);
        $get_all_supervisor_for_this_semester_stmt->execute();
        $data = $get_all_supervisor_for_this_semester_stmt->fetchAll();
        if ($data != null) {
            $row = $data[0]['counter'];
        }
        return $row;
    }
    public static function get_sub_regular_teacher_status($start, $rec_limit)
    {
        global $pdo;
        $get_all_regular_teacher_status_query = "SELECT * FROM users WHERE  
users.role=2 and users.status='regular' LIMIT :start,:rec_limit;";
        $get_all_regular_teacher_status_stmt  = $pdo->prepare($get_all_regular_teacher_status_query);
        $get_all_regular_teacher_status_stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $get_all_regular_teacher_status_stmt->bindParam(':rec_limit', $rec_limit, PDO::PARAM_INT);
        $get_all_regular_teacher_status_stmt->execute();
        return $get_all_regular_teacher_status_stmt->fetchAll();
    }
	 public static function get_all_regular_teachers_status_except_sup_login($teacher_id)
    {
        global $pdo;
        $get_all_regular_teacher_status_query = "SELECT usr_id,concat(fname,' ',lname) as name FROM users WHERE  
												users.role=2 and users.status='regular' AND usr_id!=".$pdo->quote($teacher_id).";";
        $get_all_regular_teacher_status_stmt  = $pdo->prepare($get_all_regular_teacher_status_query); 
        $get_all_regular_teacher_status_stmt->execute();
        return $get_all_regular_teacher_status_stmt->fetchAll();
    }
	
    public static function get_all_regular_teacher_status()
    {
        global $pdo;
        $row                                  = 0;
        $get_all_regular_teacher_status_query = "SELECT COUNT(*) counter FROM users WHERE  
users.role=2 and users.status='regular';";
        $get_all_regular_teacher_status_stmt  = $pdo->query($get_all_regular_teacher_status_query);
        $get_all_regular_teacher_status_stmt->execute();
        $data = $get_all_regular_teacher_status_stmt->fetchAll();
        $row  = $data[0]['counter'];
        return $row;
    }
    public static function get_sub_supervisor_counter_for_this_semester($grp_id, $start, $rec_limit)
    {
        global $pdo;
        $row                                        = 0;
        $get_all_supervisor_for_this_semester_query = "SELECT * FROM `groups`,`supervision`,`users`,`teacher` WHERE  
  groups.supervisor_id=teacher.id
    AND
    supervision.teacher_id=teacher.id
    AND 
  (group_id IN 
  (SELECT grp_id FROM groups WHERE grp_id=:grp_id) )
   AND grp_id=group_id 
   AND semester_id = (SELECT semester_id FROM semester WHERE active=1) LIMIT :start,:rec_limit ;";
        $get_all_supervisor_for_this_semester_stmt  = $pdo->prepare($get_all_supervisor_for_this_semester_query);
        $get_all_supervisor_for_this_semester_stmt->bindParam(':grp_id', $grp_id, PDO::PARAM_INT);
        $get_all_supervisor_for_this_semester_stmt->execute();
        $data = $get_all_supervisor_for_this_semester_stmt->fetchAll();
        if ($data != null) {
            $row = $data[0]['counter'];
        }
        return $row;
    }
    public static function get_supervisor_status_for_this_grp($grp_id, $supervisor_id)
    {
        global $pdo;
        $sup_status                               = "";
        $get_supervisor_status_for_this_grp_query = "SELECT `sup_status` FROM `supervision` WHERE `teacher_id`=:teacher_id AND `group_id`=:group_id;";
        $get_supervisor_status_for_this_grp_stmt  = $pdo->prepare($get_supervisor_status_for_this_grp_query);
        $get_supervisor_status_for_this_grp_stmt->bindParam(':teacher_id', $supervisor_id, PDO::PARAM_STR);
        $get_supervisor_status_for_this_grp_stmt->bindParam(':group_id', $grp_id, PDO::PARAM_INT);
        $get_supervisor_status_for_this_grp_stmt->execute();
        $data = $get_supervisor_status_for_this_grp_stmt->fetchAll();
        if ($data != null) {
            # code...
            $sup_status = $data[0]['sup_status'];
        }
        return $sup_status;
    }
    public static function get_no_of_supervisor_of_these_grp($grp_id)
    {
        global $pdo;
        $row_count                                = 0;
        $get_no_of_super_visor_of_these_grp_query = "SELECT COUNT(*) counter FROM `supervision` WHERE `sup_status`='accepted'   AND `group_id`=:group_id ;";
        $get_no_of_super_visor_of_these_grp_stmt  = $pdo->prepare($get_no_of_super_visor_of_these_grp_query);
        $get_no_of_super_visor_of_these_grp_stmt->bindParam(':group_id', $grp_id, PDO::PARAM_INT);
        $get_no_of_super_visor_of_these_grp_stmt->execute();
        $data      = $get_no_of_super_visor_of_these_grp_stmt->fetchAll();
        $row_count = $data[0]['counter'];
        return $row_count;
    }
    public static function add_new_pending_request_into_supervision_tbl($grp_id, $supervisor_id)
    {
        global $pdo;
        $row = 0;
        try {
            $sup_status                                         = "pending";
            $add_new_pending_request_into_supervision_tbl_query = "
 INSERT INTO `supervision`
SET `teacher_id`=:teacher_id, 
`group_id`=:group_id,
`sup_status`=:sup_status;";
            $add_new_pending_request_into_supervision_tbl_stmt  = $pdo->prepare($add_new_pending_request_into_supervision_tbl_query);
            $add_new_pending_request_into_supervision_tbl_stmt->bindParam(':group_id', $grp_id, PDO::PARAM_INT);
            $add_new_pending_request_into_supervision_tbl_stmt->bindParam(':teacher_id', $supervisor_id, PDO::PARAM_STR);
            $add_new_pending_request_into_supervision_tbl_stmt->bindParam(':sup_status', $sup_status, PDO::PARAM_STR);
            $add_new_pending_request_into_supervision_tbl_stmt->execute();
            $row = $add_new_pending_request_into_supervision_tbl_stmt->rowCount();
            return $row;
        }
        catch (Exception $e) {
            echo $e->getMessage();
            echo "ﻉﻮﺟﺮﻟا ﺭﺯ ﻰﻠﻋ ﻂﻐﺿا ﻉﻮﻨﻤﻣ اﺬﻫ ﻭ ﻦﻴﺗﺮﻣ ﺐﻄﻟا ﺖﺒﻠﻃ ﻚﻧﺄﻜﻓ ﻖﺑﺎﺴﻟا ﻚﺒﻠﻃ ﺎﻬﺑ ﻭ ﺔﺤﻔﺼﻟا ﻞﻴﻤﺤﺘﺑ ﺖﻤﻗ ﻚﻨﻜﻟ ﺬﻔﻨﺗ ﻖﺑﺎﺴﻟا ﻚﺒﻠﻃ";
        }
    }
    public static function cancel_pending_joinig_request_in_to_this_supervisor($grp_id, $supervisor_id)
    {
        global $pdo;
        $row                                 = 0;
        $cancel_pending_joinig_request_query = "DELETE FROM supervision WHERE `teacher_id`=:teacher_id AND  
`group_id`=:group_id ";
        $cancel_pending_joinig_request_stmt  = $pdo->prepare($cancel_pending_joinig_request_query);
        $cancel_pending_joinig_request_stmt->bindParam(':group_id', $grp_id, PDO::PARAM_INT);
        $cancel_pending_joinig_request_stmt->bindParam(':teacher_id', $supervisor_id, PDO::PARAM_STR);
        $cancel_pending_joinig_request_stmt->execute();
        $row = $cancel_pending_joinig_request_stmt->rowCount();
        return $row;
    }
    public static function update_reject_to_pending_request($grp_id, $supervisor_id)
    {
        global $pdo;
        $row                                    = 0;
        $update_reject_to_pending_request_query = "UPDATE supervision SET sup_status='pending' WHERE `teacher_id`=:teacher_id AND  
`group_id`=:group_id ";
        $update_reject_to_pending_request_stmt  = $pdo->prepare($update_reject_to_pending_request_query);
        $update_reject_to_pending_request_stmt->bindParam(':group_id', $grp_id, PDO::PARAM_INT);
        $update_reject_to_pending_request_stmt->bindParam(':teacher_id', $supervisor_id, PDO::PARAM_STR);
        $update_reject_to_pending_request_stmt->execute();
        $row = $update_reject_to_pending_request_stmt->rowCount();
        return $row;
    }
    /*follow_up_grp_request.php*/
    public static function get_sub_member_send_request_to_this_supervisor($supervisor_id, $start, $rec_limit)
    {
        global $pdo;
		 $get_all_member_send_request_query ="
SELECT * FROM `supervision` ,groups  
 WHERE `supervision`.`teacher_id`=".$pdo->quote($supervisor_id)." AND groups.semester_id in (SELECT auto_inc_id from semester where active=1)
  AND groups.grp_id=supervision.group_id LIMIT  :start,:rec_limit";
      
        $get_all_member_send_request_stmt  = $pdo->prepare($get_all_member_send_request_query); 
        $get_all_member_send_request_stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $get_all_member_send_request_stmt->bindParam(':rec_limit', $rec_limit, PDO::PARAM_INT);
        $get_all_member_send_request_stmt->execute();
        $data = $get_all_member_send_request_stmt->fetchAll();
        return $data;
    }
	  
    public static function get_count_member_send_request_to_this_supervisor($supervisor_id)
    {
        global $pdo;
        $row                               = 0;
        $get_all_member_send_request_query = "
SELECT COUNT(*) counter FROM `supervision` ,groups  
 WHERE `supervision`.`teacher_id`=".$pdo->quote($supervisor_id)." AND groups.semester_id in (SELECT auto_inc_id from semester where active=1)
  AND groups.grp_id=supervision.group_id ;
;";
        $get_all_member_send_request_stmt  = $pdo->prepare($get_all_member_send_request_query);
        $get_all_member_send_request_stmt->bindParam(':supervisor_id', $supervisor_id, PDO::PARAM_STR);
        $get_all_member_send_request_stmt->execute();
        $data = $get_all_member_send_request_stmt->fetchAll();
        if ($data != null) {
            $row = $data[0]['counter'];
        }
        return $row;
    }
    public static function count_of_all_grp_supervision_request($group_id)
    {
        global $pdo;
        $row                                        = 0;
        $count_of_all_grp_supervision_request_query = "SELECT COUNT(*) counter FROM `supervision` WHERE  `group_id`=:group_id;";
        $count_of_all_grp_supervision_request_stmt  = $pdo->prepare($count_of_all_grp_supervision_request_query);
        $count_of_all_grp_supervision_request_stmt->bindParam(':group_id', $group_id, PDO::PARAM_INT);
        $count_of_all_grp_supervision_request_stmt->execute();
        $data = $count_of_all_grp_supervision_request_stmt->fetchAll();
        $row  = $data[0]['counter'];
        return $row;
    }
    public static function delete_all_request_if_this_request_last_request_allowed_then_add_accepted_row($group_id, $supervisor_id)
    {
        global $pdo;
        $count_of_all_grp_supervision_request = 0;
        $entire                               = 0;
        $inserted_count                       = 0;
        $sup_status                           = "accepted";
        try {
            $pdo->beginTransaction();
            $count_of_all_grp_supervision_request                                                = self::count_of_all_grp_supervision_request($group_id);
            $delete_all_request_if_this_request_last_request_allowed_then_add_accepted_row_query = "DELETE FROM `supervision` WHERE  `group_id`=:group_id and sup_status!='accepted';";
            $delete_all_request_if_this_request_last_request_allowed_then_add_accepted_row_stmt  = $pdo->prepare($delete_all_request_if_this_request_last_request_allowed_then_add_accepted_row_query);
            $delete_all_request_if_this_request_last_request_allowed_then_add_accepted_row_stmt->bindParam(':group_id', $group_id, PDO::PARAM_INT);
            $delete_all_request_if_this_request_last_request_allowed_then_add_accepted_row_stmt->execute();
            $INSERT_ACCEPTED_ROW_QUERY = "INSERT INTO supervision 
SET `teacher_id`=:teacher_id, `group_id`=:group_id, `sup_status`=:sup_status
";
            $INSERT_ACCEPTED_ROW_STMT  = $pdo->prepare($INSERT_ACCEPTED_ROW_QUERY);
            $INSERT_ACCEPTED_ROW_STMT->bindParam(':teacher_id', $supervisor_id, PDO::PARAM_STR);
            $INSERT_ACCEPTED_ROW_STMT->bindParam(':group_id', $group_id, PDO::PARAM_INT);
            $INSERT_ACCEPTED_ROW_STMT->bindParam(':sup_status', $sup_status, PDO::PARAM_STR);
            $INSERT_ACCEPTED_ROW_STMT->execute();
            $inserted_count = $INSERT_ACCEPTED_ROW_STMT->rowCount();
            $entire         = $count_of_all_grp_supervision_request + $inserted_count;
            $arr            = array(
                $count_of_all_grp_supervision_request,
                $entire
            );
			$get_chat_room_id_fk_query = "SELECT `chat_room_id_fk` FROM `groups` WHERE  groups.grp_id=:grp_id";
			$get_chat_room_id_fk_stmt = $pdo->prepare($get_chat_room_id_fk_query);
			$get_chat_room_id_fk_stmt->bindParam(':grp_id',$group_id,PDO::PARAM_INT);
			$get_chat_room_id_fk_stmt->execute();
			$data = $get_chat_room_id_fk_stmt->fetchAll();
			$chat_room_id_fk = $data[0]['chat_room_id_fk']; 
			$insert_users_room_query = "INSERT INTO `rooms_users` SET `usr_id`=".$pdo->quote($supervisor_id).", `chat_room_id_fk`=:chat_room_id_fk;";
			$insert_users_room_stmt = $pdo->prepare($insert_users_room_query);
			$insert_users_room_stmt->bindParam(':chat_room_id_fk',$chat_room_id_fk,PDO::PARAM_INT);
			$insert_users_room_stmt->execute();
            $pdo->commit();
            
        }
        catch (Exception $e) {
            if (isset($pdo)) {
                $pdo->rollback();return false;
                
               $err = "ﻟﻢ ﺗﺘﻢ اﻟﻌﻤﻠﻴﺔ ﺑﻨﺠﺎﺡ";
            }
        }
        return true;
    }
    public static function update_grp_sup_request_status_to_accept($group_id, $supervisor_id)
    {
        global $pdo;
        $row                                           = 0;
        $update_grp_sup_request_status_to_accept_query = "
  UPDATE `supervision`
SET `sup_status`='accepted' WHERE `teacher_id`=:teacher_id AND
`group_id`=:group_id;
 
  ";
        $update_grp_sup_request_status_to_accept_stmt  = $pdo->prepare($update_grp_sup_request_status_to_accept_query);
        $update_grp_sup_request_status_to_accept_stmt->bindParam(':group_id', $group_id, PDO::PARAM_INT);
        $update_grp_sup_request_status_to_accept_stmt->bindParam(':teacher_id', $supervisor_id, PDO::PARAM_STR);
        $update_grp_sup_request_status_to_accept_stmt->execute();
        $row = $update_grp_sup_request_status_to_accept_stmt->rowCount();
        return $row;
    }
    public static function update_grp_sup_request_status_to_reject($group_id, $supervisor_id)
    {
        global $pdo;
        $row                                           = 0;
        $update_grp_sup_request_status_to_accept_query = "
  UPDATE `supervision`
SET `sup_status`='reject' WHERE `teacher_id`=:teacher_id AND
`group_id`=:group_id;
 
  ";
        $update_grp_sup_request_status_to_accept_stmt  = $pdo->prepare($update_grp_sup_request_status_to_accept_query);
        $update_grp_sup_request_status_to_accept_stmt->bindParam(':group_id', $group_id, PDO::PARAM_INT);
        $update_grp_sup_request_status_to_accept_stmt->bindParam(':teacher_id', $supervisor_id, PDO::PARAM_STR);
        $update_grp_sup_request_status_to_accept_stmt->execute();
		$get_chat_room_id_fk_query = "SELECT  `chat_room_id_fk` FROM `groups` WHERE `grp_id`=:grp_id;";
		$get_chat_room_id_fk_stmt = $pdo->prepare($get_chat_room_id_fk_query);
		$get_chat_room_id_fk_stmt->bindParam(':grp_id',$group_id,PDO::PARAM_INT);
		$get_chat_room_id_fk_stmt->execute();
		$data = $get_chat_room_id_fk_stmt->fetchAll();
		$chat_room_id_fk = $data[0]['chat_room_id_fk'];
		$update_supervisor_sttaus_query = "UPDATE `rooms_users` SET  `chat_room_left_state`='left' WHERE `usr_id`=".$pdo->quote($supervisor_id)." and
		`chat_room_id_fk`=:chat_room_id_fk;";
		$update_supervisor_sttaus_stmt = $pdo->prepare($update_supervisor_sttaus_query);
		$update_supervisor_sttaus_stmt->bindParam(':chat_room_id_fk',$chat_room_id_fk,PDO::PARAM_INT);
		$update_supervisor_sttaus_stmt->execute();
        $row = $update_grp_sup_request_status_to_accept_stmt->rowCount();
        return $row;
    }
  
    /* send_project_idea_into_my_sup.php */
    public static function get_idea_for_grp($grp_id){
global $pdo;
$get_idea_for_grp_query = "SELECT ideas_of_project.idea_name FROM `idea_acceptance`,ideas_of_project WHERE idea_acceptance.group_id=:group_id AND idea_acceptance.idea_id=ideas_of_project.id AND idea_status='accepted';";
$get_idea_for_grp_stmt = $pdo->prepare($get_idea_for_grp_query);
$get_idea_for_grp_stmt->bindParam(':group_id',$grp_id,PDO::PARAM_INT);
$get_idea_for_grp_stmt->execute();
$data = $get_idea_for_grp_stmt->fetchAll();
$idea_name="";
if ($data!=null) {
    # code...
    $idea_name = $data[0]['idea_name'];
}
return $idea_name;
    }
    public static function add_new_idea_into_sup($idea_name,$description,$usr_login_group_id,$usr_login_id){
       global $pdo;
        /*
        $users_tbl_data_filed = array($user_id  ,$user_fname,$user_lname,$user_type,$u_pwd);
        */
        $idea_status="pending";
        $proposer="";
        $proposer= $usr_login_id;
        try {

            // First of all, let's begin a transaction
            $pdo->beginTransaction();
            $insert_new_projects_query = "INSERT INTO `ideas_of_project`
                                            SET `idea_name`=:idea_name, 
                                            `description`=:description; ";
            $insert_new_projects_stmt  = $pdo->prepare($insert_new_projects_query);
            $insert_new_projects_stmt->bindParam(':idea_name', $idea_name, PDO::PARAM_STR); 
            $insert_new_projects_stmt->bindParam(':description', $description, PDO::PARAM_STR); 

            $insert_new_projects_stmt->execute(); 
            $last_idea_id = $pdo->lastInsertId();
             
            $insert_new_projects_status_query = "INSERT INTO `idea_acceptance`
                                                SET 
                                                `group_id`=:group_id, 
                                                `idea_id`=:idea_id, 
                                                `idea_status`=:idea_status,
                                                `proposer`=:proposer, 
                                                `date_of_proposal`=NOW();";
            $insert_new_projects_status_stmt  = $pdo->prepare($insert_new_projects_status_query);
            $insert_new_projects_status_stmt->bindParam(':group_id', $usr_login_group_id, PDO::PARAM_INT);
            $insert_new_projects_status_stmt->bindParam(':idea_id', $last_idea_id, PDO::PARAM_INT);
            $insert_new_projects_status_stmt->bindParam(':idea_status', $idea_status, PDO::PARAM_STR);
            $insert_new_projects_status_stmt->bindParam(':proposer', $proposer, PDO::PARAM_STR);

            $insert_new_projects_status_stmt->execute(); 
            // If we arrive here, it means that no exception was thrown
            // i.e. no query has failed, and we can commit the transaction
            $pdo->commit();
             
        }
        catch (Exception $e) {
            echo $e->getMessage();
            // An exception has been thrown
            // We must rollback the transaction
            if (isset($pdo)) {
                $pdo->rollback();
                 return false;
               $send_idea_err = "ﻫﺬا اﻟﻤﺴﺘﺨﺪﻡ ﻣﻮﺟﻮﺩ ﻳﺮﺟﻰ اﺿﺎﻓﺔ ﻏﻴﺮﻩ";
            }
        }
        return true;
     
    }
    public static function del_idea_from_sup($idea_id,$usr_login_group_id){
         global $pdo;
        /*
        $users_tbl_data_filed = array($user_id  ,$user_fname,$user_lname,$user_type,$u_pwd);
        */
        $idea_status="pending";
      /*
        $proposer="";
        $proposer= $usr_login_id;
        */
        try {

            // First of all, let's begin a transaction
            $pdo->beginTransaction();
            $get_idea_status_for_specific_idea_query = "SELECT `idea_status`  FROM `idea_acceptance` WHERE  `group_id`=:group_id AND `idea_id`=:idea_id;";
             $get_idea_status_for_specific_idea_stmt = $pdo->prepare($get_idea_status_for_specific_idea_query);
             $get_idea_status_for_specific_idea_stmt->bindParam(':idea_id',$idea_id,PDO::PARAM_INT);
             $get_idea_status_for_specific_idea_stmt->bindParam(':group_id',$usr_login_group_id,PDO::PARAM_INT);
             $get_idea_status_for_specific_idea_stmt->execute();
             $data = $get_idea_status_for_specific_idea_stmt->fetchAll(); 
                $idea_status=null;
                if ($data!=null) {
                    # code...

                $idea_status = $data[0]['idea_status'] ;
                }
            
            if ($idea_status!='accepted') {
                # code...
                /**/
   
            $insert_new_projects_status_query = "DELETE FROM `idea_acceptance`
                                                WHERE
                                                `group_id`=:group_id AND
                                                `idea_id`=:idea_id ;";
            $insert_new_projects_status_stmt  = $pdo->prepare($insert_new_projects_status_query);
            $insert_new_projects_status_stmt->bindParam(':group_id', $usr_login_group_id, PDO::PARAM_INT);
            $insert_new_projects_status_stmt->bindParam(':idea_id', $idea_id, PDO::PARAM_INT); 
            $insert_new_projects_status_stmt->execute(); 
           
            $insert_new_projects_query = "DELETE FROM `ideas_of_project` WHERE  `id`=:id ";
            $insert_new_projects_stmt  = $pdo->prepare($insert_new_projects_query);
            $insert_new_projects_stmt->bindParam(':id', $idea_id, PDO::PARAM_INT);   

            $insert_new_projects_stmt->execute();  

                /**/
            }
            else{
                throw new Exception("لا يمكنك حذف فكرة مقبولة من المشرف إلآ إذا تم رفضها من مشرفك مرة أخرى ", 1);
                
            } // If we arrive here, it means that no exception was thrown
            // i.e. no query has failed, and we can commit the transaction
            $pdo->commit();
             
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            // An exception has been thrown
            // We must rollback the transaction
            if (isset($pdo)) {
                $pdo->rollback();
                 return false;
               $send_idea_err = "هذه الفكرة معمول بها لا يمكن حذفها";
               echo $send_idea_err;
            }
        }
        return true;
     

    }
    public static function get_proj_idea_for_specific_std_for_specific_grp($grp_id,$std_id_login){
        global $pdo;
       $get_proj_idea_for_specific_std_for_specific_grp_query= "SELECT `id`, `idea_name`, `description`,idea_status FROM `ideas_of_project` , `idea_acceptance` WHERE idea_id=id and group_id=:group_id AND proposer=:std_id_login;";
       $get_proj_idea_for_specific_std_for_specific_grp_stmt = $pdo->prepare($get_proj_idea_for_specific_std_for_specific_grp_query);
       $get_proj_idea_for_specific_std_for_specific_grp_stmt->bindParam(':group_id',$grp_id,PDO::PARAM_INT);
       $get_proj_idea_for_specific_std_for_specific_grp_stmt->bindParam(':std_id_login',$std_id_login,PDO::PARAM_STR);

       $get_proj_idea_for_specific_std_for_specific_grp_stmt->execute();
       $data = $get_proj_idea_for_specific_std_for_specific_grp_stmt->fetchAll();
       return $data;
    }
    public static function resend_project_idea_into_my_sup($idea_name,$idea_desc,$idea_id){

      global $pdo;
	   
      $update_project_idea_query = "UPDATE `ideas_of_project` 
SET 
 `idea_name`=".$pdo->quote($idea_name).",`description`=".$pdo->quote($idea_desc)." WHERE `id`=:id";
      $update_project_idea_stmt = $pdo->prepare($update_project_idea_query);
      $update_project_idea_stmt->bindParam(':id',$idea_id,PDO::PARAM_INT); 
     return  $update_project_idea_stmt->execute();
	   
    }
    public static function get_specific_info_about_specific_idea_id($idea_id){
        global $pdo;
        $get_specific_info_about_specific_idea_id_query="SELECT   `idea_name`, `description` FROM `ideas_of_project` WHERE `id`=:id;";
        $get_specific_info_about_specific_idea_id_stmt=$pdo->prepare($get_specific_info_about_specific_idea_id_query);
        $get_specific_info_about_specific_idea_id_stmt->bindParam(':id',$idea_id,PDO::PARAM_INT);
        $get_specific_info_about_specific_idea_id_stmt->execute();
       $data = $get_specific_info_about_specific_idea_id_stmt->fetchAll();
        return  $data;
    }
    /*accept_project_idea.php*/

public static function get_sub_groups_of_sup($teacher_id,$start,$rec_limit){
    global $pdo;
    $get_groups_of_sup_query = "SELECT `teacher_id`, `group_id`, `grp_name`, `grp_id`,`sup_status` FROM
                                `supervision`,`groups` WHERE `grp_id`=`group_id` AND sup_status='accepted' AND `teacher_id`=:teacher_id 
                                LIMIT :start,:rec_limit;";
    $get_groups_of_sup_stmt = $pdo->prepare($get_groups_of_sup_query);
    $get_groups_of_sup_stmt->bindParam(':teacher_id',$teacher_id,PDO::PARAM_STR);
    $get_groups_of_sup_stmt->bindParam(':start',$start,PDO::PARAM_INT);
    $get_groups_of_sup_stmt->bindParam(':rec_limit',$rec_limit,PDO::PARAM_INT);
    
    $get_groups_of_sup_stmt->execute();
    $data = $get_groups_of_sup_stmt->fetchAll();
    return $data;
    }
    public static function get_no_of_groups_of_sup($teacher_id){
    global $pdo;
    $row=0;
    $get_groups_of_sup_query = "SELECT COUNT(*) counter FROM
                                `supervision`,`groups` WHERE `grp_id`=`group_id` AND `teacher_id`=:teacher_id;";
    $get_groups_of_sup_stmt = $pdo->prepare($get_groups_of_sup_query);
    $get_groups_of_sup_stmt->bindParam(':teacher_id',$teacher_id,PDO::PARAM_STR);
    $get_groups_of_sup_stmt->execute();
    $data = $get_groups_of_sup_stmt->fetchAll();
    if($data!=null){
        $row=$data[0]['counter'];
    }
    return $row;
    }
    public static function get_group_member_for_specific_groups($grp_id){
        global $pdo;
        $get_group_member_for_specific_groups_query = "SELECT group_members.group_id, group_members.student_id, 
        concat(users.fname,' ',users.lname) as name FROM `group_members`,`student`, `users` WHERE student.id = group_members.student_id  
        AND student.id = users.usr_id  AND group_members.status='accepted' AND group_members.group_id=:group_id;";
        $get_group_member_for_specific_groups_stmt = $pdo->prepare($get_group_member_for_specific_groups_query);
        $get_group_member_for_specific_groups_stmt->bindParam(':group_id',$grp_id,PDO::PARAM_INT);
        $get_group_member_for_specific_groups_stmt->execute();
        $data = $get_group_member_for_specific_groups_stmt->fetchAll();
        return $data;
        
    }
    public static function get_std_id_idea($std,$grp_id){
        global $pdo;
        $get_std_id_idea_query = "SELECT `group_id`, `idea_id`, `idea_status`, `proposer`, `date_of_proposal`, 
        `date_of_acceptance_of_proposal`,`id`, `idea_name`, `description`  
        FROM `idea_acceptance` ,`ideas_of_project` WHERE `proposer`=:proposer AND `group_id` =:group_id AND id=idea_id  ORDER by idea_status;"; 
        $get_std_id_idea_stmt = $pdo->prepare($get_std_id_idea_query);
        $get_std_id_idea_stmt->bindParam(':group_id',$grp_id,PDO::PARAM_INT);
        $get_std_id_idea_stmt->bindParam(':proposer',$std,PDO::PARAM_STR);
        $get_std_id_idea_stmt->execute();
        $data = $get_std_id_idea_stmt->fetchAll();
        return $data;
        
    }
    public static function accept_one_idea_and_reject_other_for_specific_grp($group_id1,$idea_id1,$supervisor_login_id){
     /**/
   
 global $pdo;
        $row      = 0;
        // $state_arr_data = array($sem_id , $state_no);
         try {
           // $pdo->beginTransaction();
           /**/
           $get_accepted_idea_for_param_grp_query = "SELECT count(*) as counter from `idea_acceptance` 
             where `idea_status`='accepted' AND group_id=:group_id;";
$get_accepted_idea_for_param_grp_stmt = $pdo->prepare($get_accepted_idea_for_param_grp_query); 
$get_accepted_idea_for_param_grp_stmt->bindParam(':group_id',$group_id1,PDO::PARAM_INT); 
/* $res1=$reject_other_idea_for_specific_grp_stmt->execute();
  var_dump($reject_other_idea_for_specific_grp_stmt->rowCount());*/
/**/$res= 
$get_accepted_idea_for_param_grp_stmt->execute();
$data2 = $get_accepted_idea_for_param_grp_stmt->fetchAll();
$rowCounter = $data2[0]['counter'];
if ($rowCounter!=0) {
  $reject_other_idea_for_specific_grp_query = 
            "UPDATE `idea_acceptance` 
            SET
            `idea_status`='reject' ,
            `date_of_acceptance_of_proposal`=NOW() where `idea_status`='accepted' AND group_id=:group_id;
           ";

$reject_other_idea_for_specific_grp_stmt = $pdo->prepare($reject_other_idea_for_specific_grp_query); 
$reject_other_idea_for_specific_grp_stmt->bindParam(':group_id',$group_id1,PDO::PARAM_INT); 
/* $res1=$reject_other_idea_for_specific_grp_stmt->execute();
  var_dump($reject_other_idea_for_specific_grp_stmt->rowCount());*/
/**/$res= 
$reject_other_idea_for_specific_grp_stmt->execute();
}
    
    $accept_one_idea_and_reject_other_for_specific_grp_query = 
            "UPDATE `idea_acceptance` 
            SET
            `idea_status`='accepted' ,
            `date_of_acceptance_of_proposal`=NOW(),
            Supervisor_who_accept_the_idea=:Supervisor_who_accept_the_idea 
            WHERE  `idea_id`=:idea_id 
            AND group_id=:group_id; ";

$accept_one_idea_and_reject_other_for_specific_grp_stmt = $pdo->prepare($accept_one_idea_and_reject_other_for_specific_grp_query); 
$accept_one_idea_and_reject_other_for_specific_grp_stmt->bindParam(':group_id',$group_id1,PDO::PARAM_INT);
$accept_one_idea_and_reject_other_for_specific_grp_stmt->bindParam(':idea_id',$idea_id1,PDO::PARAM_INT);
$accept_one_idea_and_reject_other_for_specific_grp_stmt->bindParam(':Supervisor_who_accept_the_idea',$supervisor_login_id,PDO::PARAM_STR);

$res = $accept_one_idea_and_reject_other_for_specific_grp_stmt->execute(); 
   
     
return $res;

           // $pdo->commit();
        }
        catch (Exception $e) {
           // if (isset($pdo)) {
                echo $e->getMessage();
                return false;
               // $pdo->rollback();
               $err = "ﻟﻢ ﺗﺘﻢ اﻟﻌﻤﻠﻴﺔ ﺑﻨﺠﺎﺡ";
          //  }
        }
        return $res;
     /**/

    }
    public static function reject_this_once_idea_has_accepted_request($group_id,$idea_id,$supervisor_login_id){
        global $pdo;
        $reject_this_once_idea_has_accepted_request_query = 
            "UPDATE `idea_acceptance` 
            SET
            `idea_status`='reject' ,
            `date_of_acceptance_of_proposal`=NOW(),
              Supervisor_who_accept_the_idea=:Supervisor_who_accept_the_idea 
            WHERE  `idea_id`=:idea_id 
            AND group_id=:group_id; ";

$reject_this_once_idea_has_accepted_request_stmt = $pdo->prepare($reject_this_once_idea_has_accepted_request_query); 
$reject_this_once_idea_has_accepted_request_stmt->bindParam(':group_id',$group_id,PDO::PARAM_INT);
$reject_this_once_idea_has_accepted_request_stmt->bindParam(':idea_id',$idea_id,PDO::PARAM_INT);
$reject_this_once_idea_has_accepted_request_stmt->bindParam(':Supervisor_who_accept_the_idea',$supervisor_login_id,PDO::PARAM_STR);

$res = $reject_this_once_idea_has_accepted_request_stmt->execute();
return $res;
    }
/*send_your_weekly_project_work.php*/
 
public static function check_if_this_del_msg_id_valid($msg_id){
    global $pdo;
    $check_if_this_del_msg_id_valid_query="SELECT COUNT(*)counter FROM messages WHERE messages_id=:messages_id;";
    $check_if_this_del_msg_id_valid_stmt = $pdo->prepare($check_if_this_del_msg_id_valid_query);
    $check_if_this_del_msg_id_valid_stmt->bindParam(':messages_id',$msg_id,PDO::PARAM_INT);
    $check_if_this_del_msg_id_valid_stmt->execute();
    $rowCount = $check_if_this_del_msg_id_valid_stmt->rowCount();
    return $rowCount;
}
public static function get_first_and_end_date_for_evt($evt_name)
    {
        global $pdo;
	 
 
        $get_first_and_end_date_for_evt_query = "SELECT UNIX_TIMESTAMP(`from_date`) as from_date, UNIX_TIMESTAMP(`to_date`) as to_date,evt.name_in_en FROM `evt_date` INNER JOIN semester ON semester.auto_inc_id=evt_date.semester_id AND semester.active=1 INNER JOIN evt on evt.id=evt_date.evt_id AND evt.name_in_en=:evt_name";
		 
        $get_first_and_end_date_for_evt_stmt  = $pdo->prepare($get_first_and_end_date_for_evt_query);
        $get_first_and_end_date_for_evt_stmt->bindValue(':evt_name',$evt_name,PDO::PARAM_STR);
        $get_first_and_end_date_for_evt_stmt->execute();
        $get_first_and_end_date_for_evt_arr = $get_first_and_end_date_for_evt_stmt->fetchAll();
        return $get_first_and_end_date_for_evt_arr;
    }
    public static function get_messages_from_db_and_using_it_specify_if_this_user_send_its_work_ship($group_id){
    global $pdo;
    $specify_if_this_user_send_its_work_ship_query = "SELECT UNIX_TIMESTAMP(`sending_time`) as from_date FROM `messages`,`attachments`
    WHERE msg_id in (SELECT `messages_id` FROM `messages` WHERE `group_id`=:group_id ); 
    ";
    $specify_if_this_user_send_its_work_ship_stmt = $pdo->prepare($specify_if_this_user_send_its_work_ship_query);
    $specify_if_this_user_send_its_work_ship_stmt->bindValue(':group_id',$group_id,PDO::PARAM_STR);
    $specify_if_this_user_send_its_work_ship_stmt->execute();
    $data1 = $specify_if_this_user_send_its_work_ship_stmt->fetchAll();
    return $data1;
    }
    public static function get_messages_from_db_for_specific_grp_member($group_id,$grp_member_no){
    global $pdo;
    $specify_if_this_user_send_its_work_ship_query = "SELECT UNIX_TIMESTAMP(`sending_time`) as from_date,
    `attachments_id`, `url_str`,`messages_id`, `group_id`, `sender`, `messages_text` FROM `messages`,`attachments` 
    WHERE msg_id in (SELECT `messages_id` FROM `messages` WHERE `group_id`=:group_id AND sender=:grp_member_no ); 
    ";
    $specify_if_this_user_send_its_work_ship_stmt = $pdo->prepare($specify_if_this_user_send_its_work_ship_query);
    $specify_if_this_user_send_its_work_ship_stmt->bindValue(':group_id',$group_id,PDO::PARAM_STR);
    $specify_if_this_user_send_its_work_ship_stmt->bindValue(':grp_member_no',$grp_member_no,PDO::PARAM_STR);
    $specify_if_this_user_send_its_work_ship_stmt->execute();
    $data1 = $specify_if_this_user_send_its_work_ship_stmt->fetchAll();
    return $data1;
    }
	public static function get_this_grp_weekly_file_for_specific_grp($grp_id){
		global $pdo;
		$check_if_this_grp_weekly_file_was_accepted_by_supervisor_query = "SELECT `messages_id`, `group_id`, `sender`,
		UNIX_TIMESTAMP(`sending_time`) as sending_time1
		, `messages_text`,`is_this_thesis_file`,
		`to_which_msg_id_lecture_reply`,`attachments_id`, `msg_id`, `url_str`, `status` 
		FROM `messages`,`attachments` WHERE group_id=:grp_id and LEFT(`sender`, 1)='s' and msg_id=messages_id order by sending_time1 DESC;";
		$check_if_this_grp_weekly_file_was_accepted_by_supervisor_stmt = $pdo->prepare($check_if_this_grp_weekly_file_was_accepted_by_supervisor_query);
		$check_if_this_grp_weekly_file_was_accepted_by_supervisor_stmt->bindParam(':grp_id',$grp_id,PDO::PARAM_INT);
		$check_if_this_grp_weekly_file_was_accepted_by_supervisor_stmt->execute();
		$data = $check_if_this_grp_weekly_file_was_accepted_by_supervisor_stmt->fetchAll();
		
		return $data;
	}
    public static function get_all_weekly_file_for_this_supervisor($supervisor_id){
        global $pdo;
       $get_all_weekly_file_for_this_supervisor_query = "SELECT groups.grp_name,`messages_id`, `group_id`, `sender`, UNIX_TIMESTAMP(`sending_time`) as sending_time1 ,concat(fname,' ',lname) as name, `messages_text`,`is_this_thesis_file`, `to_which_msg_id_lecture_reply`,`attachments_id`, `msg_id`, `url_str`, attachments.status FROM `messages`,`attachments`,users,groups WHERE
groups.grp_id=messages.group_id AND 
users.usr_id=`sender` AND LEFT(`sender`, 1)='s' and msg_id=messages_id AND messages.group_id in (SELECT supervision.group_id FROM supervision WHERE supervision.teacher_id=".$pdo->quote($supervisor_id)." AND supervision.sup_status='accepted') order by sending_time1 DESC";

        $get_all_weekly_file_for_this_supervisor_stmt = $pdo->query($get_all_weekly_file_for_this_supervisor_query);
        $get_all_weekly_file_for_this_supervisor_stmt->execute();
       return $get_all_weekly_file_for_this_supervisor_stmt->fetchAll();

    }

/* chat_room.php*/
public static function check_if_file_exist_in_chat_room_just_one_student_and_his_supervisors_or_two_std_at_least($grp_id){
global $pdo;
  $check_if_file_exist_in_chat_room_just_one_student_and_his_supervisors_or_two_std_at_least_query = "
  SELECT `usr_id`, `chat_room_id_fk`, `chat_room_deletion_state`, `chat_room_left_state` 
FROM 
`rooms_users` 
WHERE 
chat_room_left_state='exist' 
AND
chat_room_id_fk 
IN 
(SELECT chat_room_id_fk FROM `groups` WHERE grp_id=:grp_id )
AND
(
(
(SELECT COUNT(*) FROM rooms_users WHERE LEFT(`usr_id`, 1)='s' )>1)
    OR(
    (SELECT COUNT(*) FROM rooms_users WHERE LEFT(`usr_id`, 1)='s' )=1) AND (SELECT COUNT(*) FROM rooms_users WHERE LEFT(`usr_id`, 1)='l' )>0)
";
 $check_if_file_exist_in_chat_room_just_one_student_and_his_supervisors_or_two_std_at_least_stmt = $pdo->prepare( $check_if_file_exist_in_chat_room_just_one_student_and_his_supervisors_or_two_std_at_least_query);

$check_if_file_exist_in_chat_room_just_one_student_and_his_supervisors_or_two_std_at_least_stmt->bindParam(':grp_id',$grp_id,PDO::PARAM_INT);
$check_if_file_exist_in_chat_room_just_one_student_and_his_supervisors_or_two_std_at_least_stmt->execute();
return $check_if_file_exist_in_chat_room_just_one_student_and_his_supervisors_or_two_std_at_least_stmt->fetchAll();

}

    public static function get_all_supervisor_file_grp_for_this_week($supervisor_id){
        global $pdo;
        $get_this_grp_weekly_file_for_specific_grp_arr_res=array();
        $get_this_grp_weekly_file_for_specific_grp_res = self::get_all_weekly_file_for_this_supervisor($supervisor_id);
        if($get_this_grp_weekly_file_for_specific_grp_res!=null){
            /* check btn current and sending time */
            /* start */
               $evt_name="send_your_weekly_project_work";
                $arr_from_and_to_evt_date=self::get_first_and_end_date_for_evt($evt_name);
            $start_date =  $arr_from_and_to_evt_date[0]['from_date'];
        /*start difference current and start*/
            $hour=date('H');
             $min=date('i');
             $sec=date('s');
             $month=date('m');
             $day=date('d');
             $year=date('Y');
             $current_Date = mktime($hour, $min, $sec, $month, $day, $year);
            $current_Date= date("Y-m-d H:i:s", $current_Date);
            $current_Date = strtotime($current_Date);
             $start_date = date("Y-m-d H:i:s", $start_date);
            $start_date = strtotime($start_date);
            $datediff = $current_Date - $start_date  ;
            $date_difference_btn_current_and_start_evt_date =ceil(round(($datediff)/(60 * 60 * 24))/7.0);
             $send_thesis_file_evt_name="send_thesis_file";
 $send_your_weekly_project_work_begining_and_ending_evt_date=Crud_op::get_first_and_end_date_for_evt($send_thesis_file_evt_name);
     
         $send_thesis_file_begining_date=$send_your_weekly_project_work_begining_and_ending_evt_date[0]['from_date'];
 $send_thesis_file_ending_date=$send_your_weekly_project_work_begining_and_ending_evt_date[0]['to_date'];
 //$current_Date=date("Y-m-d H:i:s");
 $hour=date('H');
 $min=date('i');
 $sec=date('s');
 $month=date('m');
 $day=date('d');
 $year=date('Y');
 $current_Date1 = mktime($hour, $min, $sec, $month, $day, $year);
//$current_Date = strtotime($current_Date);
$current_Date1= date("Y-m-d H:i:s", $current_Date1);
//$from_date = strtotime($from_date);
$send_thesis_file_begining_date= date("Y-m-d H:i:s", $send_thesis_file_begining_date);
$diff_btn_send_thesis_file_begining_date_and_current_date=$current_Date1-$send_thesis_file_begining_date;
$diff_btn_send_thesis_file_begining_date_and_current_date =ceil(round(($diff_btn_send_thesis_file_begining_date_and_current_date)/(60 * 60 * 24))/7.0);
            
 
                        /* end */
                for($i=0;$i<count($get_this_grp_weekly_file_for_specific_grp_res);$i++){
                $sending_time1 = $get_this_grp_weekly_file_for_specific_grp_res[$i]['sending_time1'] ;  
            $date_difference_btn_sending_and_start_evt_date = $sending_time1 - $start_date  ;
            $date_difference_btn_sending_and_start_evt_date =ceil(round(($date_difference_btn_sending_and_start_evt_date)/(60 * 60 * 24))/7.0);
            if(($date_difference_btn_sending_and_start_evt_date==$date_difference_btn_current_and_start_evt_date) ||
            ($diff_btn_send_thesis_file_begining_date_and_current_date==$date_difference_btn_current_and_start_evt_date) ){
                $row=$get_this_grp_weekly_file_for_specific_grp_res[$i];
                      array_push($get_this_grp_weekly_file_for_specific_grp_arr_res, $row);
            }
                    
                      
                }
                
        
        
        } 
     return $get_this_grp_weekly_file_for_specific_grp_arr_res;
    }
    public static function get_status_of_thesis_file($grp_id){
        global $pdo;
        $get_status_of_thesis_file_query = "SELECT * FROM `messages`,attachments WHERE attachments.msg_id=messages.messages_id AND messages.group_id=:groupID AND is_this_thesis_file=1";
        $get_status_of_thesis_file_stmt = $pdo->prepare($get_status_of_thesis_file_query);
        $get_status_of_thesis_file_stmt->bindParam(':groupID',$grp_id,PDO::PARAM_INT);
         $get_status_of_thesis_file_stmt->execute();
         $data = $get_status_of_thesis_file_stmt->fetchAll();
         $status_of_thesis_file="";
if ($data!=null) {
    $status_of_thesis_file=$data[0]['status'];
}
return   $status_of_thesis_file;
    }
public static function checkIfSenderFileIsThesis($grp_id){
global $pdo;
$checkIfSenderFileIsThesisQuery = "SELECT `thesis`  FROM `groups` WHERE grp_id=:grp_id AND `thesis` is not null;";
$checkIfSenderFileIsThesisStmt = $pdo->prepare($checkIfSenderFileIsThesisQuery);
$checkIfSenderFileIsThesisStmt->bindParam(':grp_id',$grp_id,PDO::PARAM_INT);
$checkIfSenderFileIsThesisStmt->execute();
$data = $checkIfSenderFileIsThesisStmt->fetchAll();
$thesis = "";
if ($data) {
  $thesis = $checkIfSenderFileIsThesisStmt[0]['thesis'];  
}
return $thesis;
}
	public static function get_file_grp_gor_this_week($grp_id){
		global $pdo;
		$get_this_grp_weekly_file_for_specific_grp_arr_res=array();
		$get_this_grp_weekly_file_for_specific_grp_res = self::get_this_grp_weekly_file_for_specific_grp($grp_id);
		
        if($get_this_grp_weekly_file_for_specific_grp_res!=null){
			/* check btn current and sending time */
			/* start */
			   $evt_name="send_your_weekly_project_work";
				$arr_from_and_to_evt_date=self::get_first_and_end_date_for_evt($evt_name);
			$start_date =  $arr_from_and_to_evt_date[0]['from_date'];
		/*start difference current and start*/
		    $hour=date('H');
			 $min=date('i');
			 $sec=date('s');
			 $month=date('m');
			 $day=date('d');
			 $year=date('Y');
			 $current_Date = mktime($hour, $min, $sec, $month, $day, $year);
			$current_Date= date("Y-m-d H:i:s", $current_Date);
			$current_Date = strtotime($current_Date);
			 $start_date = date("Y-m-d H:i:s", $start_date);
			$start_date = strtotime($start_date);
			$datediff = $current_Date - $start_date  ;
			$date_difference_btn_current_and_start_evt_date =ceil(round(($datediff)/(60 * 60 * 24))/7.0);
			 $send_thesis_file_evt_name="send_thesis_file";
 $send_your_weekly_project_work_begining_and_ending_evt_date=Crud_op::get_first_and_end_date_for_evt($send_thesis_file_evt_name);
	 
		 $send_thesis_file_begining_date=$send_your_weekly_project_work_begining_and_ending_evt_date[0]['from_date'];
 $send_thesis_file_ending_date=$send_your_weekly_project_work_begining_and_ending_evt_date[0]['to_date'];
 //$current_Date=date("Y-m-d H:i:s");
 $hour=date('H');
 $min=date('i');
 $sec=date('s');
 $month=date('m');
 $day=date('d');
 $year=date('Y');
 $current_Date1 = mktime($hour, $min, $sec, $month, $day, $year);
//$current_Date = strtotime($current_Date);
$current_Date1= date("Y-m-d H:i:s", $current_Date1);
//$from_date = strtotime($from_date);
$send_thesis_file_begining_date= date("Y-m-d H:i:s", $send_thesis_file_begining_date);
$diff_btn_send_thesis_file_begining_date_and_current_date=$current_Date1-$send_thesis_file_begining_date;
$diff_btn_send_thesis_file_begining_date_and_current_date =ceil(round(($diff_btn_send_thesis_file_begining_date_and_current_date)/(60 * 60 * 24))/7.0);
			
 
						/* end */
				for($i=0;$i<count($get_this_grp_weekly_file_for_specific_grp_res);$i++){
				$sending_time1 = $get_this_grp_weekly_file_for_specific_grp_res[$i]['sending_time1'] ;	
			$date_difference_btn_sending_and_start_evt_date = $sending_time1 - $start_date  ;
			$date_difference_btn_sending_and_start_evt_date =ceil(round(($date_difference_btn_sending_and_start_evt_date)/(60 * 60 * 24))/7.0);
			if(($date_difference_btn_sending_and_start_evt_date==$date_difference_btn_current_and_start_evt_date) ||
			($diff_btn_send_thesis_file_begining_date_and_current_date==$date_difference_btn_current_and_start_evt_date) ){
				$row=$get_this_grp_weekly_file_for_specific_grp_res[$i];
					  $get_this_grp_weekly_file_for_specific_grp_arr_res[]= $row ;
			}
					
					  
				}
				
		
		
		} 
	 return $get_this_grp_weekly_file_for_specific_grp_arr_res;
	}
	public static function check_if_this_file_accepted_in_this_week($grp_id){
		global $pdo;
		$grp_file_staus_this_week="";
		$get_file_grp_gor_this_week = self::get_file_grp_gor_this_week($grp_id);
		if($get_file_grp_gor_this_week==null){return '';}
		$key='status'; $key_value='accepted';
		$check_if_this_grp_has_accept_file_in_this_week = self::is_in_array($get_file_grp_gor_this_week, $key, $key_value);
		   if($check_if_this_grp_has_accept_file_in_this_week=="yes"){
			   $grp_file_staus_this_week="accepted";
			   return $grp_file_staus_this_week;  
		   }
		   else {
			  $grp_file_staus_this_week= self::check_if_this_last_file_sending_by_grp_reject_or_pending_in_this_week($grp_id);
			   
		   }
	
			return $grp_file_staus_this_week;    
	}
	public static function check_if_this_last_file_sending_by_grp_reject_or_pending_in_this_week($grp_id){
	global $pdo;
	 $grp_status="";
	//by using  order by sending_time1 DESC then last file send may have reject or pending in thi status
		$get_this_grp_weekly_file_for_specific_grp = self::get_this_grp_weekly_file_for_specific_grp($grp_id);
		if($get_this_grp_weekly_file_for_specific_grp!=null){
			$grp_status = $get_this_grp_weekly_file_for_specific_grp[0]['status'];
		
		}
		
		return $grp_status;//may be pending or reject
	 
	}
	
    public static function check_if_this_usr_has_grp($user_id)
    {
	 
        global $pdo;
        $status = "accepted";
        $check_if_this_usr_admin_data1             = null;
        $check_if_this_usr_admin_of_this_grp_query ="SELECT grp_id,grp_name
													FROM group_members grp_mem
													INNER JOIN groups grp
													on grp_mem.group_id = grp.grp_id  
													and grp_mem.student_id=".$pdo->quote($user_id)."
													and grp_mem.status='accepted'
													INNER JOIN semester sem
													on sem.auto_inc_id = grp.semester_id 
													and grp.semester_id in 
													(select sem.auto_inc_id from semester where active=1);"; 
        $check_if_this_usr_admin_of_this_grp_stmt  = $pdo->prepare($check_if_this_usr_admin_of_this_grp_query);
        // $check_if_this_usr_admin_of_this_grp_stmt->bindParam(':student_id', $user_id, PDO::PARAM_STR);
        // $check_if_this_usr_admin_of_this_grp_stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $check_if_this_usr_admin_of_this_grp_stmt->execute();
        $check_if_this_usr_admin_data = $check_if_this_usr_admin_of_this_grp_stmt->fetchAll();
        if ($check_if_this_usr_admin_data != null) {
            # code...
            $check_if_this_usr_admin_data1 = $check_if_this_usr_admin_data[0]['grp_id'];
			 
        }
        return $check_if_this_usr_admin_data1;
    } public static function check_if_this_usr_has_grp1($user_id)
    {
	 
        global $pdo;
        $status = "accepted";
        $check_if_this_usr_admin_data1             = null;
        $check_if_this_usr_admin_of_this_grp_query ="SELECT grp_id,grp_name
													FROM group_members grp_mem
													INNER JOIN groups grp
													on grp_mem.group_id = grp.grp_id  
													and grp_mem.student_id=".$pdo->quote($user_id)."
													and grp_mem.status='accepted'
													INNER JOIN semester sem
													on sem.auto_inc_id = grp.semester_id 
													and grp.semester_id in 
													(select sem.auto_inc_id from semester where active=1);"; 
        $check_if_this_usr_admin_of_this_grp_stmt  = $pdo->prepare($check_if_this_usr_admin_of_this_grp_query);
        // $check_if_this_usr_admin_of_this_grp_stmt->bindParam(':student_id', $user_id, PDO::PARAM_STR);
        // $check_if_this_usr_admin_of_this_grp_stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $check_if_this_usr_admin_of_this_grp_stmt->execute();
        $check_if_this_usr_admin_data = $check_if_this_usr_admin_of_this_grp_stmt->fetchAll();
        if ($check_if_this_usr_admin_data != null) {
            # code...
            $check_if_this_usr_admin_data1 = $check_if_this_usr_admin_data[0]['grp_name'];
			 
        }
        return $check_if_this_usr_admin_data1;
    }
     public static function check_if_this_grp_has_a_supervisor($grp_id)
    {
        global $pdo;
        $row                                      = 0;
        $sup_status                               = "accepted";
        $check_if_this_grp_has_a_supervisor_query = "SELECT  COUNT(*) counter FROM `supervision` WHERE  `group_id`=:group_id AND `sup_status`=:sup_status;";
        $check_if_this_grp_has_a_supervisor_stmt  = $pdo->prepare($check_if_this_grp_has_a_supervisor_query);
        $check_if_this_grp_has_a_supervisor_stmt->bindParam(':group_id', $grp_id, PDO::PARAM_INT);
        $check_if_this_grp_has_a_supervisor_stmt->bindParam(':sup_status', $sup_status, PDO::PARAM_STR);
        $check_if_this_grp_has_a_supervisor_stmt->execute();
        $data = $check_if_this_grp_has_a_supervisor_stmt->fetchAll();
        if ($data != null) {
            $row = $data[0]['counter'];
        }
        return $row;
    }
    public static function check_if_this_grp_has_an_idea($grp_id){
        
        global $pdo;
        $row_count=0;
        $check_if_this_grp_has_an_idea_query = "SELECT COUNT(*) as counter FROM `idea_acceptance` WHERE idea_status='accepted' and group_id=:grp_id;";
        $check_if_this_grp_has_an_idea_stmt = $pdo->prepare($check_if_this_grp_has_an_idea_query);
        $check_if_this_grp_has_an_idea_stmt->bindParam(':grp_id',$grp_id,PDO::PARAM_INT);
        $check_if_this_grp_has_an_idea_stmt->execute();
        $row_count_data = $check_if_this_grp_has_an_idea_stmt->fetchAll();
        if($row_count_data!=null){
            $row_count = $row_count_data[0]['counter'];
        }
        return $row_count;
        
    }
        public static function delete_specific_msg_for_specif_sender_at_specific_grp($attachments_id,$messages_id){
            
        global $pdo;
        $row1=0;
        $row2=0;
         try {
            $pdo->beginTransaction(); 
            $delete_specific_msg_for_specif_sender_at_specific_grp_query = "DELETE FROM `attachments` WHERE attachments_id=:attachments_id;";
            $delete_specific_msg_for_specif_sender_at_specific_grp_stmt  = $pdo->prepare($delete_specific_msg_for_specif_sender_at_specific_grp_query);
            $delete_specific_msg_for_specif_sender_at_specific_grp_stmt->bindParam(':attachments_id', $attachments_id, PDO::PARAM_INT);
            $delete_specific_msg_for_specif_sender_at_specific_grp_stmt->execute();
            $row1 = $delete_specific_msg_for_specif_sender_at_specific_grp_stmt->rowCount();
            
    $delete_specific_msg_for_specif_sender_at_specific_grp_query = "DELETE FROM `messages` WHERE messages_id=:messages_id;";
            $delete_specific_msg_for_specif_sender_at_specific_grp_stmt  = $pdo->prepare($delete_specific_msg_for_specif_sender_at_specific_grp_query);
            $delete_specific_msg_for_specif_sender_at_specific_grp_stmt->bindParam(':messages_id', $messages_id, PDO::PARAM_INT);
            $delete_specific_msg_for_specif_sender_at_specific_grp_stmt->execute();
            $row2 = $delete_specific_msg_for_specif_sender_at_specific_grp_stmt->rowCount();
            $pdo->commit();
            
        }
        catch (Exception $e) {
            if (isset($pdo)) {
                return 0;
                $pdo->rollback();
               $err = "ﻟﻢ ﺗﺘﻢ اﻟﻌﻤﻠﻴﺔ ﺑﻨﺠﺎﺡ";
            }
        }
        return $row1+$row2;
            
        }
        public static function insert_a_new_messages_without_attachment($group_id ,$sender,$messages_text ,$to_which_msg_id_reply ,$is_this_thesis_file ,$IsThisMsgTheMother){
            global $pdo;
            
 $insert_specific_msg_for_specif_sender_at_specific_grp_query = 
            "
            INSERT INTO `messages`
            SET 
            `group_id`=:group_id,
            `sender`=:sender, 
            `sending_time`=NOW(), 
            `messages_text`=:messages_text,
            `to_which_msg_id_reply`=:to_which_msg_id_reply,
            `is_this_thesis_file`=:is_this_thesis_file, 
            `IsThisMsgTheMother`=:IsThisMsgTheMother ;
            ";
            $insert_specific_msg_for_specif_sender_at_specific_grp_stmt  = $pdo->prepare($insert_specific_msg_for_specif_sender_at_specific_grp_query);
            $insert_specific_msg_for_specif_sender_at_specific_grp_stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
            $insert_specific_msg_for_specif_sender_at_specific_grp_stmt->bindValue(':sender', $sender, PDO::PARAM_STR);
            $insert_specific_msg_for_specif_sender_at_specific_grp_stmt->bindValue(':messages_text', $messages_text, PDO::PARAM_STR);
            $insert_specific_msg_for_specif_sender_at_specific_grp_stmt->bindValue(':to_which_msg_id_reply', $to_which_msg_id_reply, PDO::PARAM_INT);  
            $insert_specific_msg_for_specif_sender_at_specific_grp_stmt->bindValue(':is_this_thesis_file', $is_this_thesis_file, PDO::PARAM_INT);
            $insert_specific_msg_for_specif_sender_at_specific_grp_stmt->bindValue(':IsThisMsgTheMother', $IsThisMsgTheMother, PDO::PARAM_INT);
            
            
            $insert_specific_msg_for_specif_sender_at_specific_grp_stmt->execute(); 
           return $pdo->lastInsertId();

        }
        public static function insert_a_new_msg_with_its_attachment($group_id ,$sender,$messages_text ,$to_which_msg_id_reply ,$is_this_thesis_file ,$IsThisMsgTheMother, $url_str,$status="pending"){
            
        global $pdo;
        /* start upload file and it's attachment */

 
        /**/ 
         try {
            $pdo->beginTransaction();  
           $last_insert_msg_id =  self::insert_a_new_messages_without_attachment($group_id ,$sender,$messages_text ,$to_which_msg_id_reply ,$is_this_thesis_file ,$IsThisMsgTheMother);
            
            if ($url_str!="") {
                

            $insert_specific_attachment_for_specif_msg_at_specific_grp_query = "INSERT INTO `attachments` SET  `msg_id`=:msg_id,status='pending', `url_str`=:url_str;";
            $insert_specific_attachment_for_specif_msg_at_specific_grp_stmt  = $pdo->prepare($insert_specific_attachment_for_specif_msg_at_specific_grp_query);
            $insert_specific_attachment_for_specif_msg_at_specific_grp_stmt->bindParam(':msg_id', $last_insert_msg_id, PDO::PARAM_INT);
             
            $insert_specific_attachment_for_specif_msg_at_specific_grp_stmt->bindParam(':url_str', $url_str, PDO::PARAM_STR);
            $insert_specific_attachment_for_specif_msg_at_specific_grp_stmt->execute(); 
             
            }
            if ($status=="accepted") {
               
              Crud_op::update_thesis_url_for_accepted_file($group_id,$to_which_msg_id_reply);

            }
            $pdo->commit();
            return true;
        }
        catch (Exception $e) {
            if (isset($pdo)) {
                echo $e->getMessage();
                
                $pdo->rollback();
                return false;
               $err = "ﻟﻢ ﺗﺘﻢ اﻟﻌﻤﻠﻴﺔ ﺑﻨﺠﺎﺡ";
            }
        }
       
            
        }
        public static function update_thesis_url_for_accepted_file($groupID,$msgIDToGetUrlStrFrom){
            global $pdo;
           $getUrlThesisQuery = "SELECT `attachments_id`, `msg_id`, `url_str`, `status` FROM `attachments`,messages WHERE messages.messages_id=attachments.msg_id AND  msg_id=:msg_id AND url_str is not null ORDER BY messages.sending_time DESC LIMIT 1;";
             $getUrlThesisStmt = $pdo->prepare($getUrlThesisQuery);           
              $getUrlThesisStmt->bindParam(':msg_id',$msgIDToGetUrlStrFrom,PDO::PARAM_INT);
               $getUrlThesisStmt->execute();
              $data = $getUrlThesisStmt->fetchAll();
              $getUrlThesis =  "";

if ( $data!=null) {
   $getUrlThesis =  $data[0]['url_str']; 
}


            $update_thesis_url_for_accepted_file_query = "UPDATE `groups` SET `thesis`=:thesis ,`thesis_submission_date`=NOW() WHERE `grp_id`=:grp_id";
            $update_thesis_url_for_accepted_file_stmt = $pdo->prepare($update_thesis_url_for_accepted_file_query);
            $update_thesis_url_for_accepted_file_stmt->bindParam(':grp_id',$groupID,PDO::PARAM_INT); 
            $update_thesis_url_for_accepted_file_stmt->bindParam(':thesis',$getUrlThesis,PDO::PARAM_STR); 
            $update_thesis_url_for_accepted_file_stmt->execute();
        }
		public static function change_pending_weekly_file_status_for_specific_grp($attachments_id,$status){
			global $pdo;
			$change_pending_weekly_file_status_for_specific_grp_query = "UPDATE attachments SET
			status=:status 
			WHERE attachments_id=:attachments_id
			";
			$change_pending_weekly_file_status_for_specific_grp_stmt=$pdo->prepare($change_pending_weekly_file_status_for_specific_grp_query);
			$change_pending_weekly_file_status_for_specific_grp_stmt->bindValue(':attachments_id',$attachments_id,PDO::PARAM_INT);
			$change_pending_weekly_file_status_for_specific_grp_stmt->bindValue(':status',$status,PDO::PARAM_STR);
			$change_pending_weekly_file_status_for_specific_grp_stmt->execute();
			return $change_pending_weekly_file_status_for_specific_grp_stmt->rowCount();
		}
        public static function insert_a_new_msg_with_its_attachment_and_specify_to_which_msg_reply($status_weekly_file_satus,$group_id,$sender,
		$messages_text,$url_str,$to_which_msg_reply_id,$attachments_id,$is_this_thesis_file){
            
        global $pdo;
        $row1=0;
        $row2=0;
         try {
            $pdo->beginTransaction(); 
             
            $updated_rowCount=self::change_pending_weekly_file_status_for_specific_grp($attachments_id,$status_weekly_file_satus);
            $insert_specific_msg_for_specif_sender_at_specific_grp_query = "INSERT INTO `messages`
 SET `group_id`=:group_id, `sender`=:sender,  `messages_text`=:messages_text
 ,`is_this_thesis_file`=:is_this_thesis_file,`sending_time`=NOW();";
            $insert_specific_msg_for_specif_sender_at_specific_grp_stmt  = $pdo->prepare($insert_specific_msg_for_specif_sender_at_specific_grp_query);
            $insert_specific_msg_for_specif_sender_at_specific_grp_stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
            $insert_specific_msg_for_specif_sender_at_specific_grp_stmt->bindValue(':messages_text', $messages_text, PDO::PARAM_STR);
            $insert_specific_msg_for_specif_sender_at_specific_grp_stmt->bindValue(':sender', $sender, PDO::PARAM_STR); 
            $insert_specific_msg_for_specif_sender_at_specific_grp_stmt->bindValue(':is_this_thesis_file',$is_this_thesis_file,PDO::PARAM_INT);
            
			
			$insert_specific_msg_for_specif_sender_at_specific_grp_stmt->execute();
            $row1 = $insert_specific_msg_for_specif_sender_at_specific_grp_stmt->rowCount();
			 
            $last_insert_msg_id = $pdo->lastInsertId();
    $insert_specific_attachment_for_specif_msg_at_specific_grp_query = "INSERT INTO `attachments` SET  `msg_id`=:msg_id,status='pending', `url_str`=:url_str;";
            $insert_specific_attachment_for_specif_msg_at_specific_grp_stmt  = $pdo->prepare($insert_specific_attachment_for_specif_msg_at_specific_grp_query);
            $insert_specific_attachment_for_specif_msg_at_specific_grp_stmt->bindValue(':msg_id', $last_insert_msg_id, PDO::PARAM_INT);
             
            $insert_specific_attachment_for_specif_msg_at_specific_grp_stmt->bindValue(':url_str', $url_str, PDO::PARAM_STR);
            $insert_specific_attachment_for_specif_msg_at_specific_grp_stmt->execute();
            $row2 = $insert_specific_attachment_for_specif_msg_at_specific_grp_stmt->rowCount();
            $get_is_this_thesis_file_query="SELECT is_this_thesis_file FROM `messages` WHERE messages_id=:messages_id;";
			$get_is_this_thesis_file_stmt = $pdo->prepare($get_is_this_thesis_file_query);
			$get_is_this_thesis_file_stmt->bindValue(':messages_id',$last_insert_msg_id,PDO::PARAM_INT);
			$get_is_this_thesis_file_stmt->execute();
			$messages_id=$get_is_this_thesis_file_stmt->fetchAll();
			$is_this_thesis_file=null;
			if($messages_id!=null){
			$is_this_thesis_file=$messages_id[0]['is_this_thesis_file'];
            if($is_this_thesis_file==1){
				$update_thesis_query = "UPDATE `groups` SET `thesis`=:thesis,thesis_submission_date	=now() WHERE `grp_id`=:grp_id;";
				$update_thesis_stmt = $pdo->prepare($update_thesis_query);
				$update_thesis_stmt->bindValue(':grp_id',$group_id,PDO::PARAM_INT);
				$update_thesis_stmt->bindValue(':thesis',$url_str,PDO::PARAM_STR);
				$update_thesis_stmt->execute();
			}	
			}
			$pdo->commit();
        }
        catch (Exception $e) {
            if (isset($pdo)) {
                echo $e->getMessage();
                return 0;
                $pdo->rollback();
               $err = "ﻟﻢ ﺗﺘﻢ اﻟﻌﻤﻠﻴﺔ ﺑﻨﺠﺎﺡ";
            }
        }
        return $row1+$row2+$updated_rowCount;
        }
        public static function get_supervisor_of_this_grp($grp_id){
            global $pdo;
            $get_supervisor_of_this_grp_query = "SELECT `teacher_id`,concat(users.fname,' ',users.lname) as name FROM `supervision`,teacher,users WHERE 
            `sup_status`='accepted' AND `group_id`=:grp_id AND teacher.id=supervision.teacher_id and users.usr_id=teacher.id ;";
            $get_supervisor_of_this_grp_stmt = $pdo->prepare($get_supervisor_of_this_grp_query);
            $get_supervisor_of_this_grp_stmt->bindValue(':grp_id',$grp_id,PDO::PARAM_STR);
            $get_supervisor_of_this_grp_stmt->execute();
            $data = $get_supervisor_of_this_grp_stmt->fetchAll();
            return $data;
        }
public static function compareFiles($file_a, $file_b)
{
    if (filesize($file_a) == filesize($file_b))
    {
        $fp_a = fopen($file_a, 'rb');
        $fp_b = fopen($file_b, 'rb');

        while (($b = fread($fp_a, 4096)) !== false)
        {
            $b_b = fread($fp_b, 4096);
            if ($b !== $b_b)
            {
                fclose($fp_a);
                fclose($fp_b);
                return false;
            }
        }

        fclose($fp_a);
        fclose($fp_b);

        return true;
    }

    return false;
}
public static function check_if_this_file_exist_by_compare_its_content($grp_id,$file_b){
	global $pdo;
	$check_if_file_exist=false;
	$arr = self::get_file_grp_gor_this_week($grp_id);
	for($t1=0;$t1<count($arr);$t1++){
		$file_a=$arr[$t1]['url_str'];
		$check_if_file_exist=self::compareFiles($file_a, $file_b);
	 if($check_if_file_exist==true){
		 break;
	 }
	}
	return $check_if_file_exist;
}
/* send_your_weekly_project_work.php */
public static function check_if_grp_send_their_weeky_work_group($groupID){
    global $pdo;

    /* check if user send work file into sup this week */
$check_if_grp_send_their_weeky_group_query = 'SELECT ABS(WEEK(SELECT  DATE(`from_date`) FROM `evt_date`,`evt` WHERE evt_date.evt_id=evt.id AND evt_date.semester_id IN (SELECT semester.auto_inc_id FROM semester WHERE semester.active=1) AND evt.name_in_en="send_your_weekly_project_work";)-WEEK(DATE(SELECT `sending_time` FROM `messages` WHERE group_id=:group_id))   ) as diff;';
$check_if_grp_send_their_weeky_group_stmt = $pdo->prepare($check_if_grp_send_their_weeky_group_query);
$check_if_grp_send_their_weeky_group_stmt->bindParam(':group_id',$groupID,PDO::PARAM_INT);
$check_if_grp_send_their_weeky_group_stmt->execute();
$data = $check_if_grp_send_their_weeky_group_stmt->fetchAll();
$diff=0;
if ($data!=null) {
   $diff=$data[0]['diff'];
}
  /* check if user send thesis file into sup this week */
  $check_if_grp_send_their_thesis_group_query = 'SELECT ABS(
 

SELECT EXTRACT(WEEK FROM (SELECT `from_date` FROM `evt_date`,evt WHERE evt.id=evt_date.evt_id and evt_date.semester_id IN (SELECT semester.auto_inc_id from semester where semester.active=1) AND evt.name_in_en="send_your_weekly_project_work" ))

  - SELECT EXTRACT(WEEK FROM (SELECT `sending_time` FROM `messages` WHERE group_id=:group_id ))

  

   ) as diff;';
$check_if_grp_send_their_thesis_group_stmt = $pdo->prepare($check_if_grp_send_their_thesis_group_query);
$check_if_grp_send_their_thesis_group_stmt->bindParam(':group_id',$groupID,PDO::PARAM_INT);
$check_if_grp_send_their_thesis_group_stmt->execute();
$data1 = $check_if_grp_send_their_thesis_group_stmt->fetchAll();
$diffForThesis=0;
if ($data1!=null) {
   $diffForThesis=$data1[0]['diff'];
}
return $diff+$diffForThesis ;
}
public static function check_if_grp_send_their_thesis_file_group($groupID){
    global $pdo;

    /* check if user send work file into sup this week */
$check_if_grp_send_their_weeky_group_query = 'SELECT ABS(
WEEK(CURDATE())-SELECT EXTRACT(WEEK FROM (SELECT `sending_time` FROM `messages` WHERE group_id=:group_id ))

    ) as diff;';
$check_if_grp_send_their_weeky_group_stmt = $pdo->prepare($check_if_grp_send_their_weeky_group_query);
$check_if_grp_send_their_weeky_group_stmt->bindParam(':group_id',$groupID,PDO::PARAM_INT);
$check_if_grp_send_their_weeky_group_stmt->execute();
$data = $check_if_grp_send_their_weeky_group_stmt->fetchAll();
$diff=0;
if ($data!=null) {
   $diff=$data[0]['diff'];
}
  /* check if user send thesis file into sup this week */
  $check_if_grp_send_their_thesis_group_query = 'SELECT 
(
    ABS(
       SELECT EXTRACT(WEEK FROM (SELECT `sending_time` FROM `messages` WHERE group_id=:group_id ))

  
    -
     

SELECT EXTRACT(WEEK FROM (SELECT `from_date` FROM `evt_date`,evt WHERE evt.id=evt_date.evt_id and evt_date.semester_id IN (SELECT semester.auto_inc_id from semester where semester.active=1) AND evt.name_in_en="send_thesis_file" ))  

)  as diff;';
$check_if_grp_send_their_thesis_group_stmt = $pdo->prepare($check_if_grp_send_their_thesis_group_query);
$check_if_grp_send_their_thesis_group_stmt->bindParam(':group_id',$groupID,PDO::PARAM_INT);
$check_if_grp_send_their_thesis_group_stmt->execute();
$data1 = $check_if_grp_send_their_thesis_group_stmt->fetchAll();
$diffForThesis=0;
if ($data1!=null) {
   $diffForThesis=$data1[0]['diff'];
}
return $diff+$diffForThesis ;
}
	public static function get_grps_for_specific_supervisor_which_has_an_idea_for_tbl_view($supervisor_id){
		global $pdo;
		$get_grps_for_specific_supervisor_query = "SELECT * FROM groups,`idea_acceptance`,messages,attachments WHERE messages.messages_id=attachments.msg_id AND
		messages.group_id=idea_acceptance.group_id AND idea_acceptance.idea_status='accepted' AND groups.grp_id=idea_acceptance.group_id and
		groups.grp_id=messages.group_id AND 
		`idea_id` IS NOT NULL and idea_acceptance.group_id in (SELECT `group_id` FROM `supervision` WHERE sup_status='accepted' AND `teacher_id`=:teacher_id) 
		and groups.semester_id in (SELECT semester.auto_inc_id FROM semester where semester.active=1);";
		$get_grps_for_specific_supervisor_stmt = $pdo->prepare($get_grps_for_specific_supervisor_query);
		$get_grps_for_specific_supervisor_stmt->bindValue(':teacher_id',$supervisor_id,PDO::PARAM_STR);
		$get_grps_for_specific_supervisor_stmt->execute();
		$data = $get_grps_for_specific_supervisor_stmt->fetchAll();
		return $data;
		
		
	}
	public static function get_grps_for_specific_supervisor_and_group_which_has_an_idea_for_tbl_view($supervisor_id,$grp_id){
		global $pdo;
		$get_grps_for_specific_supervisor_query = "SELECT * FROM groups,`idea_acceptance`,messages,attachments WHERE messages.messages_id=attachments.msg_id AND
		messages.group_id=idea_acceptance.group_id AND idea_acceptance.idea_status='accepted' AND groups.grp_id=idea_acceptance.group_id and
		groups.grp_id=messages.group_id AND idea_acceptance.group_id=:grp_id AND
		`idea_id` IS NOT NULL and idea_acceptance.group_id in (SELECT `group_id` FROM `supervision` WHERE sup_status='accepted' AND `teacher_id`=:teacher_id) 
		and groups.semester_id in (SELECT semester.auto_inc_id FROM semester where semester.active=1);";
		$get_grps_for_specific_supervisor_stmt = $pdo->prepare($get_grps_for_specific_supervisor_query);
		$get_grps_for_specific_supervisor_stmt->bindValue(':teacher_id',$supervisor_id,PDO::PARAM_STR);
		$get_grps_for_specific_supervisor_stmt->bindValue(':grp_id',$grp_id,PDO::PARAM_INT);
		$get_grps_for_specific_supervisor_stmt->execute();
		$data = $get_grps_for_specific_supervisor_stmt->fetchAll();
		return $data;
		
		
	}
	 
		public static function get_all_grps_for_specific_supervisor($teacher_id){
		global $pdo;
		$get_all_grps_for_specific_supervisor_query = "SELECT *  FROM `groups`,`supervision`,group_members,teacher ,student
 WHERE student.id=owner and group_members.student_id=student.id AND  supervision.teacher_id=teacher.id AND
 supervision.sup_status='accepted' AND groups.grp_id=supervision.group_id AND teacher_id=:teacher_id AND group_members.group_id=groups.grp_id 
 AND group_members.group_id=supervision.group_id and groups.semester_id in (SELECT semester.auto_inc_id FROM semester where semester.active=1);";
$get_all_grps_for_specific_supervisor_stmt = $pdo->prepare($get_all_grps_for_specific_supervisor_query);
 $get_all_grps_for_specific_supervisor_stmt->bindValue(':teacher_id',$teacher_id,PDO::PARAM_STR);
  $get_all_grps_for_specific_supervisor_stmt->execute();
$data =   $get_all_grps_for_specific_supervisor_stmt->fetchAll();
return $data;  
	}
/* start res_depend_on_grp_weekly_file_status.php*/

        public static function update_status_into_reject_and_update_check_if_this_file_thesis_into_zero($status_weekly_file_satus,$group_id,$sender,
		$messages_text,$url_str,$to_which_msg_reply_id,$attachments_id,$is_this_thesis_file){
            
        global $pdo;
        $row1=0;
        $row2=0;
         try {
            $pdo->beginTransaction(); 
			  $updated_rowCount=self::change_pending_weekly_file_status_for_specific_grp($attachments_id,$status_weekly_file_satus);
             /*
            $updated_rowCount=self::change_pending_weekly_file_status_for_specific_grp($attachments_id,$status_weekly_file_satus);
            $insert_specific_msg_for_specif_sender_at_specific_grp_query = "INSERT INTO `messages`
 SET `group_id`=:group_id, `sender`=:sender,  `messages_text`=:messages_text,to_which_msg_id_lecture_reply=:to_which_msg_id_lecture_reply
 ,`is_this_thesis_file`=:is_this_thesis_file,`sending_time`=NOW();";
            $insert_specific_msg_for_specif_sender_at_specific_grp_stmt  = $pdo->prepare($insert_specific_msg_for_specif_sender_at_specific_grp_query);
            $insert_specific_msg_for_specif_sender_at_specific_grp_stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
            $insert_specific_msg_for_specif_sender_at_specific_grp_stmt->bindValue(':messages_text', $messages_text, PDO::PARAM_STR);
            $insert_specific_msg_for_specif_sender_at_specific_grp_stmt->bindValue(':sender', $sender, PDO::PARAM_STR);
			$insert_specific_msg_for_specif_sender_at_specific_grp_stmt->bindValue(':to_which_msg_id_lecture_reply',$to_which_msg_id_lecture_reply,PDO::PARAM_STR);
            $insert_specific_msg_for_specif_sender_at_specific_grp_stmt->bindValue(':is_this_thesis_file',$is_this_thesis_file,PDO::PARAM_INT);
            
			
			$insert_specific_msg_for_specif_sender_at_specific_grp_stmt->execute();
            $row1 = $insert_specific_msg_for_specif_sender_at_specific_grp_stmt->rowCount();
			 
            $last_insert_msg_id = $pdo->lastInsertId();
			//start 
			$insert_specific_attachment_for_specif_msg_at_specific_grp_query = "INSERT INTO `attachments` SET  `msg_id`=:msg_id,status='pending', `url_str`=:url_str;";
            $insert_specific_attachment_for_specif_msg_at_specific_grp_stmt  = $pdo->prepare($insert_specific_attachment_for_specif_msg_at_specific_grp_query);
            $insert_specific_attachment_for_specif_msg_at_specific_grp_stmt->bindValue(':msg_id', $last_insert_msg_id, PDO::PARAM_INT);
             
            $insert_specific_attachment_for_specif_msg_at_specific_grp_stmt->bindValue(':url_str', $url_str, PDO::PARAM_STR);
            $insert_specific_attachment_for_specif_msg_at_specific_grp_stmt->execute();
            $row2 = $insert_specific_attachment_for_specif_msg_at_specific_grp_stmt->rowCount();
			//end 
    $update_specific_attachment_status_for_specif_msg_at_specific_grp_query = "UPDATE `attachments` SET  status='accepted' WHERE `attachments_id`=:attachments_id;";
            $update_specific_attachment_status_for_specif_msg_at_specific_grp_stmt  = $pdo->prepare($update_specific_attachment_status_for_specif_msg_at_specific_grp_query);
            $update_specific_attachment_status_for_specif_msg_at_specific_grp_stmt->bindValue(':attachments_id', $attachments_id, PDO::PARAM_INT);
              
            $update_specific_attachment_status_for_specif_msg_at_specific_grp_stmt->execute();
            $row2 = $update_specific_attachment_status_for_specif_msg_at_specific_grp_stmt->rowCount();
			 *//*
            $get_is_this_thesis_file_query="SELECT is_this_thesis_file FROM `messages` WHERE messages_id=:messages_id;";
			$get_is_this_thesis_file_stmt = $pdo->prepare($get_is_this_thesis_file_query);
			$get_is_this_thesis_file_stmt->bindValue(':messages_id',$last_insert_msg_id,PDO::PARAM_INT);
			$get_is_this_thesis_file_stmt->execute();
			$messages_id=$get_is_this_thesis_file_stmt->fetchAll();
			$is_this_thesis_file=null;
			if($messages_id!=null){
				*/
			//$is_this_thesis_file=$messages_id[0]['is_this_thesis_file'];
             
				$update_thesis_query = "UPDATE `groups` SET `thesis`=NULL WHERE `grp_id`=:grp_id;";
				$update_thesis_stmt = $pdo->prepare($update_thesis_query);
				$update_thesis_stmt->bindValue(':grp_id',$group_id,PDO::PARAM_INT); 
				$update_thesis_stmt->execute();
			 	$row1=$update_thesis_stmt->rowCount();
			//}
			$pdo->commit();
        }
        catch (Exception $e) {
            if (isset($pdo)) {
                echo $e->getMessage();
                return 0;
                $pdo->rollback();
               $err = "ﻟﻢ ﺗﺘﻢ اﻟﻌﻤﻠﻴﺔ ﺑﻨﺠﺎﺡ";
            }
        }
        return $row1+$updated_rowCount;
        }
		public static function update_status_into_accepted_and_update_check_if_this_file_thesis_into_zero($status_weekly_file_satus,$grp_id,
	  $supervisor_login_id,$txt_msg,$dest_path,$to_which_msg_reply_id,$attachments_id,$is_this_thesis_file){
		
   
        global $pdo;
        $row1=0;
        $row2=0;
         try {
            $pdo->beginTransaction(); 
			  $updated_rowCount=self::change_pending_weekly_file_status_for_specific_grp($attachments_id,$status_weekly_file_satus);
              
             if($is_this_thesis_file==1){
				$update_thesis_query = "UPDATE `groups` SET `thesis`=NULL,thesis_submission_date=NULL WHERE `grp_id`=:grp_id;";
				$update_thesis_stmt = $pdo->prepare($update_thesis_query);
				$update_thesis_stmt->bindValue(':grp_id',$grp_id,PDO::PARAM_INT); 
				$update_thesis_stmt->execute();
			 	$row1=$update_thesis_stmt->rowCount();
			}
				
			//}
			$pdo->commit();
        }
        catch (Exception $e) {
            if (isset($pdo)) {
                echo $e->getMessage();
                return 0;
                $pdo->rollback();
               $err = "ﻟﻢ ﺗﺘﻢ اﻟﻌﻤﻠﻴﺔ ﺑﻨﺠﺎﺡ";
            }
        }
        return $row1+$updated_rowCount;
        		
		  
	  }
	  /* change_input_type_submit_depend_on_comm_disc_status.php */
	  public static function get_status_btn_groups_id_and_examiner_id($examiner_id,$groups_id){
		 $examination_accept_status="";  
		 global $pdo;
		 $get_status_btn_groups_id_and_examiner_id_query = "SELECT  `examination_accept_status` FROM `examination`
		 WHERE  examiner_id=".$pdo->quote($examiner_id)." AND groups_id=:groups_id;";
		  $get_status_btn_groups_id_and_examiner_id_stmt=$pdo->prepare($get_status_btn_groups_id_and_examiner_id_query);
		 $get_status_btn_groups_id_and_examiner_id_stmt->bindParam(':groups_id',$groups_id,PDO::PARAM_INT); 
		 $get_status_btn_groups_id_and_examiner_id_stmt->execute();
		 $data = $get_status_btn_groups_id_and_examiner_id_stmt->fetchAll();
		 if($data!=null){
			$examination_accept_status= $data[0]['examination_accept_status'];
		 }
		return $examination_accept_status;
		 
	  }
	  
	   public static function get_request_status_btn_examiner_and_grp($grp_id,$examiner_id){
		  global $pdo;
		  $examination_accept_status="";
		  $change_request_status_btn_examiner_and_grp_query = "SELECT examination_accept_status FROM `examination` 
			  WHERE `groups_id`=:groups_id AND `examiner_id`=".$pdo->quote($examiner_id);
		$change_request_status_btn_examiner_and_grp_stmt = $pdo->prepare($change_request_status_btn_examiner_and_grp_query);
		$change_request_status_btn_examiner_and_grp_stmt->bindParam(':groups_id',$grp_id,PDO::PARAM_INT);	
		$change_request_status_btn_examiner_and_grp_stmt->execute();
		$data = $change_request_status_btn_examiner_and_grp_stmt->fetchAll();
		if($data!=null){
		$examination_accept_status = $data[0]['examination_accept_status'];
		}
		
		return $examination_accept_status;
	  }
      public static function get_all_examiner_status_for_specific_grp_for_specific_supervisor($supervisor_id){
        global $pdo;
        $get_all_examiner_status_for_specific_grp_for_specific_supervisor_query = "SELECT * FROM `examination`,groups,teacher,users WHERE groups.grp_id=examination.groups_id AND ( `groups_id` in (select supervision.group_id FROM supervision WHERE supervision.teacher_id=".$pdo->quote($supervisor_id)." ) ) AND groups_id in (SELECT groups.grp_id FROM groups WHERE groups.semester_id IN (SELECT semester.auto_inc_id FROM semester WHERE semester.active=1)) AND teacher.id=examination.examiner_id AND users.usr_id=teacher.id";
$get_all_examiner_status_for_specific_grp_for_specific_supervisor_stmt = $pdo->query($get_all_examiner_status_for_specific_grp_for_specific_supervisor_query);

$get_all_examiner_status_for_specific_grp_for_specific_supervisor_stmt->execute();
return $get_all_examiner_status_for_specific_grp_for_specific_supervisor_stmt->fetchAll();



      }
	  public static function change_request_status_btn_examiner_and_grp($grp_id,$examiner_id,$examination_accept_status){
		  global $pdo;
		  $row=0;
		  try{ 
		  $change_request_status_btn_examiner_and_grp_query = "UPDATE `examination` SET 
			`examination_accept_status`=".$pdo->quote($examination_accept_status)." WHERE `examiner_id`=".$pdo->quote($examiner_id)." AND `groups_id`=:groups_id ";
		$change_request_status_btn_examiner_and_grp_stmt = $pdo->prepare($change_request_status_btn_examiner_and_grp_query);
		 
		$change_request_status_btn_examiner_and_grp_stmt->bindParam(':groups_id',$grp_id,PDO::PARAM_INT);	
		$change_request_status_btn_examiner_and_grp_stmt->execute();
		$row = $change_request_status_btn_examiner_and_grp_stmt->rowCount();
		  }catch(PDOException $ex){
			  echo $ex->getMessage();
		  }
		return $row;
	  }
	    public static function delete_request_btn_examiner_and_grp($grp_id,$examiner_id){
		  global $pdo;
		  $row=0;
		  $delete_request_btn_examiner_and_grp_query = "DELETE FROM `examination` WHERE `groups_id`=:groups_id AND`examiner_id`=".$pdo->quote($examiner_id);
		$delete_request_btn_examiner_and_grp_stmt = $pdo->prepare($delete_request_btn_examiner_and_grp_query);
		$delete_request_btn_examiner_and_grp_stmt->bindParam(':groups_id',$grp_id,PDO::PARAM_INT);	
		$delete_request_btn_examiner_and_grp_stmt->execute();
		$row = $delete_request_btn_examiner_and_grp_stmt->rowCount();
		return $row;
	  }
	  
	  public static function insert_new_request_status_btn_examiner_and_grp($grp_id,$examiner_id,$examination_accept_status){
		  global $pdo;
		  $row=0;
		  $insert_new_request_status_btn_examiner_and_grp_query = "INSERT INTO `examination` SET 
			`examination_accept_status`=".$pdo->quote($examination_accept_status)." , `groups_id`=:groups_id,`examiner_id`=".$pdo->quote($examiner_id);
		$insert_new_request_status_btn_examiner_and_grp_stmt = $pdo->prepare($insert_new_request_status_btn_examiner_and_grp_query);
		$insert_new_request_status_btn_examiner_and_grp_stmt->bindParam(':groups_id',$grp_id,PDO::PARAM_INT);	
		$insert_new_request_status_btn_examiner_and_grp_stmt->execute();
		$row = $insert_new_request_status_btn_examiner_and_grp_stmt->rowCount();
		return $row;
	  }
	  public static function get_accepted_status_rowCount_of_selected_grp_to_prevent_exceed_maximum_no_of_examinar_for_this_grp($grp_id){
		global $pdo;
		$status="accepted";
		$row=0;
		$examination_accept_status="accepted";
		$examination_accept_status2="pending";
		
		$get_accepted_status_rowCount_of_selected_grp_to_prevent_exceed_maximum_no_of_examinar_for_this_grp_query = 
		"SELECT COUNT(*) AS counter FROM `examination` WHERE `groups_id`=:groups_id  AND (`examination_accept_status`=".$pdo->quote($examination_accept_status)." OR `examination_accept_status`=".$pdo->quote($examination_accept_status2)."); ";
	 $get_accepted_status_rowCount_of_selected_grp_to_prevent_exceed_maximum_no_of_examinar_for_this_grp_stmt = $pdo->prepare($get_accepted_status_rowCount_of_selected_grp_to_prevent_exceed_maximum_no_of_examinar_for_this_grp_query);
$get_accepted_status_rowCount_of_selected_grp_to_prevent_exceed_maximum_no_of_examinar_for_this_grp_stmt->bindParam(':groups_id',$grp_id,PDO::PARAM_INT);
$get_accepted_status_rowCount_of_selected_grp_to_prevent_exceed_maximum_no_of_examinar_for_this_grp_stmt->execute();
$data = $get_accepted_status_rowCount_of_selected_grp_to_prevent_exceed_maximum_no_of_examinar_for_this_grp_stmt->fetchAll();
if($data!=null){
	$row = $data[0]['counter'];
}
return  $row;
	 }
	 /* follow_up_join_into_examiner_request.php */
	 public static function check_if_this_examiner_has_a_request($examinar_id){
		 global $pdo; 
		 $check_if_this_examiner_has_a_request_query = "SELECT `groups_id`, `grp_name`,`thesis`, `examiner_id`, 
		 `examination_accept_status` FROM `examination`,
		 groups WHERE groups_id= grp_id and examiner_id=".$pdo->quote($examinar_id).";";
		 
		 
			$check_if_this_examiner_has_a_request_stmt = $pdo->query($check_if_this_examiner_has_a_request_query);   
			$check_if_this_examiner_has_a_request_stmt->execute();
			$arr_groups_id_for_specific_examinar = $check_if_this_examiner_has_a_request_stmt->fetchAll();
			return $arr_groups_id_for_specific_examinar;
		 
	 }
	 public static function check_if_this_grp_has_a_thesis($groups_id){
		 global $pdo;
		 $counter=0;
		 $check_if_this_grp_has_a_thesis_query="SELECT COUNT(*) AS counter FROM `groups` WHERE thesis is not null AND grp_id=:groups_id;";
		 $check_if_this_grp_has_a_thesis_stmt = $pdo->prepare($check_if_this_grp_has_a_thesis_query);
		 $check_if_this_grp_has_a_thesis_stmt->bindParam(':groups_id',$groups_id,PDO::PARAM_INT);
		 $check_if_this_grp_has_a_thesis_stmt->execute();
		 $data = $check_if_this_grp_has_a_thesis_stmt->fetchAll();
		 if($data!=null){
			$counter = $data[0]['counter'];
		 }
		 return $counter;
	 }
	 /* see_my_examinar */
	 public static function check_if_this_grp_has_examinar($grp_id){
		global $pdo;
		$check_if_this_grp_has_examinar_query = "SELECT * FROM `teacher`,users,examination WHERE examination.examiner_id=teacher.id 
		and teacher.id=users.usr_id and examination.groups_id=:group_id and `examination_accept_status`='accepted';"; 	
		$check_if_this_grp_has_examinar_stmt = $pdo->prepare($check_if_this_grp_has_examinar_query);
		$check_if_this_grp_has_examinar_stmt->bindParam(':group_id',$grp_id,PDO::PARAM_INT);
		$check_if_this_grp_has_examinar_stmt->execute();
		$data = $check_if_this_grp_has_examinar_stmt->fetchAll();
		return $data;
	 }
	 /* set_examination_status_for_specific_grp.php */
	  
	 public static function get_thesis_and_prject_idea_and_supervisor_for_accepted_grp_examiner($examiner_id){
		 global $pdo;
	$get_thesis_and_prject_idea_and_supervisor_for_accepted_grp_examiner_query="
	SELECT examination_status,grp_id,grp_name,idea_name,description,thesis,examination_accept_status  FROM
	`groups`,`idea_acceptance`,`supervision`,`ideas_of_project`,examination 
	WHERE `groups`.`grp_id`=`idea_acceptance`.`group_id`
	AND `supervision`.`group_id`=`groups`.`grp_id` AND 
	examination.examiner_id=".$pdo->quote($examiner_id)." AND
    examination.groups_id=groups.grp_id AND
	`ideas_of_project`.`id`=`idea_acceptance`.`idea_id` AND
	`supervision`.`teacher_id` is not null 
	AND thesis is not null AND idea_status='accepted' AND sup_status='accepted'  ;";
	$get_thesis_and_prject_idea_and_supervisor_for_accepted_grp_examiner_stmt = $pdo->query($get_thesis_and_prject_idea_and_supervisor_for_accepted_grp_examiner_query);
	 $get_thesis_and_prject_idea_and_supervisor_for_accepted_grp_examiner_stmt->execute();
	 $data = $get_thesis_and_prject_idea_and_supervisor_for_accepted_grp_examiner_stmt->fetchAll();
	 return $data ;
	 }
	 /* edit_request_btn_examiner_and_grps.php */
	 public static function get_all_teachers_except_supervisor_of_this_grp($grp_id){
		 global $pdo; 
         /* start */
         $get_all_teachers_except_supervisor_of_this_grp_query = 
        "SELECT  users.usr_id,concat(fname,' ',lname) as name FROM  users 
        WHERE  role=2 and users.usr_id not in (SELECT `teacher_id`  FROM `supervision`  
        WHERE  `group_id`=:group_id AND  sup_status='accepted');";
     $get_all_teachers_except_supervisor_of_this_grp_stmt = $pdo->prepare($get_all_teachers_except_supervisor_of_this_grp_query);
     $get_all_teachers_except_supervisor_of_this_grp_stmt->bindParam(':group_id',$grp_id,PDO::PARAM_INT);
     $get_all_teachers_except_supervisor_of_this_grp_stmt->execute();
     $data = $get_all_teachers_except_supervisor_of_this_grp_stmt->fetchAll();
       /* end */
      return $data;
	 
	 }
	 public static function get_all_hall_for_active_semester(){
		 global $pdo;
		$get_all_hall_for_active_semester_query = "SELECT `room_id`, `room_description`, `semesters_id_ref` FROM `rooms` 
		WHERE semesters_id_ref in (select semester.auto_inc_id from semester where semester.active=1);";
		$get_all_hall_for_active_semester_stmt = $pdo->query($get_all_hall_for_active_semester_query);
		$get_all_hall_for_active_semester_stmt->execute();
		$data = $get_all_hall_for_active_semester_stmt->fetchAll();
		return $data;
	 } 
	 public static function check_if_this_hall_available_in_this_semester($room_id ,$semester_id){
	 global $pdo;
		$get_all_hall_for_active_semester_query = "SELECT semesters_id_ref,room_id,room_description  FROM `rooms` 
		WHERE   room_id=".$pdo->quote($room_id)." and semesters_id_ref =:semester_id;";
        
		$get_all_hall_for_active_semester_stmt = $pdo->prepare($get_all_hall_for_active_semester_query);
		$get_all_hall_for_active_semester_stmt->bindParam(':semester_id',$semester_id,PDO::PARAM_INT); 
		
		$get_all_hall_for_active_semester_stmt->execute();
		$data = $get_all_hall_for_active_semester_stmt->fetchAll();
		 
		return $data;
	
	 }
	 public static function del_this_hall_available_in_this_semester($room_id,$semester_id){
		global $pdo;
		$deleted_row_count=0;
$del_this_hall_available_in_this_semester_query="DELETE FROM `rooms` WHERE room_id=".$pdo->quote($room_id)." and semesters_id_ref=:semester_id";
$del_this_hall_available_in_this_semester_stmt=$pdo->prepare($del_this_hall_available_in_this_semester_query);
$del_this_hall_available_in_this_semester_stmt->bindParam(':semester_id',$semester_id,PDO::PARAM_INT);
$del_this_hall_available_in_this_semester_stmt->execute();
$deleted_row_count=$del_this_hall_available_in_this_semester_stmt->rowCount();


return $deleted_row_count;		
	 }
	 public static function insert_new_hall_no($room_number,$active_semester){
		global $pdo;
		$row_count=0;
		try{
			$insert_new_hall_no_query = "INSERT INTO `rooms` SET room_description=".$pdo->quote($room_number).",
semesters_id_ref =:active_semester
			";
		$insert_new_hall_no_stmt = $pdo->prepare($insert_new_hall_no_query);
		$insert_new_hall_no_stmt->bindParam(':active_semester',$active_semester,PDO::PARAM_INT);
		$insert_new_hall_no_stmt->execute();
		$row_count = $insert_new_hall_no_stmt->rowCount();
		return $row_count;
		}
		catch(PDOException $ex){
			echo $ex->getMessage();
			echo 'لم تتم العملية بنجاح لربما لم يتم تفعيل الفصل الدراسي بعد';
		}
		return $row_count;
	 }
	 public static function update_specific_hall_no($old_hall_no_value,$new_hall_no_value,$active_semester){
		 global $pdo;
		 $updated_row_count=0;
		 $update_specific_hall_no_query = "UPDATE rooms  SET room_description=".$pdo->quote($new_hall_no_value)."
		 WHERE room_id=".$pdo->quote($old_hall_no_value)." and semesters_id_ref=:semester_id;
		 ";
		 $update_specific_hall_no_stmt = $pdo->prepare($update_specific_hall_no_query);
		$update_specific_hall_no_stmt->bindParam(':semester_id',$active_semester,PDO::PARAM_INT); 
		return $update_specific_hall_no_stmt->execute();
		 
	 }
	 /* determine_the_times_allowed_for_students_to_be_discussed_during_the_day.php */
	 public static function get_all_suggested_time_for_this_semester(){
		 global $pdo;
		 $get_all_suggested_time_for_this_semester_query="SELECT time.id,from_time,to_time,year_val,sem_name,sem_id_no FROM `time`,`semester`, semester_names  
		 WHERE  time.sem_id_no=semester.auto_inc_id AND semester.semester_id=semester_names.id and
		 sem_id_no in (SELECT semester.auto_inc_id from semester where semester.active=1); ";
		 $get_all_suggested_time_for_this_semester_stmt = $pdo->query($get_all_suggested_time_for_this_semester_query);
		  $get_all_suggested_time_for_this_semester_stmt->execute();
		  $data = $get_all_suggested_time_for_this_semester_stmt->fetchAll();
		 return $data;
	 }
	 public static function check_if_this_time_in_db($t_id){
		 global $pdo;
		 
		 $check_if_this_time_in_db_query="SELECT * FROM `time`,`semester`  
		 WHERE id=:t_id AND time.sem_id_no=semester.auto_inc_id AND
		 sem_id_no in (SELECT semester.auto_inc_id from semester where semester.active=1);";
		 $check_if_this_time_in_db_stmt = $pdo->prepare($check_if_this_time_in_db_query);
		 $check_if_this_time_in_db_stmt->bindParam(':t_id',$t_id,PDO::PARAM_INT);
		 $check_if_this_time_in_db_stmt->execute();
		 $data = $check_if_this_time_in_db_stmt->fetchAll();
		  
		 return  $data;
		 
	 }
	 
	 public static function check_timeOverLapping_for_time_tbl_in_active_semester($from_time, $to_time)
    {
        global $pdo;
		/*
        $check_timeOverLapping_query = " SELECT  TIME_FORMAT(`from_time`,'%H:%m') as from_time ,
		TIME_FORMAT(`to_time`,'%H:%m') as  to_time  FROM `time`,semester,semester_names  WHERE
 (
('$from_time' BETWEEN from_time AND to_time) OR
('$to_time' BETWEEN from_time AND to_time) OR
(from_time < '$from_time' AND to_time > '$to_time')) AND
 semester.auto_inc_id=sem_id_no and semester.semester_id=semester_names.id
 and sem_id_no=(select semester.auto_inc_id from semester where active =1 ) 
   ;";
   */
    $check_timeOverLapping_query = " SELECT  cast(`from_time` as time) as from_time , cast(`to_time` as time) as to_time FROM `time`,semester,semester_names  WHERE
 (
(".$pdo->quote($from_time)." BETWEEN from_time AND to_time) OR
(".$pdo->quote($to_time)." BETWEEN from_time AND to_time) OR
(from_time < ".$pdo->quote($from_time)." AND to_time > ".$pdo->quote($to_time).")) AND
 semester.auto_inc_id=sem_id_no and semester.semester_id=semester_names.id
 and sem_id_no=(select semester.auto_inc_id from semester where active =1 ) 
   ;";
        $check_timeOverLapping_stmt  = $pdo->query($check_timeOverLapping_query); 
        $check_timeOverLapping_stmt->execute();
        $rowData = $check_timeOverLapping_stmt->fetchAll();
	 
        return $rowData;
        //if $row=0 => time input by user is true ,else if $row>0 => time input by user is false
    }
		 public static function check_timeOverLapping_for_time_tbl_in_active_semester_except_selection_time($from_time, $to_time,$time_id)
    {
        global $pdo;
		
        $check_timeOverLapping_query = " SELECT  TIME_FORMAT(`from_time`,'%H:%m') as from_time ,
		TIME_FORMAT(`to_time`,'%H:%m') as  to_time  FROM time,semester,semester_names  WHERE
 (
(".$pdo->quote($from_time)." BETWEEN from_time AND to_time) OR
(".$pdo->quote($to_time)." BETWEEN from_time AND to_time) OR
(from_time < ".$pdo->quote($from_time)." AND to_time > ".$pdo->quote($to_time).")) AND
`time`.id!=:id
AND
 semester.auto_inc_id=sem_id_no and semester.semester_id=semester_names.id
 and sem_id_no=(select semester.auto_inc_id from semester where active =1 ) 
   ;";
        $check_timeOverLapping_stmt  = $pdo->prepare($check_timeOverLapping_query); 
		$check_timeOverLapping_stmt->bindParam(':id',$time_id,PDO::PARAM_INT);
        $check_timeOverLapping_stmt->execute();
        $rowData = $check_timeOverLapping_stmt->fetchAll();
	 
        return $rowData;
        //if $row=0 => time input by user is true ,else if $row>0 => time input by user is false
    }
	public static function delete_specific_date($t_id){
		global $pdo;
		$delete_specific_date_query="DELETE FROM time WHERE id=:t_id;";
		$delete_specific_date_stmt = $pdo->prepare($delete_specific_date_query);
		$delete_specific_date_stmt->bindParam(':t_id',$t_id,PDO::PARAM_INT);
		$delete_specific_date_stmt->execute();
		$rowCount = $delete_specific_date_stmt->rowCount();
		return $rowCount;
		
	}
	public static function insert_new_suggested_time($from_time,$to_time,$auto_inc_id){
		global $pdo;
		$insert_new_suggested_time_query = "INSERT INTO `time`
SET   `from_time`=".$pdo->quote($from_time)." ,`to_time`=".$pdo->quote($to_time).", `sem_id_no`=:sem_id_no;";
		$insert_new_suggested_time_stmt = $pdo->prepare($insert_new_suggested_time_query);
		$insert_new_suggested_time_stmt->bindParam(':sem_id_no',$auto_inc_id,PDO::PARAM_INT);
		$insert_new_suggested_time_stmt->execute();
		$inserted_row = $insert_new_suggested_time_stmt->rowCount();
		return $inserted_row;
	}
	public static function update_exist_suggested_time($from_time,$to_time,$auto_inc_id,$time_id){
		global $pdo;
		$update_exist_suggested_time_query = "UPDATE `time`
SET   `from_time`=".$pdo->quote($from_time)." ,`to_time`=".$pdo->quote($to_time).", `sem_id_no`=:sem_id_no WHERE id=:id;";
		$update_exist_suggested_time_stmt = $pdo->prepare($update_exist_suggested_time_query);
		$update_exist_suggested_time_stmt->bindParam(':id',$time_id,PDO::PARAM_INT);
		$update_exist_suggested_time_stmt->bindParam(':sem_id_no',$auto_inc_id,PDO::PARAM_INT);
		
		$update_res_complete_op = $update_exist_suggested_time_stmt->execute();
		 return $update_res_complete_op;
		
	}
	/* suggest_a_date_for_my_discission.php */
public static function check_if_this_user_grp_accept_in_examination($grp_id){
	global $pdo;
	$rowCount=0;
	$examination_accept_status="accepted";
	$check_if_this_user_grp_accept_in_examination_query = "SELECT COUNT(*) as counter FROM `examination` 
	WHERE `groups_id`=:groups_id and `examination_accept_status`=".$pdo->quote($examination_accept_status);
	$check_if_this_user_grp_accept_in_examination_stmt = $pdo->prepare($check_if_this_user_grp_accept_in_examination_query );
	$check_if_this_user_grp_accept_in_examination_stmt->bindParam(':groups_id',$grp_id,PDO::PARAM_INT);
	$check_if_this_user_grp_accept_in_examination_stmt->execute();
	$data = $check_if_this_user_grp_accept_in_examination_stmt->fetchAll();
	if($data!=null){
		$rowCount = $data[0]['counter'];
	}
	return $rowCount;
}
public static function get_grp_time_status($usr_grp){
	global $pdo;
	$get_grp_time_status_query = "SELECT  `room_id_fk`, `time_id`, `date_val`, `status` FROM `group_time`
	WHERE `g_id`=:grp_id;";
	$get_grp_time_status_stmt = $pdo->prepare($get_grp_time_status_query);
	$get_grp_time_status_stmt->bindParam(':grp_id',$usr_grp,PDO::PARAM_INT);
	$get_grp_time_status_stmt->execute();
	$data = $get_grp_time_status_stmt->fetchAll();
	
	return $data;
}
public static function check_if_grp_time_status_is_accepted($usr_grp){
	global $pdo; 
	$get_grp_time_status_query = "SELECT `room_id_fk`,from_time,to_time, `time_id`, `date_val`, `status` 
	FROM `group_time`,`time` 
WHERE `time`.`id`=time_id and status='accepted' and group_time.g_id=:grp_id   ;";
	$get_grp_time_status_stmt = $pdo->prepare($get_grp_time_status_query);
	$get_grp_time_status_stmt->bindParam(':grp_id',$usr_grp,PDO::PARAM_INT);
	$get_grp_time_status_stmt->execute();
	$data = $get_grp_time_status_stmt->fetchAll();
	return $data;
}
public static function check_overlapping_for_group_time($from_time_and_date,$to_time_and_date,$hall_name){
	
	global $pdo;
 //compare overlapping with stats=accepted

$check_overlapping_for_group_time_query = "
SELECT cast(concat(group_time.date_val,' ',`time`.from_time) as datetime) as start_time, 
cast(concat(group_time.date_val,' ',`time`.to_time) as datetime) as end_time 
FROM `group_time`,`time` WHERE
room_id_fk=".$pdo->quote($hall_name)." and
 status='accepted' and `time`.`id`=group_time.time_id and ( (cast(".$pdo->quote($from_time_and_date)." as datetime) 
BETWEEN (cast(concat(group_time.date_val,' ',`time`.from_time) as datetime)) AND
 cast(concat(group_time.date_val,' ',`time`.to_time) as datetime)) OR (cast(".$pdo->quote($to_time_and_date)." as datetime) 
 BETWEEN (cast(concat(group_time.date_val,' ',`time`.from_time) as datetime)) 
 AND cast(concat(group_time.date_val,' ',`time`.to_time) as datetime)) 
 OR ((cast(concat(group_time.date_val,' ',`time`.from_time) as datetime)) <cast(".$pdo->quote($from_time_and_date)." as datetime) 
AND cast(concat(group_time.date_val,' ',`time`.to_time) as datetime) > cast(".$pdo->quote($to_time_and_date)." as datetime)) )
";
$check_overlapping_for_group_time_stmt = $pdo->prepare($check_overlapping_for_group_time_query);

$check_overlapping_for_group_time_stmt->execute();

$data = $check_overlapping_for_group_time_stmt->fetchAll();
return $data;
}
public static function get_times(){
	global $pdo;
	$get_times_query = "SELECT `id`,  `from_time` , `to_time`  FROM `time` WHERE 
	`sem_id_no` in (SELECT semester.auto_inc_id FROM semester WHERE semester.active=1);";
	$get_times_stmt = $pdo->query($get_times_query);
	$get_times_stmt->execute();
	$data = $get_times_stmt->fetchAll();
	return $data ;
}
public static function get_rooms(){
	global $pdo;
	 
	$get_rooms_query = "SELECT *  FROM `rooms` WHERE 
	`semesters_id_ref` in (SELECT semester.auto_inc_id FROM semester WHERE semester.active=1);";
	$get_rooms_stmt = $pdo->query($get_rooms_query);
	$get_rooms_stmt->execute();
	$data = $get_rooms_stmt->fetchAll();
	return $data ;
}
public static function get_all_time_of_this_grp($g_id,$user_id){
	global $pdo;
	$get_all_time_of_this_grp_query = "SELECT * FROM `group_time`,`time`,rooms WHERE
	g_id=:g_id and `time`.id=group_time.time_id and proposer_of_date=".$pdo->quote($user_id)." and `room_id_fk`=rooms.room_id ;";
	$get_all_time_of_this_grp_stmt = $pdo->prepare($get_all_time_of_this_grp_query );
	$get_all_time_of_this_grp_stmt->bindParam(':g_id',$g_id,PDO::PARAM_INT);
	$get_all_time_of_this_grp_stmt->execute();
	$data = $get_all_time_of_this_grp_stmt->fetchAll();	
	return $data;
}
public static function get_specific_info_about_specific_time($time_id){
	global $pdo;
	$get_specific_info_about_specific_time_query = "
	SELECT `id`, `from_time`, 
	`to_time`, `sem_id_no` FROM `time` 
	WHERE `id`=:id;";
	$get_specific_info_about_specific_time_stmt = $pdo->prepare($get_specific_info_about_specific_time_query);
	$get_specific_info_about_specific_time_stmt->bindParam(':id',$time_id,PDO::PARAM_INT);
	$get_specific_info_about_specific_time_stmt->execute();
	$data = $get_specific_info_about_specific_time_stmt->fetchAll();
	return $data;
	
}
public static function insert_into_group_time($usr_grp,$time_val,$date_value,$hall_name,$user_id,$status,$semester_no){
	global $pdo;
	$status="pending";
	$insert_into_group_time_query = "
INSERT INTO `group_time` SET
`g_id`=:g_id, 
`room_id_fk`=".$pdo->quote($hall_name).",
`time_id`=".$pdo->quote($time_val).",
`date_val`=".$pdo->quote($date_value).", 
`status`=".$pdo->quote($status).", 
semester_id=:semester_no,
`proposer_of_date`=".$pdo->quote($user_id);

	$insert_into_group_time_stmt = $pdo->prepare($insert_into_group_time_query);
	$insert_into_group_time_stmt->bindParam(':g_id',$usr_grp,PDO::PARAM_INT);
	$insert_into_group_time_stmt->bindParam(':semester_no',$semester_no,PDO::PARAM_INT);
	
	$insert_into_group_time_stmt->execute();
	$rowCount = $insert_into_group_time_stmt->rowCount();
	return $rowCount;
}
public static function check_if_this_grp_has_accepted_status_in_this_semester($usr_grp,$semester_id){
global $pdo;
$rowCount=0;

$check_if_this_grp_has_accepted_status_query = "
SELECT * FROM `group_time`,`time` WHERE
 `time_id`=`time`.`id` and `semester_id`=:semester_id 
 and g_id=:g_id and status ='accepted';";	
$check_if_this_grp_has_accepted_status_stmt = $pdo->prepare($check_if_this_grp_has_accepted_status_query);	
$check_if_this_grp_has_accepted_status_stmt->bindParam(':g_id',$usr_grp,PDO::PARAM_INT);	
$check_if_this_grp_has_accepted_status_stmt->bindParam(':semester_id',$semester_id,PDO::PARAM_INT);	
$check_if_this_grp_has_accepted_status_stmt->execute();
$data = $check_if_this_grp_has_accepted_status_stmt->fetchAll();
 
 return $data ;

}
public static function change_reject_or_accepted_into_specific_status( $semester_no1,$grp_id,$time_id,$date_id,$room_id,$status){
	global $pdo; 
	$change_reject_status_into_accepted_query = "
	UPDATE `group_time` SET 
`status`=".$pdo->quote($status)."
WHERE

`g_id`=:g_id
AND
`room_id_fk`=".$pdo->quote($room_id)."
AND
`time_id`=:time_id
AND
`date_val`=".$pdo->quote($date_id)."
AND
`semester_id`=:semester_id;";
	$change_reject_status_into_accepted_stmt = $pdo->prepare($change_reject_status_into_accepted_query);
	$change_reject_status_into_accepted_stmt->bindParam(':g_id',$grp_id,PDO::PARAM_INT);
	$change_reject_status_into_accepted_stmt->bindParam(':time_id',$time_id,PDO::PARAM_INT);
	$change_reject_status_into_accepted_stmt->bindParam(':semester_id',$semester_no1,PDO::PARAM_INT);
	
	$change_reject_status_into_accepted_stmt->execute(); 
	return $change_reject_status_into_accepted_stmt->rowCount();
	
}
public static function delete_pending_status($semester_no1,$grp_id,$time_id,$date_id,$room_id){
	 

	global $pdo;
	  
	$delete_pending_status_query = "
	DELETE FROM `group_time`WHERE 

`g_id`=:g_id
AND
`room_id_fk`=".$pdo->quote($room_id)."
AND
`time_id`=:time_id
AND
`date_val`=".$pdo->quote($date_id)."
AND
`semester_id`=:semester_id;";
	$delete_pending_status_stmt = $pdo->prepare($delete_pending_status_query );
	$delete_pending_status_stmt->bindParam(':g_id',$grp_id,PDO::PARAM_INT);
	$delete_pending_status_stmt->bindParam(':time_id',$time_id,PDO::PARAM_INT);
	$delete_pending_status_stmt->bindParam(':semester_id',$semester_no1,PDO::PARAM_INT);
	
	$delete_pending_status_stmt->execute(); 
	return $delete_pending_status_stmt->rowCount();	
	
	
}
/* accept_of_grp_examination_time.php */
public static function get_all_time_grps_of_this_semester($semester_id){
	global $pdo;
	$get_all_time_grps_of_this_semester_query = "
	SELECT * FROM `group_time`,semester,`time`,groups WHERE 
groups.semester_id=semester.auto_inc_id and
`time`.`sem_id_no`=semester.auto_inc_id  and
group_time.semester_id in 
	(SELECT semester.auto_inc_id FROM semester WHERE semester.active=1) 
 and  group_time.semester_id = semester.auto_inc_id
	AND `time`.`id`=group_time.time_id AND groups.grp_id=group_time.g_id;";
	$get_all_time_grps_of_this_semester_stmt = $pdo->query($get_all_time_grps_of_this_semester_query);
	$get_all_time_grps_of_this_semester_stmt->execute();
	$data =$get_all_time_grps_of_this_semester_stmt->fetchAll();
	return $data;
}
public static function change_previous_accepted_into_pending($semester_no1,$grp_id,$change_status){
	global $pdo;
	$change_previous_accepted_into_pending_query = "
	UPDATE `group_time` SET status='pending' WHERE 
 `g_id`=:g_id and `semester_id`=:semester_id and status='accepted';";
 $change_previous_accepted_into_pending_stmt = $pdo->prepare($change_previous_accepted_into_pending_query);
	 $change_previous_accepted_into_pending_stmt->bindParam(':g_id',$grp_id,PDO::PARAM_INT);
	 $change_previous_accepted_into_pending_stmt->bindParam(':semester_id',$semester_no1,PDO::PARAM_INT);
	 $change_previous_accepted_into_pending_stmt->execute();
	 
	 
	
}
 public static function change_previous_accepted_into_pending_and_edit_reject_suggested_examination_date_into_accepted( 
		$semester_no1,$grp_id,$time_id,$date_id,$room_id){
			
			global $pdo;
      
        try {
            $pdo->beginTransaction();
          self::change_previous_accepted_into_pending($semester_no1,$grp_id,"pending");
		 self::change_reject_or_accepted_into_specific_status( $semester_no1,$grp_id,$time_id,$date_id,$room_id,"accepted");
            $pdo->commit();
        }
        catch (Exception $e) {
            if (isset($pdo)) {
                return false;
                $pdo->rollback();
               $err = "ﻟﻢ ﺗﺘﻢ اﻟﻌﻤﻠﻴﺔ ﺑﻨﺠﺎﺡ";
            }
        }
        return true;
			
		}
		/* fill_main_grade_creteria.php */
		public static function get_all_key_creteria(){
			global $pdo;
			$get_all_key_creteria_query = "SELECT `key_criteria_id`, 
			`criteria_name`, `top_end_of_mak`, `low_end_of_mak`, `sem_val` FROM `key_criteria_for_marks` WHERE 
sem_val IN (SELECT semester.auto_inc_id FROM semester WHERE semester.active=1);";
	        $get_all_key_creteria_stmt = $pdo->query($get_all_key_creteria_query); 
			$get_all_key_creteria_stmt->execute();
			$data = $get_all_key_creteria_stmt->fetchAll();
			return $data;
			
			
		}
		
		public static function check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester($val){
				global $pdo;
				 $sumation =0;
		$check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester_query = "
		SELECT (SUM(`top_end_of_mak`)+:val) as sumation FROM `key_criteria_for_marks` 
	WHERE `sem_val` 
			in(SELECT semester.auto_inc_id FROM semester WHERE semester.active=1);";
			$check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester_stmt = $pdo->prepare($check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester_query );
			$check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester_stmt->bindParam(':val',$val,PDO::PARAM_INT);
			$check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester_stmt->execute();
			$data = $check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester_stmt->fetchAll();
			if($data!=null){$sumation = $data[0]['sumation'];}
			
			return $sumation;
				 
		}
		
		
		public static function check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester_in_edit_part($creteria_id,$val){
				global $pdo;
				 $sumation =0;
				 $get_max_mark_of_specific_creteria=self::check_if_this_creteria_in_db($creteria_id);
				 $top_end_of_mak=$get_max_mark_of_specific_creteria[0]['top_end_of_mak'];
		$check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester_query = "
		SELECT (SUM(`top_end_of_mak`)+:val-:top_end_of_mak) as sumation FROM `key_criteria_for_marks` 
	WHERE `sem_val` 
			in(SELECT semester.auto_inc_id FROM semester WHERE semester.active=1);";
			$check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester_stmt = $pdo->prepare($check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester_query );
			$check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester_stmt->bindParam(':val',$val,PDO::PARAM_INT);
			$check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester_stmt->bindParam(':top_end_of_mak',$top_end_of_mak,PDO::PARAM_INT);
			$check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester_stmt->execute();
			$data = $check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester_stmt->fetchAll();
			if($data!=null){$sumation = $data[0]['sumation'];}
			
			return $sumation;
				 
		}
		
		public static function add_grade_to_a_new_creteria($creteria_name,$max_mark,$min_mark){
			global $pdo;
			$add_grade_to_a_new_creteria_query = "INSERT INTO `key_criteria_for_marks`
SET  `criteria_name`=".$pdo->quote($creteria_name)." ,`top_end_of_mak`=:max_mark, 
`low_end_of_mak`=:min_mark, `sem_val`=(SELECT semester.auto_inc_id
                                       FROM semester WHERE semester.active=1); ";
			$add_grade_to_a_new_creteria_stmt = $pdo->prepare($add_grade_to_a_new_creteria_query);
			
			$add_grade_to_a_new_creteria_stmt->bindParam(':max_mark',$max_mark,PDO::PARAM_INT);
			$add_grade_to_a_new_creteria_stmt->bindParam(':min_mark',$min_mark,PDO::PARAM_INT);
			$add_grade_to_a_new_creteria_stmt->execute();
			return $add_grade_to_a_new_creteria_stmt->rowCount();
			
		}
		public static function check_if_this_creteria_in_db($criteria_id){
			global $pdo;
			$check_if_this_creteria_in_db_query = "SELECT  `criteria_name`, `top_end_of_mak`, 
			`low_end_of_mak`, `sem_val` FROM `key_criteria_for_marks` WHERE `key_criteria_id`=:key_criteria_id;";
			$check_if_this_creteria_in_db_stmt = $pdo->prepare($check_if_this_creteria_in_db_query);
			$check_if_this_creteria_in_db_stmt->bindParam(':key_criteria_id',$criteria_id,PDO::PARAM_INT);
			$check_if_this_creteria_in_db_stmt->execute();
			$data = $check_if_this_creteria_in_db_stmt->fetchAll();
			return $data;
		}
		public static function update_creteria_depend_on_old_creteria($creteria_id,$creteria_name,$max_mark,$min_mark){
			try{
			global $pdo;
			$update_creteria_depend_on_old_creteria_query="UPDATE `key_criteria_for_marks`
SET `criteria_name`=".$pdo->quote($creteria_name)." ,`top_end_of_mak`=:max_mark, 
`low_end_of_mak`=:min_mark, `sem_val`=(SELECT semester.auto_inc_id
                                       FROM semester WHERE semester.active=1) WHERE key_criteria_id=:creteria_id ;";
			$update_creteria_depend_on_old_creteria_stmt = $pdo->prepare($update_creteria_depend_on_old_creteria_query);
			$update_creteria_depend_on_old_creteria_stmt->bindParam(':creteria_id',$creteria_id,PDO::PARAM_INT);
			$update_creteria_depend_on_old_creteria_stmt->bindParam(':max_mark',$max_mark,PDO::PARAM_INT);
			$update_creteria_depend_on_old_creteria_stmt->bindParam(':min_mark',$min_mark,PDO::PARAM_INT);
			
			return $update_creteria_depend_on_old_creteria_stmt->execute();
		}
		catch(PDOException $ex){
			echo $ex->getMessage();
			
		}	
		}
		public static function delete_specific_creteria($creteria_id){
			global $pdo;
			$delete_specific_creteria_query = "
			DELETE FROM `key_criteria_for_marks` WHERE `key_criteria_id`=:key_criteria_id;";
			$delete_specific_creteria_stmt = $pdo->prepare($delete_specific_creteria_query);
			$delete_specific_creteria_stmt->bindParam(':key_criteria_id',$creteria_id,PDO::PARAM_INT);
			$delete_specific_creteria_stmt->execute();
			return $delete_specific_creteria_stmt->rowCount();
			
			
			
		}
		 /* fill_sub_grade_creteria.php */
		public static function get_all_sub_creteria($semester_id){
			global $pdo;
			$get_all_sub_creteria_query = 
			"
			SELECT * FROM `sub_criteria_of_grade`,key_criteria_for_marks WHERE
			key_criteria_for_marks.key_criteria_id=sub_criteria_of_grade.key_criteria_id_fk AND
			key_criteria_for_marks.sem_val in (SELECT semester.auto_inc_id FROM semester WHERE semester.active=1);
			";
			$get_all_sub_creteria_stmt = $pdo->query($get_all_sub_creteria_query);
			
			$get_all_sub_creteria_stmt->execute();
			return $get_all_sub_creteria_stmt->fetchAll();
		}
		  public static function get_all_sub_creteria_for_specific_main_creteria($semester_id,$main_creteria){
            global $pdo;
            $get_all_sub_creteria_query = 
            "
            SELECT * FROM `sub_criteria_of_grade`,key_criteria_for_marks WHERE
            key_criteria_for_marks.key_criteria_id=sub_criteria_of_grade.key_criteria_id_fk AND
            sub_criteria_of_grade.key_criteria_id_fk=:key_criteria_id_fk AND
            key_criteria_for_marks.sem_val in (SELECT semester.auto_inc_id FROM semester WHERE semester.active=1);
            ";
            $get_all_sub_creteria_stmt = $pdo->prepare($get_all_sub_creteria_query);
            $get_all_sub_creteria_stmt->bindParam(':key_criteria_id_fk',$main_creteria,PDO::PARAM_INT);
            $get_all_sub_creteria_stmt->execute();
            return $get_all_sub_creteria_stmt->fetchAll();
        }
        public static function get_all_key_creteria_which_has_sub_creteria(){
            global $pdo;
         $get_all_key_creteria_which_has_sub_creteria_query = "SELECT `key_criteria_id`, `criteria_name`, `top_end_of_mak`, `low_end_of_mak`, `sem_val` FROM `key_criteria_for_marks` WHERE `key_criteria_id` in (SELECT key_criteria_id FROM `sub_criteria_of_grade`,key_criteria_for_marks WHERE key_criteria_for_marks.key_criteria_id=sub_criteria_of_grade.key_criteria_id_fk and key_criteria_for_marks.sem_val = (SELECT semester.auto_inc_id FROM semester WHERE semester.active=1))";
         $get_all_key_creteria_which_has_sub_creteria_stmt = $pdo->prepare($get_all_key_creteria_which_has_sub_creteria_query);
         $get_all_key_creteria_which_has_sub_creteria_stmt->execute();
         return $get_all_key_creteria_which_has_sub_creteria_stmt->fetchAll();
        }
		public static function check_if_this_sub_criteria_id_in_db($sub_criteria_id){
			global $pdo;
			$check_if_this_sub_criteria_id_in_db_query=
			"
			SELECT * FROM `sub_criteria_of_grade`  WHERE
			 sub_criteria_of_grade.sub_criteria_id=:sub_criteria_id ;
			
			";
			$check_if_this_sub_criteria_id_in_db_stmt = $pdo->prepare($check_if_this_sub_criteria_id_in_db_query);
			$check_if_this_sub_criteria_id_in_db_stmt->bindParam(':sub_criteria_id',$sub_criteria_id,PDO::PARAM_INT);
			$check_if_this_sub_criteria_id_in_db_stmt->execute();
			
return $check_if_this_sub_criteria_id_in_db_stmt->fetchAll() ;
			
			
			
		}
		public static function delete_specific_sub_creteria($sub_criteria_id){
			
				global $pdo;
			$check_if_this_sub_criteria_id_in_db_query=
			"
			DELETE FROM `sub_criteria_of_grade`  WHERE
			 sub_criteria_of_grade.sub_criteria_id=:sub_criteria_id ;
			
			";
			$check_if_this_sub_criteria_id_in_db_stmt = $pdo->prepare($check_if_this_sub_criteria_id_in_db_query);
			$check_if_this_sub_criteria_id_in_db_stmt->bindParam(':sub_criteria_id',$sub_criteria_id,PDO::PARAM_INT);
			$check_if_this_sub_criteria_id_in_db_stmt->execute();
			
return $check_if_this_sub_criteria_id_in_db_stmt->rowCount() ;
			
			
			
		}
		 
		public static function check_if_this_main_creteria_has_a_sub_creteria($main_key_creteria_id){
			global $pdo;
			$get_no_of_sub_creteria_for_specific_main_creteria_query = "
			SELECT COUNT(*) AS counter 
			FROM `sub_criteria_of_grade`
			WHERE  `key_criteria_id_fk`=:key_criteria_id_fk;";
			$get_no_of_sub_creteria_for_specific_main_creteria_stmt = $pdo->prepare($get_no_of_sub_creteria_for_specific_main_creteria_query);
			$get_no_of_sub_creteria_for_specific_main_creteria_stmt->bindParam(':key_criteria_id_fk',$main_key_creteria_id,PDO::PARAM_INT);
			$get_no_of_sub_creteria_for_specific_main_creteria_stmt->execute();
			$data = $get_no_of_sub_creteria_for_specific_main_creteria_stmt->fetchAll();	
			return $data[0]['counter'];
		}
		 public static function 
		 compare_btn_usr_input_sub_creteria_data_and_key_creteria_max_mark_if_sub_creteria_for_fixed_main_creteria($sub_mark,$key_criteria_id)
		 {
			 global $pdo;
			$compare_btn_usr_input_sub_creteria_data_and_key_creteria_max_mark_query =
			"SELECT count(*) as counter 
			 FROM 
			 `key_criteria_for_marks` 
			 WHERE 
			 :val<=`top_end_of_mak` 
			 AND 
			 `key_criteria_id`=:key_criteria_id";
			 $compare_btn_usr_input_sub_creteria_data_and_key_creteria_max_mark_stmt =
			 $pdo->prepare($compare_btn_usr_input_sub_creteria_data_and_key_creteria_max_mark_query);
			 $compare_btn_usr_input_sub_creteria_data_and_key_creteria_max_mark_stmt->bindParam(':val',$sub_mark,PDO::PARAM_INT);
			 $compare_btn_usr_input_sub_creteria_data_and_key_creteria_max_mark_stmt->bindParam(':key_criteria_id',$key_criteria_id,PDO::PARAM_INT);
			 $compare_btn_usr_input_sub_creteria_data_and_key_creteria_max_mark_stmt->execute(); 
			 $data =  $compare_btn_usr_input_sub_creteria_data_and_key_creteria_max_mark_stmt->fetchAll();
			return $data[0]['counter'];

		}
		public static function check_if_this_new_sub_grade_dont_exceed_max_value_allowed_for_each_semester($val,$creteria_id){
				global $pdo;
				 $sumation =0;
		$check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester_query = "
	SELECT (SUM(`sub_max_mark`)+:val) as sumation,top_end_of_mak,`sem_val` ,key_criteria_id_fk,key_criteria_id 
FROM
sub_criteria_of_grade  inner join
`key_criteria_for_marks`  
ON
key_criteria_for_marks.key_criteria_id=sub_criteria_of_grade.key_criteria_id_fk
where sub_criteria_of_grade.key_criteria_id_fk=:creteria_id
 
group by key_criteria_id_fk ,top_end_of_mak


having (SUM(`sub_max_mark`)+:val) <= key_criteria_for_marks.top_end_of_mak ";
			$check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester_stmt = $pdo->prepare($check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester_query );
			$check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester_stmt->bindParam(':val',$val,PDO::PARAM_INT);
			$check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester_stmt->bindParam(':creteria_id',$creteria_id,PDO::PARAM_INT);
			
			$check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester_stmt->execute();
			$data = $check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester_stmt->fetchAll();
			//if($data!=null){$sumation = $data[0]['sumation'];}
			
			return $data;
				 
		}
		public static function check_if_this_new_sub_grade_dont_exceed_max_value_allowed_for_each_semester1($val,$creteria_id,$exclude_creteria_id){
				global $pdo;
				 $sumation =0;
		$check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester_query = "
	SELECT (SUM(`sub_max_mark`)+:val) as sumation,top_end_of_mak,`sem_val` ,key_criteria_id_fk,key_criteria_id 
FROM
sub_criteria_of_grade  inner join
`key_criteria_for_marks`  
ON
key_criteria_for_marks.key_criteria_id=sub_criteria_of_grade.key_criteria_id_fk
where sub_criteria_of_grade.key_criteria_id_fk=:creteria_id
 and sub_criteria_of_grade.sub_criteria_id!=:creteria_id_needed_to_edit
group by key_criteria_id_fk ,top_end_of_mak


having (SUM(`sub_max_mark`)+:val) <= key_criteria_for_marks.top_end_of_mak ";
			$check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester_stmt = $pdo->prepare($check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester_query );
			$check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester_stmt->bindParam(':val',$val,PDO::PARAM_INT);
			$check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester_stmt->bindParam(':creteria_id',$creteria_id,PDO::PARAM_INT);
			$check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester_stmt->bindParam(':creteria_id_needed_to_edit',$exclude_creteria_id,PDO::PARAM_INT);
			
			
			$check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester_stmt->execute();
			$data = $check_if_this_new_grade_dont_exceed_max_value_allowed_for_each_semester_stmt->fetchAll();
			//if($data!=null){$sumation = $data[0]['sumation'];}
			
			return $data;
				 
		}
		public static function insert_new_sub_grade($sub_name,$sub_min_mark,$sub_max_mark,$key_criteria_id_fk){
			
			global $pdo;
			$insert_new_sub_grade_query="
				INSERT INTO `sub_criteria_of_grade`
				SET  `sub_name`=".$pdo->quote($sub_name).",
				`sub_min_mark`=:sub_min_mark, 
				`sub_max_mark`=:sub_max_mark,
				`key_criteria_id_fk`=:key_criteria_id_fk;";
				
				$insert_new_sub_grade_stmt = $pdo->prepare($insert_new_sub_grade_query);
				$insert_new_sub_grade_stmt->bindParam(':sub_min_mark',$sub_min_mark,PDO::PARAM_INT);
				$insert_new_sub_grade_stmt->bindParam(':sub_max_mark',$sub_max_mark,PDO::PARAM_INT);
				$insert_new_sub_grade_stmt->bindParam(':key_criteria_id_fk',$key_criteria_id_fk,PDO::PARAM_INT);
				$insert_new_sub_grade_stmt->execute();
				return $insert_new_sub_grade_stmt->rowCount();
				
		}
		public static function update_exist_sub_grade($sub_name,$sub_min_mark,$sub_max_mark,$key_criteria_id_fk,$key_criteria_id_needed_to_update){
			
			global $pdo; 
			$update_exist_sub_grade_query="
				UPDATE `sub_criteria_of_grade`
				SET  `sub_name`=".$pdo->quote($sub_name).",
				`sub_min_mark`=:sub_min_mark, 
				`sub_max_mark`=:sub_max_mark,
				`key_criteria_id_fk`=:key_criteria_id_fk
				WHERE sub_criteria_id=:key_criteria_id_needed_to_update
				;";
				
				$update_exist_sub_grade_stmt = $pdo->prepare($update_exist_sub_grade_query);
				
				$update_exist_sub_grade_stmt->bindParam(':sub_min_mark',$sub_min_mark,PDO::PARAM_INT);
				$update_exist_sub_grade_stmt->bindParam(':sub_max_mark',$sub_max_mark,PDO::PARAM_INT);
				$update_exist_sub_grade_stmt->bindParam(':key_criteria_id_fk',$key_criteria_id_fk,PDO::PARAM_INT);
				$update_exist_sub_grade_stmt->bindParam(':key_criteria_id_needed_to_update',$key_criteria_id_needed_to_update,PDO::PARAM_INT);
				 
				return $update_exist_sub_grade_stmt->execute() ;
		 
				
		}
		/* put_std_grade.php */
		public static function check_if_this_examiner_has_accepted_grp($examiner_id){
			global $pdo;
			$check_if_this_examiner_has_accepted_grp_query = "
			SELECT * FROM `examination`,groups WHERE examiner_id=".$pdo->quote($examiner_id)."
            and groups.grp_id=examination.groups_id
			and examination_accept_status='accepted';";
			$check_if_this_examiner_has_accepted_grp_stmt = $pdo->query($check_if_this_examiner_has_accepted_grp_query);
			$check_if_this_examiner_has_accepted_grp_stmt->execute();
			return $check_if_this_examiner_has_accepted_grp_stmt->fetchAll();
			
		}
		public static function check_if_sum_of_sub_creteria_equal_to_100(){
			global $pdo;
			$sum =0;
			/*
			SELECT SUM(`sub_max_mark`) AS sumation ,sem_val FROM `sub_criteria_of_grade` left join key_criteria_for_marks 
			on key_criteria_for_marks.key_criteria_id=sub_criteria_of_grade.key_criteria_id_fk where sem_val=1 
			group by sem_val having sem_val=1
			*/
			$check_if_sum_of_sub_creteria_equal_to_100_query = "SELECT SUM(`sub_max_mark`) AS sumation FROM 
			`sub_criteria_of_grade`,key_criteria_for_marks WHERE key_criteria_for_marks.key_criteria_id=sub_criteria_of_grade.key_criteria_id_fk
and key_criteria_for_marks.sem_val in (SELECT semester.auto_inc_id from semester WHERE semester.active=1);";
			$check_if_sum_of_sub_creteria_equal_to_100_stmt = $pdo->query($check_if_sum_of_sub_creteria_equal_to_100_query);
			 
			$check_if_sum_of_sub_creteria_equal_to_100_stmt->execute();
			 $data = $check_if_sum_of_sub_creteria_equal_to_100_stmt->fetchAll();
			 if($data!=null){
				 $sum= $data[0]['sumation'];
			 }
			return $sum;
			
		}
		public static function check_no_of_grp_accept_in_examination(){
			global $pdo;
			$check_no_of_std_accept_in_examination_query = 
			"
			SELECT COUNT(*) as counter FROM `groups` WHERE 
			`examination_status`='accepted' AND  `semester_id`
			in (SELECT semester.auto_inc_id FROM semester WHERE semester.active=1);
			";
			$check_no_of_std_accept_in_examination_stmt = $pdo->query($check_no_of_std_accept_in_examination_query);
			$check_no_of_std_accept_in_examination_stmt->execute();
			return $check_no_of_std_accept_in_examination_stmt->fetchAll();
			
			
		}
		
		/*set_examination_status_for_specific_grp.php*/
		public static function set_examination_status_for_specific_grp($grp_id,$examination_status){
			global $pdo;
			 $set_examination_status_for_specific_grp_query="UPDATE `groups`
				SET `examination_status`=".$pdo->quote($examination_status)." 
				WHERE 
				`grp_id`=:grp_id;";
			$set_examination_status_for_specific_grp_stmt = $pdo->prepare($set_examination_status_for_specific_grp_query);	
			$set_examination_status_for_specific_grp_stmt->bindParam(':grp_id',$grp_id,PDO::PARAM_INT);
			$set_examination_status_for_specific_grp_stmt->execute();
			return  $set_examination_status_for_specific_grp_stmt->rowCount();
			
			
			
		}
		public static function get_std_has_sub_grade($examiner_id){
			global $pdo;
			$get_std_has_sub_grade_query = 
			"SELECT * FROM group_members
			inner join 
			student_grade_for_sub_creteria
			on 
			group_members.student_id=student_grade_for_sub_creteria.std_id
			inner join 
			student
			on student.id =student_grade_for_sub_creteria.std_id
			INNER JOIN
			examination
			on examination.groups_id=group_members.group_id
			group by examination.examiner_id
			having examination.examiner_id=".$pdo->quote($examiner_id);
			$get_std_has_sub_grade_stmt = $pdo->prepare($get_std_has_sub_grade_query);
			$get_std_has_sub_grade_stmt->bindParam(':examiner_id',$examiner_id,PDO::PARAM_INT);
			$get_std_has_sub_grade_stmt->execute();
			return $get_std_has_sub_grade_stmt->fetchAll();
			
		}
		public static function get_all_students_for_this__supervisors_dont_has_a_sub_grade($examinar_id){
			global $pdo;
			$get_all_students_for_this__supervisors_dont_has_a_sub_grade_query=
			"
			  SELECT * FROM `groups`,group_members,student,users WHERE student.id=users.usr_id 
			  AND group_members.group_id=groups.grp_id AND group_members.student_id=student.id 
			  AND groups.owner=student.id AND ( group_members.student_id NOT IN 
			  (SELECT student_grade_for_sub_creteria.std_id FROM student_grade_for_sub_creteria)) 
			  AND ( group_members.group_id IN (SELECT examination.groups_id FROM examination WHERE examination.examiner_id=".$pdo->quote($examinar_id).") ) AND 
			  groups.examination_status='accepted' AND 
			  (group_members.student_id in (SELECT users.usr_id FROM users WHERE users.status='regular'))
			";
			$get_all_students_for_this__supervisors_dont_has_a_sub_grade_stmt = $pdo->prepare($get_all_students_for_this__supervisors_dont_has_a_sub_grade_query);
			$get_all_students_for_this__supervisors_dont_has_a_sub_grade_stmt->bindParam(':examinar_id',$examinar_id,PDO::PARAM_INT);
			$get_all_students_for_this__supervisors_dont_has_a_sub_grade_stmt->execute();
			return $get_all_students_for_this__supervisors_dont_has_a_sub_grade_stmt->fetchAll();
			
			
		}
		
		/* put_std_grade_edit.php */
		public static function get_sub_creteria_for_specific_key($key_creteria){
			global $pdo;
			$get_sub_creteria_for_specific_key_query = 
			"
			SELECT `sub_criteria_id`, `sub_name`, `sub_min_mark`, 
			`sub_max_mark` FROM `sub_criteria_of_grade` WHERE `key_criteria_id_fk`=:key_creteria;
			";
			$get_sub_creteria_for_specific_key_stmt = $pdo->prepare($get_sub_creteria_for_specific_key_query);
			$get_sub_creteria_for_specific_key_stmt->bindParam(':key_creteria',$key_creteria,PDO::PARAM_INT);
			$get_sub_creteria_for_specific_key_stmt->execute();
			return $get_sub_creteria_for_specific_key_stmt->fetchAll();
			
		}
		public static function insert_all_std_grade_for_sub_creteria($selected_std_id,$sub_creteria_id_arr,$mark_arr){
			global $pdo;
			$inserted_row_count=0;
			
			//
			 try {
            $pdo->beginTransaction();
            for($t=0;$t<count($mark_arr);$t++){
				$inserted_row_count+=self::insert_each_std_grade_row($selected_std_id,
				$sub_creteria_id_arr[$t],$mark_arr[$t]);
				
			}
           
            $pdo->commit();
        }
        catch (Exception $e) {
            if (isset($pdo)) {
                return false;
                $pdo->rollback();
               $err = "ﻟﻢ ﺗﺘﻢ اﻟﻌﻤﻠﻴﺔ ﺑﻨﺠﺎﺡ";
            }
        }
			
			//
			
			/*
			if($inserted_row_count==count($mark_arr)){
				return true;
			}
			*/
			return true ;
		}
		public static function insert_each_std_grade_row($std_id,$sub_creteria_no,$grade_of_fixed_sub_creteria){
			
			global $pdo;
			$insert_each_std_grade_row_query=
			"
			INSERT INTO `student_grade_for_sub_creteria`
			SET
			`std_id`=:std_id,
			`sub_creteria_no`=:sub_creteria_no, 
			`grade_of_fixed_sub_creteria` =:grade_of_fixed_sub_creteria;
			";
			$insert_each_std_grade_row_stmt = $pdo->prepare($insert_each_std_grade_row_query);
			$insert_each_std_grade_row_stmt->bindParam(':std_id',$std_id,PDO::PARAM_INT);
			$insert_each_std_grade_row_stmt->bindParam(':sub_creteria_no',$sub_creteria_no,PDO::PARAM_INT);
			$insert_each_std_grade_row_stmt->bindParam(':grade_of_fixed_sub_creteria',$grade_of_fixed_sub_creteria,PDO::PARAM_INT);
			$insert_each_std_grade_row_stmt->execute();
			//return $insert_each_std_grade_row_stmt->rowCount();
			
		}
		public static function get_all_student_has_sub_creteria_grades(){
			
			global $pdo;
			$get_all_student_has_sub_creteria_grades_query = 
			"SELECT * FROM users,student,student_grade_for_sub_creteria, key_criteria_for_marks,sub_criteria_of_grade 
				WHERE 
				student.id=users.usr_id
				AND
				student_grade_for_sub_creteria.std_id=student.id
				AND
				student_grade_for_sub_creteria.sub_creteria_no=sub_criteria_of_grade.sub_criteria_id
				AND
				sub_criteria_of_grade.key_criteria_id_fk=key_criteria_for_marks.key_criteria_id
				AND
				users.status='regular'

				AND
				key_criteria_for_marks.sem_val IN (SELECT semester.auto_inc_id FROM semester WHERE semester.active=1); ";
				
				$get_all_student_has_sub_creteria_grades_stmt = $pdo->query($get_all_student_has_sub_creteria_grades_query);
				$get_all_student_has_sub_creteria_grades_stmt->execute();
				return $get_all_student_has_sub_creteria_grades_stmt->fetchAll();
				
				
		}
		
		public static function get_rowCount_of_sub_creteria_in_this_semester(){
			global $pdo;
			$get_rowCount_of_sub_creteria_in_this_semester_query = "
			SELECT `sub_criteria_id`, `sub_name`, `sub_min_mark`, `sub_max_mark`, `key_criteria_id_fk` 
			FROM `sub_criteria_of_grade`,key_criteria_for_marks
			WHERE 
			sub_criteria_of_grade.key_criteria_id_fk=key_criteria_for_marks.key_criteria_id
			AND key_criteria_for_marks.sem_val in (SELECT semester.auto_inc_id FROM semester WHERE semester.active=1);
			";
			$get_rowCount_of_sub_creteria_in_this_semester_stmt = $pdo->query($get_rowCount_of_sub_creteria_in_this_semester_query);
			$get_rowCount_of_sub_creteria_in_this_semester_stmt->execute();
			
			$rowCount = $get_rowCount_of_sub_creteria_in_this_semester_stmt->rowCount();
			return $rowCount;
		}
		public static function get_std_which_has_grades_for_sub_creteria(){
			global $pdo;
			$get_std_which_has_grades_for_sub_creteria_query = "
			SELECT * FROM student,users WHERE
				student.id=users.usr_id
				AND
				student.id in (
				SELECT `std_id` FROM `student_grade_for_sub_creteria`
				);";
			$get_std_which_has_grades_for_sub_creteria_stmt = $pdo->query($get_std_which_has_grades_for_sub_creteria_query);
			$get_std_which_has_grades_for_sub_creteria_stmt->execute();
			return $get_std_which_has_grades_for_sub_creteria_stmt->fetchAll();

			
			
			
		}
		public static function check_if_this_std_id_has_grade_in_db($std_id){
			global  $pdo;
			$check_if_this_std_id_has_grade_in_db_query = 
			"
			SELECT *
			FROM 
			`student_grade_for_sub_creteria` ,student,users,sub_criteria_of_grade
			WHERE 
            sub_criteria_of_grade.sub_criteria_id=student_grade_for_sub_creteria.sub_creteria_no
            AND
            student.id=users.usr_id
            AND
            student.id=student_grade_for_sub_creteria.std_id
            AND
			`std_id`=".$pdo->quote($std_id);
			$check_if_this_std_id_has_grade_in_db_stmt = $pdo->query($check_if_this_std_id_has_grade_in_db_query);
			$check_if_this_std_id_has_grade_in_db_stmt->execute();
			return $check_if_this_std_id_has_grade_in_db_stmt->fetchAll();
			
			
		}
		public static function update_std_grade($sub_creteria_id_arr,$mark_arr,$old_std_id){
			global $pdo;
			$update_std_grade_query = 
			"
			UPDATE `student_grade_for_sub_creteria`
			SET  
			`grade_of_fixed_sub_creteria`=:grade_of_fixed_sub_creteria
			WHERE 
			`std_id`=".$pdo->quote($old_std_id)."
			AND
			`sub_creteria_no`=:sub_creteria_no
			";
			$update_std_grade_stmt = $pdo->prepare($update_std_grade_query);
			$update_std_grade_stmt->bindParam(':grade_of_fixed_sub_creteria',$mark_arr,PDO::PARAM_INT);
			$update_std_grade_stmt->bindParam(':sub_creteria_no',$sub_creteria_id_arr,PDO::PARAM_INT);
			  $update_std_grade_stmt->execute();
		 
			
			
		}
		public static function update_all_std_grade($sub_creteria_id_arr,$mark_arr,$old_std_id){
			global $pdo;
			 try {
            //$pdo->beginTransaction();
             for($t=0;$t<count($mark_arr);$t++){
				 self::update_std_grade($sub_creteria_id_arr[$t],$mark_arr[$t],$old_std_id);
			 }
           // $pdo->commit();
        }
        catch (Exception $e) {
			echo $e->getMessage();
            if (isset($pdo)) {
                return false;
                $pdo->rollback();
               $err = "ﻟﻢ ﺗﺘﻢ اﻟﻌﻤﻠﻴﺔ ﺑﻨﺠﺎﺡ";
            }
        }
			
			
			 
		 
			return true;
			
		}
		
		public static function get_row_count_of_sub_cretria()
		{
			global $pdo;
			$rowCount=0;
			$get_row_count_of_sub_cretria_query = "
			SELECT count(*) as counter
			FROM `student_grade_for_sub_creteria` 
   
			";
			$get_row_count_of_sub_cretria_stmt = $pdo->query($get_row_count_of_sub_cretria_query);
			$get_row_count_of_sub_cretria_stmt->execute();
			$data = $get_row_count_of_sub_cretria_stmt->fetchAll();
			if($data!=null){
				$rowCount = $data[0]['counter'];
				
			}
			return $rowCount;
		}
		public static function delete_fixed_sub_creteria_grade_for_std($std_id){
			
			global $pdo;
			$delete_fixed_sub_creteria_grade_for_std_query = 
			"
			DELETE FROM `student_grade_for_sub_creteria` WHERE `std_id`=".$pdo->quote($std_id);
			return $pdo->exec($delete_fixed_sub_creteria_grade_for_std_query);
			 
			
			
		}
		public static function check_if_this_supervisor_has_a_group($supervisor_id){
		global $pdo;
		$check_if_this_supervisor_has_a_group_query="SELECT * FROM `supervision` ,groups where groups.grp_id=supervision.group_id 
		and sup_status='accepted' AND supervision.teacher_id=".$pdo->quote($supervisor_id);
		$check_if_this_supervisor_has_a_group_stmt = $pdo->query($check_if_this_supervisor_has_a_group_query);
		$check_if_this_supervisor_has_a_group_stmt->execute();  
		return $check_if_this_supervisor_has_a_group_stmt->fetchAll();
		
		
			
		}
		//get_grp_name.php
		public static function get_grp_name_for_specific_grp($selected_grp){
			global $pdo;
	$get_grp_name_query = "SELECT grp_name FROM groups WHERE grp_id=:grp_id";
$get_grp_name_stmt = $pdo->prepare($get_grp_name_query);
$get_grp_name_stmt->bindParam(':grp_id',$selected_grp,PDO::PARAM_INT);
$get_grp_name_stmt->execute();
$data = $get_grp_name_stmt->fetchAll();
$grp_name="";
 if($data!=null){$grp_name=$data[0]['grp_name'];}
return $grp_name;
	
	
}
	public static function insert_msg_into_specific_user_for_specific_grp($selected_grp ,$msg_chat ,$supervisor_login_id)
				{
				global $pdo;
				$get_chat_room_id_query = "SELECT  `chat_room_id_fk` FROM `groups` WHERE  `grp_id`=:grp_id;";
				$get_chat_room_id_stmt = $pdo->prepare($get_chat_room_id_query );
				$get_chat_room_id_stmt->bindParam(':grp_id',$selected_grp,PDO::PARAM_INT);
				$get_chat_room_id_stmt->execute();
				$data = $get_chat_room_id_stmt->fetchAll();
				$chat_room_id_fk='';
				if($data!=null){
					$chat_room_id_fk = $data[0]['chat_room_id_fk'];
				}
				
				$inserrt_msg_from_usr_into_spcific_grp_query = "INSERT INTO `messages_btn_supervisor_and_his_grp_members` SET  `sender`=".$pdo->quote($supervisor_login_id).", 
				`msg_text`=".$pdo->quote($msg_chat).", `chat_room_id_fk`=:chat_room_id_fk, `sending_time`=NOW();";
	            $inserrt_msg_from_usr_into_spcific_grp_stmt = $pdo->prepare($inserrt_msg_from_usr_into_spcific_grp_query);
				$inserrt_msg_from_usr_into_spcific_grp_stmt->bindParam(':chat_room_id_fk',$chat_room_id_fk,PDO::PARAM_INT);
				$inserrt_msg_from_usr_into_spcific_grp_stmt->execute();
				//$get_msg_for_specsific_grp = self::get_msg_for_specsific_grp($selected_grp);
				return $inserrt_msg_from_usr_into_spcific_grp_stmt->rowCount();
				}	
				public static function get_msg_for_specsific_grp($grp_id){
					global $pdo;
					$get_msg_for_specsific_grp_query  = "SELECT * FROM `messages_btn_supervisor_and_his_grp_members`,
					users,groups WHERE users.usr_id=messages_btn_supervisor_and_his_grp_members.sender AND groups.grp_id=:grp_id";
					$get_msg_for_specsific_grp_stmt = $pdo->prepare($get_msg_for_specsific_grp_query);
					$get_msg_for_specsific_grp_stmt->bindParam(':grp_id',$grp_id,PDO::PARAM_INT);
					$get_msg_for_specsific_grp_stmt->execute();
					$data = $get_msg_for_specsific_grp_stmt->fetchAll();
					return $data;
					
				}
				/* send_your_weekly_project_work.php */
				public static function delete_this_msg_and_its_attachment($u_msg,$u_att_id){
					global $pdo;
					  try{
                        
						 $pdo->beginTransaction();
						
                        $delete_attachment_msg_query = "DELETE FROM `attachments` WHERE `attachments_id`=:u_att_id;";
						$delete_attachment_msg_stmt = $pdo->prepare($delete_attachment_msg_query);
						$delete_attachment_msg_stmt->bindParam(':u_att_id',$u_att_id,PDO::PARAM_INT);
						$delete_attachment_msg_stmt->execute();

                        $delete_msg_query = "DELETE FROM `messages` WHERE `messages_id`=:u_msg;";
                        $delete_msg_stmt = $pdo->prepare($delete_msg_query);
                        $delete_msg_stmt->bindParam(':u_msg',$u_msg,PDO::PARAM_INT);
                        $delete_msg_stmt->execute();
						 
					 	$pdo->commit();
                        return true;
					}catch(PDOException $ex){
						echo $ex->getMessage();
						 if(isset($pdo)){
							 
							 $pdo->rollback();
							 return false;
							 
						 }
						
					} 
					  
					
				}
				/* add_halls_into_this_semester.php */
				public static function check_if_this_hall_exist($hall_no){
					global $pdo;
					$check_if_this_hall_exist_query = "SELECT count(*) as counter FROM rooms WHERE `room_description`=".$pdo->quote($hall_no)."
					AND `semesters_id_ref` in (SELECT semester.auto_inc_id FROM semester WHERE semester.active=1)";
					$check_if_this_hall_exist_stmt = $pdo->query($check_if_this_hall_exist_query);
					$check_if_this_hall_exist_stmt->execute();
					$data = $check_if_this_hall_exist_stmt->fetchAll();
					return ($data[0]['counter']==0);
					
				}
					public static function check_if_this_hall_exist1($selected_room_id,$hall_no){
					global $pdo;
					$check_if_this_hall_exist_query = "SELECT count(*) as counter FROM rooms WHERE `room_description`=".$pdo->quote($hall_no)."
					AND `semesters_id_ref` in (SELECT semester.auto_inc_id FROM semester WHERE semester.active=1) and room_id!=:room_id";
					$check_if_this_hall_exist_stmt = $pdo->prepare($check_if_this_hall_exist_query);
					$check_if_this_hall_exist_stmt->bindParam(':room_id',$selected_room_id,PDO::PARAM_INT);
					$check_if_this_hall_exist_stmt->execute();
					$data = $check_if_this_hall_exist_stmt->fetchAll();
					return ($data[0]['counter']==0);
					
				}
			/* send_weekly_work_of_group_edit.php */
public static function check_if_it_is_a_time_to_begin_evt( $evt_name){
/**/
$status=false;
 $send_your_weekly_project_work_begining_and_ending_evt_date=Crud_op::get_first_and_end_date_for_evt($evt_name);
 if(count($send_your_weekly_project_work_begining_and_ending_evt_date)!=0){
   $send_your_weekly_project_work_begining_date=$send_your_weekly_project_work_begining_and_ending_evt_date[0]['from_date'];
 $send_your_weekly_project_work_ending_date=$send_your_weekly_project_work_begining_and_ending_evt_date[0]['to_date']; 
 $hour=date('H');
 $min=date('i');
 $sec=date('s');
 $month=date('m');
 $day=date('d');
 $year=date('Y');
 $current_Date = mktime($hour, $min, $sec, $month, $day, $year);
$current_Date= date("Y-m-d H:i:s", $current_Date); 
$send_your_weekly_project_work_begining_date= date("Y-m-d H:i:s", $send_your_weekly_project_work_begining_date); 
$send_your_weekly_project_work_ending_date= date("Y-m-d H:i:s", $send_your_weekly_project_work_ending_date);
 
if($current_Date>=$send_your_weekly_project_work_begining_date && $current_Date<=$send_your_weekly_project_work_ending_date){
  
$status=true;
} 
  } 
/**/
return $status;
}
public static function insertNewMotherMeg($groupID,$sender,$messages_text,$to_which_msg_id_reply,$is_this_thesis_file,$IsThisMsgTheMother,$attachmentPath,$status){
global $pdo;
try {
  $pdo->beginTransaction();
/* start insert msg */
$insertNewMotherMsgQuery = 
"
INSERT INTO `messages`
SET
`group_id`=:group_id,
`sender`=:sender, 
`sending_time`=NOW(),
`messages_text`=:messages_text, 
`to_which_msg_id_reply`=:to_which_msg_id_reply,
`is_this_thesis_file`=:is_this_thesis_file, 
`IsThisMsgTheMother`=:IsThisMsgTheMother;
";
$insertNewMotherMsgStmt = $pdo->prepare($insertNewMotherMsgQuery);
$insertNewMotherMsgStmt->bindParam(':group_id',$groupID,PDO::PARAM_INT);
$insertNewMotherMsgStmt->bindParam(':sender',$sender,PDO::PARAM_STR);
$insertNewMotherMsgStmt->bindParam(':messages_text',$messages_text,PDO::PARAM_STR);
$insertNewMotherMsgStmt->bindParam(':to_which_msg_id_reply',$to_which_msg_id_reply,PDO::PARAM_INT);
$insertNewMotherMsgStmt->bindParam(':is_this_thesis_file',$is_this_thesis_file,PDO::PARAM_INT);
$insertNewMotherMsgStmt->bindParam(':IsThisMsgTheMother',$IsThisMsgTheMother,PDO::PARAM_INT);
 $insertNewMotherMsgStmt->execute();
$lastInsertedMsg = $pdo->lastInsertId();
/* end insert msg */
/* start insert attachment */
if ($attachmentPath!="") {
    # code...
    $insertRelatedAttachmentForMotherMsgQuery = 
"
INSERT INTO `attachments`
SET
`msg_id`=:msg_id, 
`url_str`=:url_str, 
`status`=:status; 
";
$insertRelatedAttachmentForMotherMsgStmt = $pdo->prepare($insertRelatedAttachmentForMotherMsgQuery);
$insertRelatedAttachmentForMotherMsgStmt->bindParam(':msg_id',$lastInsertedMsg,PDO::PARAM_INT);
$insertRelatedAttachmentForMotherMsgStmt->bindParam(':url_str',$attachmentPath,PDO::PARAM_STR);
$insertRelatedAttachmentForMotherMsgStmt->bindParam(':status',$status,PDO::PARAM_STR); 
 $insertRelatedAttachmentForMotherMsgStmt->execute();

}

/* end insert attachment */
  $pdo->commit();
  return true;
} catch (PDOException $e) {
    if (isset($pdo)) {

    $pdo->rollback();
    return false;
    }
}

}
public static function getMotherMsgToFixedGrpForStdInbox($groupID){
global $pdo;
$getMotherMsgQuery = "SELECT `messages_id`, `group_id`, `sender`, `sending_time`, `messages_text`, `to_which_msg_id_reply`, `is_this_thesis_file`, `IsThisMsgTheMother`,`usr_id`, `password`, `role`, `fname`, `lname`, users.status as usr_status,`url_str`,attachments.status as attach_status
FROM `messages`,attachments,users
WHERE attachments.msg_id=messages.messages_id 
and 
users.usr_id=sender 
  and
( ( 
    
   LEFT(`sender`, 1)='l' 
   and 
   group_id=:groupID 
   and messages.to_which_msg_id_reply=0) 
 OR ( 
     LEFT(`sender`, 1)='l' 
     and 
     group_id=:groupID 
     and 
     messages.to_which_msg_id_reply 
     IN 
     (SELECT messages.messages_id 
      FROM 
      messages 
      WHERE
      IsThisMsgTheMother=1 
      and 
      LEFT(`sender`, 1)='s' 
      and group_id=:groupID)) ) 
      ORDER BY 
      sending_time
      DESC";
$getMotherMsgStmt = $pdo->prepare($getMotherMsgQuery);
$getMotherMsgStmt->bindParam(':groupID',$groupID,PDO::PARAM_INT);
$getMotherMsgStmt->execute();

return $getMotherMsgStmt->fetchAll();
}
public static function getMotherMsgToFixedGrpForSupInbox($groupID){
global $pdo;
$getMotherMsgQuery=
    " 
SELECT `messages_id`, `group_id`, `sender`, `sending_time`, `messages_text`, `to_which_msg_id_reply`, `is_this_thesis_file`, `IsThisMsgTheMother`,`usr_id`, `password`, `role`, `fname`, `lname`, users.status as usr_status,`url_str`,attachments.status as attach_status
FROM `messages`,attachments,users
WHERE attachments.msg_id=messages.messages_id  and  
users.usr_id=sender 
 and
(

 ( 
    
   LEFT(`sender`, 1)='s' 
   and 
   group_id=:groupID 
   and messages.to_which_msg_id_reply=0) 
 OR ( 
     LEFT(`sender`, 1)='s'
     and 
     group_id=:groupID 
     and( 
     messages.to_which_msg_id_reply 
     IN 
     (SELECT messages.messages_id 
      FROM 
      messages 
      WHERE
      IsThisMsgTheMother=1 
      and 
      LEFT(`sender`, 1)='l' 
      and group_id=:groupID
     and messages.to_which_msg_id_reply =0
     )
 )
 )
) 
      ORDER BY 
      sending_time
      DESC
 ";
 $getMotherMsgStmt = $pdo->prepare($getMotherMsgQuery);
$getMotherMsgStmt->bindParam(':groupID',$groupID,PDO::PARAM_INT);
$getMotherMsgStmt->execute();

return $getMotherMsgStmt->fetchAll();
}
/* see_my_examinar.php */
public static function get_examination_status_of_fixed_grp($grpID){
global $pdo;
$get_examination_status_of_fixed_grp_query = "SELECT `examination_status` FROM `groups` WHERE `grp_id`=:grp_id;";
$get_examination_status_of_fixed_grp_stmt = $pdo->prepare($get_examination_status_of_fixed_grp_query);
$get_examination_status_of_fixed_grp_stmt->bindParam(':grp_id',$grpID,PDO::PARAM_INT);
$get_examination_status_of_fixed_grp_stmt->execute();
$data = $get_examination_status_of_fixed_grp_stmt->fetchAll();
$examination_status="";
if ($data!=null) {
    $examination_status=$data[0]['examination_status'];
}
return  $examination_status;
}
public static function getRelatedMsgOfMotherMsg($montherMsgID,$groupID){
   
global $pdo;
$getRelatedMsgOfMotherMsgQuery="SELECT `messages_id`, `group_id`, `sender`, `sending_time`, `messages_text`, `to_which_msg_id_reply`, `is_this_thesis_file`, `IsThisMsgTheMother` ,`attachments_id`, `msg_id`, `url_str`, attachments.`status`,concat(fname,' ',lname) as name FROM `messages`,`attachments`,users WHERE usr_id=sender and attachments.msg_id=messages.messages_id AND ( (to_which_msg_id_reply=:to_which_msg_id_reply AND group_id=:group_id) OR (messages.messages_id=:to_which_msg_id_reply ) ) ORDER BY messages.sending_time DESC ;
 
 ";
$getRelatedMsgOfMotherMsgStmt = $pdo->prepare($getRelatedMsgOfMotherMsgQuery);
$getRelatedMsgOfMotherMsgStmt->bindParam(':to_which_msg_id_reply',$montherMsgID,PDO::PARAM_INT);
$getRelatedMsgOfMotherMsgStmt->bindParam(':group_id',$groupID,PDO::PARAM_INT);
$getRelatedMsgOfMotherMsgStmt->execute();
return $getRelatedMsgOfMotherMsgStmt->fetchAll();

} 
public static function sender_msg_of_std($std_id,$grpID){
global $pdo;
$sender_msg_of_std_query = "(SELECT * FROM `messages` WHERE (LEFT(messages.sender,1)='l' ) AND messages.group_id=:grpID AND to_which_msg_id_reply 
IN (SELECT messages.messages_id FROM messages WHERE messages.to_which_msg_id_reply=0 and
 (LEFT(messages.sender,1)='s') AND sender=:std_id ) ) UNION (SELECT * from messages WHERE sender=:std_id and to_which_msg_id_reply=0)";
$sender_msg_of_std_stmt = $pdo->prepare($sender_msg_of_std_query);
$sender_msg_of_std_stmt->bindParam(':std_id',$std_id,PDO::PARAM_STR);
$sender_msg_of_std_stmt->bindParam(':grpID',$grpID,PDO::PARAM_INT);
$sender_msg_of_std_stmt->execute();
return $sender_msg_of_std_stmt->fetchAll();

}

public static function getAllMotherMsgForThisGrp($grpID){
global $pdo;
$getRelatedMsgfromSupMsgsQuery="SELECT * from messages,users WHERE sender=usr_id  and messages.group_id=:grpID and 
to_which_msg_id_reply=0";
$getRelatedMsgfromSupMsgsStmt = $pdo->prepare($getRelatedMsgfromSupMsgsQuery);
$getRelatedMsgfromSupMsgsStmt->bindParam(':grpID',$grpID,PDO::PARAM_INT); 
$getRelatedMsgfromSupMsgsStmt->execute();
return $getRelatedMsgfromSupMsgsStmt->fetchAll();


}
//for inbox std msg
 public static function getInboxMsgForGroupFromSupOfGrp($grpID){
global $pdo;
$getAllMotherMsgForThisGrp = self::getAllMotherMsgForThisGrp($grpID);
$inboxMsg=array();
if ($getAllMotherMsgForThisGrp!=null) {
    for ($i=0; $i < count($getAllMotherMsgForThisGrp); $i++) { 
      
    $motherMsgID = $getAllMotherMsgForThisGrp[$i]['messages_id']  ;
    $CheckIfItasRelatedMsgOrItIsALone = Crud_op::CheckIfIthasRelatedMsgOrItIsALone($grpID,$motherMsgID);
    $sender=$getAllMotherMsgForThisGrp[$i]['sender'];
    $senderType=$sender[0];
if ($CheckIfItasRelatedMsgOrItIsALone==0 &&  $senderType='l') {
   $inboxMsg[]=$getAllMotherMsgForThisGrp[$i];
}

    } 
}
 
 }

 public static function CheckIfIthasRelatedMsgOrItIsALone($grpID,$messages_id){
global $pdo;
$supMotherMsgIDQuery = "SELECT count(*) as counter from messages,users WHERE sender=usr_id and LEFT(sender,1)='l'   and messages.group_id=:grpID and 
to_which_msg_id_reply=:supMotherMsgID";
$supMotherMsgIDStmt = $pdo->prepare($supMotherMsgIDQuery);
$supMotherMsgIDStmt->bindParam(':supMotherMsgID',$messages_id,PDO::PARAM_INT);
$supMotherMsgIDStmt->bindParam(':grpID',$grpID,PDO::PARAM_INT);
// $supMotherMsgIDStmt->bindParam(':stdID',$stdID,PDO::PARAM_STR);

$supMotherMsgIDStmt->execute();
$data = $supMotherMsgIDStmt->fetchAll();

$rowCount =0;
if ($data!=null) {
  $rowCount =$data[0]['counter'];   
}
return $rowCount;

 }
//for std sent msg
public static function supMotherMsgID($stdID,$grpID,$messages_id){
global $pdo;
$supMotherMsgIDQuery = "SELECT count(*) as counter from messages,users WHERE sender=usr_id and sender=:stdID  and messages.group_id=:grpID and 
to_which_msg_id_reply=:supMotherMsgID";
$supMotherMsgIDStmt = $pdo->prepare($supMotherMsgIDQuery);
$supMotherMsgIDStmt->bindParam(':supMotherMsgID',$messages_id,PDO::PARAM_INT);
$supMotherMsgIDStmt->bindParam(':grpID',$grpID,PDO::PARAM_INT);
$supMotherMsgIDStmt->bindParam(':stdID',$stdID,PDO::PARAM_STR);

$supMotherMsgIDStmt->execute();
$data = $supMotherMsgIDStmt->fetchAll();

$rowCount =0;
if ($data!=null) {
  $rowCount =$data[0]['counter'];   
}
return $rowCount;
}
public static function get_all_start_msg_for_std($stdID,$grpID){
global $pdo;
$getAllMotherMsgForThisGrp = self::getAllMotherMsgForThisGrp($grpID);
$motherMsgHasSenderFromLoginStd=array();
 
for ($i=0; $i <count($getAllMotherMsgForThisGrp) ; $i++) { 
  $sender = $getAllMotherMsgForThisGrp[$i]['sender']; 
 $rol_of_sender = $sender[0]; 
 $loginUsrRole = $stdID[0];
 if ( $loginUsrRole=='s') {
    if ( $rol_of_sender=='s') {
     $motherMsgHasSenderFromLoginStd[] = $getAllMotherMsgForThisGrp[$i];
 }
 elseif ( $rol_of_sender=='l') {
      $supMotherMsgID = $getAllMotherMsgForThisGrp[$i]['messages_id']; 
       
     $getLastMsgReplyOnSupervisor = Crud_op::getLastMsgReplyOnSupervisor($stdID,$grpID,$supMotherMsgID);
 
     if (count($getLastMsgReplyOnSupervisor)!=0) {
         $motherMsgHasSenderFromLoginStd[] =  $getLastMsgReplyOnSupervisor;
     
     }
        
 }  
 }
 elseif ($loginUsrRole=='l') {
      if ( $rol_of_sender=='l') {//if sup send then it's mother msg
     $motherMsgHasSenderFromLoginStd[] = $getAllMotherMsgForThisGrp[$i];
 }
  elseif ( $rol_of_sender=='l') {
      $supMotherMsgID = $getAllMotherMsgForThisGrp[$i]['messages_id']; 
       
     $getLastMsgReplyOnSupervisor = Crud_op::getLastMsgReplyOnSupervisor($stdID,$grpID,$supMotherMsgID);
 
     if (count($getLastMsgReplyOnSupervisor)!=0) {
         $motherMsgHasSenderFromLoginStd[] =  $getLastMsgReplyOnSupervisor;
     
     }
        
 } 
 }

}   
return $motherMsgHasSenderFromLoginStd;

} 
public static function getLastMsgReplyOnSupervisor($stdID,$grpID,$supMotherMsgID){
    global $pdo;
$supMotherMsgIDQuery = "SELECT * from messages INNER JOIN users  on sender=usr_id WHERE sender=:stdID  and messages.group_id=:grpID and 
to_which_msg_id_reply=:supMotherMsgID order  by sending_time desc limit 1
";
$supMotherMsgIDStmt = $pdo->prepare($supMotherMsgIDQuery);
$supMotherMsgIDStmt->bindParam(':supMotherMsgID',$messages_id,PDO::PARAM_INT);
$supMotherMsgIDStmt->bindParam(':grpID',$grpID,PDO::PARAM_INT);
$supMotherMsgIDStmt->bindParam(':stdID',$stdID,PDO::PARAM_STR);

$supMotherMsgIDStmt->execute();
// var_dump($supMotherMsgIDStmt->fetchAll());
return  $supMotherMsgIDStmt->fetchAll();

 
}
public static function get_attachment_for_related_msg($msgID){
global $pdo;
$get_attachment_for_related_msg_query = "SELECT `attachments_id`, `msg_id`, `url_str`, `status` FROM `attachments` WHERE msg_id=:msg_id;";
$get_attachment_for_related_msg_stmt = $pdo->prepare($get_attachment_for_related_msg_query);
$get_attachment_for_related_msg_stmt->bindParam(':msg_id',$msgID,PDO::PARAM_INT);
$get_attachment_for_related_msg_stmt->execute();
return $get_attachment_for_related_msg_stmt->fetchAll();
}
public static function get_related_to_mother_msg_for_std($messages_id){
global $pdo;
$get_related_to_mother_msg_for_std_query="SELECT * FROM `messages`,users  WHERE users.usr_id=sender and messages.to_which_msg_id_reply=:to_which_msg_id_reply;";
$get_related_to_mother_msg_for_std_stmt = $pdo->prepare($get_related_to_mother_msg_for_std_query);
$get_related_to_mother_msg_for_std_stmt->bindParam(':to_which_msg_id_reply',$messages_id,PDO::PARAM_INT);
$get_related_to_mother_msg_for_std_stmt->execute();
return $get_related_to_mother_msg_for_std_stmt->fetchAll();


}




}
?>