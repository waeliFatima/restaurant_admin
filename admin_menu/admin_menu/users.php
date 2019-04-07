<?php
require_once '../../config/config.php';
include 'head.php';
include 'menu.php';
$get_users="SELECT * from `users`";

$query=@mysqli_query($db,"SET NAMES utf8");
$query=@mysqli_query($db,"SET CHARACTER SET utf8");
$query=@mysqli_query($db,$get_users);

?>


<?/*php include 'tag.php'*/?>


<main class="page-content">
    <?php
    function display_errors($errors){
        $display = '<ul class="bg-danger">';
        foreach ($errors as $error){
            $display .='<li class=>'.$error.'</li>';
        }
        $display .= '</ul>';
        return $display;
    }
    if(isset($_GET['delete'])){
        $id = $_GET['delete'];
        $delete = mysqli_query($db, "DELETE FROM `users` WHERE `id` = $id");
        if($delete) {
            header('Location: users.php');
        }
    }
    ?>
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12 col-md-12">
                <h4 id="foodl">کاربر جدید </h4><br>
            </div>
        </div>
        <?php


        if(isset($_GET['add'])|| isset($_GET['edit'])) {
            $name = ((isset($_POST['name']) && $_POST['name'] != '') ? $_POST['name'] : '');
            $city = ((isset($_POST['city']) && $_POST['city'] != '') ? $_POST['city'] : '');
            $address = ((isset($_POST['address']) && $_POST['address'] != '') ? $_POST['address'] : '');
            $phone = ((isset($_POST['phone']) && ($_POST['phone'] != '')) ? $_POST['phone'] : '');
            $email = ((isset($_POST['email']) && ($_POST['email'] != '')) ? $_POST['email'] : '');
            $password = ((isset($_POST['password']) && ($_POST['password'] != '')) ? $_POST['password'] : '');


            if (isset($_GET['edit'])) {
                $edit_id = $_GET['edit'];
                echo $edit_id;
                $productResult = $db->query("SELECT * FROM `users` WHERE id = $edit_id");
                $productEdit = mysqli_fetch_assoc($productResult);

                $name = ((isset($_POST['name']) && $_POST['name'] != '') ? $_POST['name'] : $productEdit['full_name']);
                $city = ((isset($_POST['city']) && $_POST['city'] != '') ? $_POST['city'] : $productEdit['city']);
                $address = ((isset($_POST['address']) && $_POST['address'] != '') ? $_POST['address'] : $productEdit['address']);
                $phone = ((isset($_POST['phone']) && $_POST['phone'] != '') ? $_POST['phone'] : $productEdit['phone']);
                $email = ((isset($_POST['email']) && $_POST['email'] != '') ? $_POST['email'] : $productEdit['email']);
                $password = ((isset($_POST['password']) && $_POST['password'] != '') ? $_POST['password'] : $productEdit['password']);

            }
            if (isset($_POST['add_product'])) {
                $name = $_POST['name'];
                $city = $_POST['city'];
                $phone = $_POST['phone'];
                $address = $_POST['address'];
                $email = $_POST['email'];
                $password = $_POST['password'];


                $errors = array();
                $required = array('name', 'city', 'email', 'phone', 'address', 'password');
                foreach ($required as $field) {
                    if ($_POST[$field] == '') {
                        $errors[] = 'All Filed are required';
                        break;
                    }
                }

//-------------------------------------------------------------------------------

                if (!empty($errors)) {
                    echo display_errors($errors);
                } else {

                    if (isset($_GET['edit'])) {
                        $Add = mysqli_query($db, "UPDATE `users`  SET   `full_name`='$name', `city`='$city', `address`='$address', `password`='$password', `email`='$email', `phone`='$phone' WHERE `id` =$edit_id");
                    }
                    else{
                        $Add = mysqli_query($db, "INSERT INTO `users`( `full_name`, `city`, `address`, `phone`, `password`, `email`) VALUES ('$name','$city','$address','$phone','$password','$email')");

                    }
                    if ($Add) {
                        header('Location: users.php');
                    } else {
                        echo 'Failed';
                    }
                }
            }

            ?>


            <form action="users.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1')?>" method="post" enctype="multipart/form-data">
                <div class="form-group col-md-3">
                    <label for="name">نام و نام خوادگی: </label>
                    <input type="text" name="name" class="form-control" value="<?=$name?>" >

                </div>
                <div class="form-group col-md-3">
                    <label for="city">شهر: </label>
                    <input type="text" name="city" class="form-control" value="<?=$city?>">
                </div>

                <div class="form-group col-md-3">
                    <label for="address">ادرس : </label>
                    <input type="text" name="address" class="form-control" value="<?=$address?>">
                </div>

                <div class="form-group col-md-3">
                    <label for="phone">شماره تماس : </label>
                    <input type="text" name="phone" class="form-control" value="<?=$phone?>">
                </div><div class="form-group col-md-3">
                    <label for="phone">ایمبل : </label>
                    <input type="email" name=email class="form-control" value="<?=$email?>">
                </div><div class="form-group col-md-3">
                    <label for="phone">رمز : </label>
                    <input type="password" name="password" class="form-control" value="<?=$password?>">
                </div>

                <div class="form-group pull-right">
                    <a href="users.php" class="btn btn-default" >Cancel</a>
                    <input type="submit" value="<?=((isset($_GET['edit']))?'Edit ':'Add ')?>User" class=" btn btn-success" name="add_product">

                </div>

                <div class="clearfix"></div>
            </form>

            <div class="row">
                <div class="col-md-12">

                    <div class="table-responsive">



                    </div>
                </div>
            </div>
        <?php } else{?>
            <div class="row">
                <div class="col-md-12">

                    <div class="table-responsive">


                        <table id="mytable" class="table table-bordred table-striped">

                            <thead>
                            <th>حذف</th>
                            <th>ویرایش</th>
                            <th>شماره تماس</th>
                            <th>نوع</th>
                            <th>ادرس</th>
                            <th>شهر</th>
                            <th>نام</th>
                            <th><i class="fas fa-users"></i></th>
                            </thead>
                            <hr><br>
                            <tbody>
                            <?php while ($product = mysqli_fetch_assoc($query)) { ?>
                                <tr>
                                    <td><p data-placement="top" data-toggle="tooltip" title="Delete"><a href="users.php?delete='<?=$product['id'];?>'"><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete"  ><i class="fas fa-trash" ></button></a></p></td>
                                    <td><p data-placement="top" data-toggle="tooltip" title="Edit"><a href="users.php?edit='<?=$product['id'];?>'"><button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit" ><i class="far fa-edit"></i></button></a></p></td>
                                    <td><?=$product['phone']?></td>
                                    <td><?=$product['email'];?></td>
                                    <td><?= $product['address'] ?></td>
                                    <td><?=$product['city'] ?></td>
                                    <td><?= $product['full_name']; ?></td>
                                    <td><i class="fas fa-users"></i></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php }?>
        <!--end div container-->
    </div>

</main>

<!-- page-content" -->
</div>

<!--javascript-->
<script src="js/jquery-3.3.1.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/user.js"></script>
</body>
</html>