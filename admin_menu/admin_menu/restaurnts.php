<?php
require_once '../../config/config.php';
include 'head.php';
include 'menu.php';

$get_rest="SELECT * from `restaurants`";

$query=@mysqli_query($db,"SET NAMES utf8");
$query=@mysqli_query($db,"SET CHARACTER SET utf8");
$query=@mysqli_query($db,$get_rest);

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
        $delete = mysqli_query($db, "DELETE FROM `restaurants` WHERE `id` = $id");
        if($delete) {
            header('Location: restaurnts.php');
        }
    }
    ?>
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12 col-md-12">
                <h4 id="foodl">رستوران جدید </h4><br>
            </div>
        </div>
        <?php


        if(isset($_GET['add'])|| isset($_GET['edit'])) {
            $name = ((isset($_POST['name']) && $_POST['name'] != '') ? $_POST['name'] : '');
            $city = ((isset($_POST['city']) && $_POST['city'] != '') ? $_POST['city'] : '');
            $address = ((isset($_POST['address']) && $_POST['address'] != '') ? $_POST['address'] : '');
            $phone = ((isset($_POST['phone']) && ($_POST['phone'] != '')) ? $_POST['phone'] : '');
            $categories = ((isset($_POST['categories'])) && !empty($_POST['categories']) ? $_POST['categories'] : '');
            $description = ((isset($_POST['description']) && $_POST['description'] != '') ? $_POST['description'] : '');
            $save_image = '';


            if (isset($_GET['edit'])) {
                $edit_id = $_GET['edit'];
                echo $edit_id ;
                $productResult = $db->query("SELECT * FROM `restaurants` WHERE id = $edit_id");
                $productEdit = mysqli_fetch_assoc($productResult);
                if (isset($_GET['delete_image'])) {
                    $image_url = $_SERVER['DOCUMENT_ROOT'] . $productEdit['image'];
                    echo $image_url;
                    unset($image_url);
                    $db->query("UPDATE `restaurants` SET image=''WHERE id=$edit_id");
                    header('Location: restaurnts.php?edit=' . $edit_id);

                }
                $name = ((isset($_POST['name']) && $_POST['name'] != '') ? $_POST['name'] : $productEdit['name']);
                $city = ((isset($_POST['city']) && $_POST['city'] != '') ? $_POST['city'] : $productEdit['city']);
                $address = ((isset($_POST['address']) && $_POST['address'] != '') ? $_POST['address'] : $productEdit['address']);
                $phone = ((isset($_POST['phone']) && $_POST['phone'] != '') ? $_POST['phone'] : $productEdit['phone']);
                $categories = ((isset($_POST['categories']) && !empty($_POST['categories'])) ? $_POST['categories'] : $productEdit['categories']);
                $description = ((isset($_POST['description']) && $_POST['description'] != '') ? $_POST['description'] : $productEdit['description']);
                $save_image = (($productEdit['image'] != '') ? $productEdit['image'] : '');

            }
            if (isset($_POST['add_product'])) {
                $name = $_POST['name'];
                $city = $_POST['city'];
                $categories = $_POST['categories'];
                $phone = $_POST['phone'];
                $address = $_POST['address'];
                $description = $_POST['description'];
                $image = '';
////////////////////////////////////
///

                $dbPath = '';
                $errors = array();
                $required = array('name', 'city', 'categories', 'phone', 'address', 'description');
                foreach ($required as $field) {
                    if ($_POST[$field] == '') {
                        $errors[] = 'All Filed are required';
                        break;
                    }
                }


//--------------------------------------------------------


                if (!empty($_FILES)) {

                    $Allowextension = array("jpeg" , "jpg" , "png");
                    $FileExtension=explode(".",$_FILES["image"]["name"]);
                    $extension=end($FileExtension);
                    echo $extension;
                    if(in_array($extension,$Allowextension )&&($_FILES["image"]["size"]<=20971520))
                    {
                        if($_FILES["image"]["error"]==0)
                        {
                            $c_image = $_FILES['image']['name'];
                            $c_image_tmp = $_FILES['image']['tmp_name'];
                            $new_address_image ='/restaurnats/admin_menu/admin_menu/images/' .$c_image;
                            move_uploaded_file($c_image_tmp,$new_address_image);

                        }else{

                            array_push($errors, "فایل به درستی آپلود نشد!!!");
                        }
                    }else{
                        array_push($errors, "تصویر مناسب را انتخاب کنید! پسوند مجاز برای تصویر شامل jpeg و jpg و png می باشد و حجم آن نباید بیشتر از 2 مگابایت باشد!!!");
                    }

                }

//-------------------------------------------------------------------------------

                if (!empty($errors)) {
                    echo display_errors($errors);
                } else {

                    //if (!empty($_FILES)) {
                    //    move_uploaded_file($tmpLoc, $uploadPath);
                    //  }
                    if(isset($_GET['edit'])){
                        $Add = mysqli_query($db, "UPDATE `restaurants`  SET  `name`='$name', `city`='$city', `address`='$address', `image`='$dbPath', `description`='$description', `phone`='$phone' WHERE `id` =$edit_id" );
                    }else{
                        $Add = mysqli_query($db, "INSERT INTO `restaurants`( `name`, `city`, `address`, `phone`, `categories`, `image`, `description`) VALUES ('$name','$city','$address','$phone','$categories','$dbPath','$description')");

                    }
                    if ($Add) {
                        header('Location: restaurnts.php');
                    } else {
                        echo 'Failed';
                    }
                }
            }
            ?>


            <form action="restaurnts.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1')?>" method="post" enctype="multipart/form-data">
                <div class="form-group col-md-3">
                    <label for="name">رستوران </label>
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
                </div>
                <div class="form-group col-md-3">
                    <label for="categories">نوع :</label>
                    <select type="text" name="categories" class="form-control">
                        <option value="<?=$categories?>"></option>
                        <option>a</option>
                        <option>b</option>
                        <option>c</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <?php if($save_image !='') :?>
                        <div class="save-image"><img src="<?= $save_image?>" alt="saved image" class="image-saved">
                            <a  href="restaurants.php?delete_image=1&edit=<?=$edit_id?>" class="text-danger">Delete_Photo</a>
                        </div>

                    <?php else :?>
                        <label for="image">عکس رستوران: </label>
                        <input type="file" name="image" class="form-control">
                    <?php endif;?>
                </div>
                <div class="form-group col-md-3">
                    <label for="description">توضیح : </label>
                    <textarea type="text" name="description" class="form-control"><?=$description?></textarea>
                </div>

                <div class="form-group pull-right">
                    <a href="restaurnts.php" class="btn btn-default" >Cancel</a>
                    <input type="submit" value="<?=((isset($_GET['edit']))?'Edit ':'Add ')?>Restaurant" class=" btn btn-success" name="add_product">

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
                        <th>رستوران</th>
                        <th><i class="fas fa-users"></i></th>
                        </thead>
                        <hr><br>
                        <tbody>
                        <?php while ($product = mysqli_fetch_assoc($query)) { ?>
                            <tr>
                                <td><p data-placement="top" data-toggle="tooltip" title="Delete"><a href="restaurnts.php?delete='<?=$product['id'];?>'"><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete"  ><i class="fas fa-trash" ></button></a></p></td>
                                <td><p data-placement="top" data-toggle="tooltip" title="Edit"><a href="restaurnts.php?edit='<?=$product['id'];?>'"><button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit" ><i class="far fa-edit"></i></button></a></p></td>
                                <td><?=$product['phone']?></td>
                                <td><?=$product['categories'];?></td>
                                <td><?= $product['address'] ?></td>
                                <td><?=$product['city'] ?></td>
                                <td><?= $product['name']; ?></td>
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