<?php
require_once("config.php");
require_once("DatabaseConnector.php");

$db = new DatabaseConnector();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Users</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.2/css/bootstrapValidator.min.css"/>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.2/js/bootstrapValidator.min.js"></script>
</head>
<body>
    <br>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
               <p><button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModal"> Add </button></p>
                <div class="table-responsive">
                    <table class="table table-striped table-condensed">
                        <thead>
                            <tr>
                                <td>ID</td>
                                <td>Name</td>
                                <td>Email</td>
                                <td>Contact</td>
                                <td>Created</td>
                                <td>Updated</td>
                                <td>Status</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $users = $db->select("users", array("order_by" => "id DESC"));
                                if(!empty($users))
                                {
                                    foreach($users as $user)
                                    {
                                        if($user['status'] == 1)
                                            $status = "Active";
                                        else if($user['status'] == 2)
                                            $status = "Unactive";

                                        $tr[] = "
                                        <tr class='row".$user["id"]."'>
                                            <td>" . $user["id"] . "</td>
                                            <td>" . $user["name"] . "</td>
                                            <td>" . $user["email"] . "</td>
                                            <td>" . $user["contact"] . "</td>
                                            <td>" . $user["created"] . "</td>
                                            <td>" . $user["updated"] . "</td>
                                            <td>" . $status . "</td>
                                            <td>
                                                <button onclick=\"showModal('".$user['id']."')\" class='btn btn-outline-info btn-xs'><i class='fa fa-pencil'></i></button>
                                                <button onclick=\"deleteData('".$user['id']."')\" class='btn btn-outline-danger btn-xs'><i class='fa fa-trash'></i></button>
                                            </td>
                                        </tr>
                                        ";
                                    }
                                }
                                else
                                {
                                    $tr[] = "<tr class='danger text-center'><td colspan='7'>No Record Found!</td></tr>";
                                }

                                echo implode("", $tr);
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php include_once("form.php");?>

    <script>
        function deleteData(id){
            $.post("controller.php", $.param({"id":id,"action":"delete"}), function(){
                $(".row"+id).addClass("danger").fadeOut("slow");
            });
        }

        function showModal(id){
            $("#myModal").modal({
                backdrop: 'static',
                keyboard: false
            });

            $.post("controller.php", $.param({"id":id,"action":"edit"}), function(data){
                $(".user-form").append("<input type='hidden' name='id' value='"+data.id+"'>");
                $(".user-form input[name='name']").val(data.name);
                $(".user-form input[name='email']").val(data.email);
                $(".user-form input[name='contact']").val(data.contact);
                $(".user-form select[name='status'] option:selected").val(data.status);
                $(".user-form button[type='submit']").val("edit").text("Edit");
            },"json");
        }

        $(document).ready(function() {
            $('.user-form').bootstrapValidator({
                message: 'This value is not valid',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    name: {
                        message: 'The name is not valid',
                        validators: {
                            notEmpty: {
                                message: 'The name is required and cannot be empty'
                            },
                            stringLength: {
                                min: 6,
                                max: 30,
                                message: 'The name must be more than 6 and less than 30 characters long'
                            },
                            regexp: {
                                regexp: /^[a-zA-Z0-9_]+$/,
                                message: 'The name can only consist of alphabetical, number and underscore'
                            }
                        }
                    },
                    email: {
                        validators: {
                            notEmpty: {
                                message: 'The email is required and cannot be empty'
                            },
                            emailAddress: {
                                message: 'The input is not a valid email address'
                            }
                        }
                    },
                    contact: {
                        validators: {
                            notEmpty: {
                                message: 'The contact is required and cannot be empty'
                            },
                            integer: {
                                message: 'The contact should be digit'
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
