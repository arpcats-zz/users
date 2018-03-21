<?php
require_once("config.php");
require_once("DatabaseConnector.php");

$db = new DatabaseConnector();

if(array_key_exists("submit", $_POST))
{
    $submit = !empty($_POST["submit"]) ? $_POST["submit"] : "";
    $id = !empty($_POST["id"]) ? $_POST["id"] : "";
    $name = !empty($_POST["name"]) ? $_POST["name"] : "";
    $email = !empty($_POST["email"]) ? $_POST["email"] : "";
    $contact = !empty($_POST["contact"]) ? $_POST["contact"] : "";
    $status = !empty($_POST["status"]) ? $_POST["status"] : "";

    if($submit == "add" || $submit == "edit")
    {
        $data["name"] = $name;
        $data["email"] = $email;
        $data["contact"] = $contact;
        $data["status"] = $status;
        if($submit == "add")
        {
            $data["created"] = date("Y-m-d H:i:s");
            $db->insert("users", $data);
        }
        else if($submit == "edit")
        {
            if(is_numeric($id))
            {
                $data["updated"] = date("Y-m-d H:i:s");
                $condition["id"] = $id;
                $db->update("users", $data, $condition);
            }
        }

        header("Location: index.php");
    }
}

if(array_key_exists("action", $_POST))
{
    $action = !empty($_POST['action']) ? $_POST['action'] : "";
    $id = !empty($_POST['id']) ? $_POST['id'] : "";
    if($action == "edit" && is_numeric($id))
    {
        $conditions["where"] = array("id" => $id);
        $conditions["return_type"] = "single";
        $user = $db->select("users", $conditions);
        print json_encode($user);
        exit;
    }
    else if($action == "delete" && is_numeric($id))
    {
        $conditions["id"] = $id;
        $user = $db->delete("users", $conditions);
        print json_encode($user);
        exit;
    }
}

?>