<?php 
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    $id = trim(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $status = trim(filter_input(INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $module = isset($_POST['modulename']) ? $_POST['modulename'] : false;

    # Update User Info
    $userinfo = "UPDATE `jb_user` SET `status` = '".$status."', `updated_at` = '".dateToday()."' WHERE `id` = '".$id."'";
    # Explode
    $checker = "DELETE FROM `jb_permission` WHERE user_id = '" .$id. "'";

    if ($module) {

        $userinfoUpdate = $db->ExecuteQuery($userinfo);
        $query = $db->InsertData($checker);

        if( $query ) {

            foreach ($module as $key => $value) {
                $permissiontype = "SELECT * FROM jb_permission_type WHERE id='".$value."'"; 
                $permissionQuery = $db->ReadData($permissiontype);

                $view_status = 'no';
                $add_status = 'no';
                $edit_status = 'no';
                $delete_status = 'no';
                
                if(isset($_POST[$permissionQuery[0]['name']])) {
                    for ($i=0; $i < count($_POST[$permissionQuery[0]['name']]) ; $i++) { 

                        if( $_POST[$permissionQuery[0]['name']][$i] == 'view' ) {
                            $view_status = 'yes';
                        }
                        if( $_POST[$permissionQuery[0]['name']][$i] == 'add' ) {
                            $add_status = 'yes';
                        }
                        if( $_POST[$permissionQuery[0]['name']][$i] == 'edit' ) {
                            $edit_status = 'yes';
                        }
                        if( $_POST[$permissionQuery[0]['name']][$i] == 'delete' ) {
                            $delete_status = 'yes';
                        }

                    }
                }

                $sql = "INSERT INTO `jb_permission`(`user_id`, `permission_type_id`, `add_status`, `edit_status`, `delete_status`, `view_status`, `created_at`) VALUES ('".$id."','".$permissionQuery[0]['id']."','".$add_status."','".$edit_status."','".$delete_status."','".$view_status."','".dateToday()."')";
                $db->InsertData($sql);
            }
        }

    } else {
        $userinfoUpdate = $db->ExecuteQuery($userinfo);
        $query = $db->InsertData($checker);
    }

    if( isset($userinfoUpdate) && isset($query) ) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>