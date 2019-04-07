<?php
require_once '../../config/config.php';
include 'head.php';
include 'menu.php';


$get_foo="SELECT * from `foo`";
$query=@mysqli_query($db,"SET NAMES utf8");
$query=@mysqli_query($db,"SET CHARACTER SET utf8");
$query=@mysqli_query($db,$get_foo);

//SELECT `restaurants` FROM `foo` WHERE city='fa' AND categories ='b'
/*
                        if(isset($city) && isset($categories)){
                        $rest = query("SELECT `restaurants` FROM `foo` WHERE city='fa' AND categories ='b'");
                        while ($result=mysqli_fetch_array($result)):?>
                        <option><?php echo $result?></option>
                <?php endwhile;}
                else echo "شهر و نوع غذا انتخاب کنید";*/
?>


<main class="page-content">
    <?php
    function display_errors($errors){
        $display = '<ul class="bg-danger">';
        foreach ($errors as $error){
            $display .='<li>'.$error.'</li>';
        }
        $display .= '</ul>';
        return $display;
    }
    if(isset($_GET['delete'])){
        $id = $_GET['delete'];
        $delete = mysqli_query($db, "DELETE FROM `foo` WHERE `id` = $id");
        if($delete) {
            header('Location: food.php');
        }
    }
    ?>
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12 col-md-12">
                <h4 id="foodl">غذا ها </h4><br>
            </div>
        </div>
        <?php


        if(isset($_GET['add'])|| isset($_GET['edit'])) {
            $name = ((isset($_POST['name']) && $_POST['name'] != '') ? $_POST['name'] : '');
            $city =((isset($_POST['city'])) && !empty($_POST['city']) ? $_POST['city'] : '');
            $restaurants = ((isset($_POST['restaurants'])) && !empty($_POST['restaurants']) ? $_POST['restaurants'] : '');
            $price = ((isset($_POST['price']) && ($_POST['price'] != '')) ? $_POST['price'] : '');
            $categories = ((isset($_POST['categories'])) && !empty($_POST['categories']) ? $_POST['categories'] : '');
            $description = ((isset($_POST['description']) && $_POST['description'] != '') ? $_POST['description'] : '');
            $save_image = '';

            if (isset($_GET['edit'])) {
                $edit_id = $_GET['edit'];
                echo $edit_id ;
                $productResult = $db->query("SELECT * FROM `foo` WHERE id = $edit_id");
                $productEdit = mysqli_fetch_assoc($productResult);
                if (isset($_GET['delete_image'])) {
                    $image_url = $_SERVER['DOCUMENT_ROOT'] . $productEdit['image'];
                    echo $image_url;
                    unset($image_url);
                    $db->query("UPDATE `foo` SET image=''WHERE id=$edit_id");
                    header('Location: foo.php?edit=' . $edit_id);

                }
                $name = ((isset($_POST['name']) && $_POST['name'] != '') ? $_POST['name'] : $productEdit['name']);
                $city = ((isset($_POST['city']) && $_POST['city'] != '') ? $_POST['city'] : $productEdit['city']);
                $restaurants = ((isset($_POST['restaurants']) && $_POST['restaurants'] != '') ? $_POST['restaurants'] : $productEdit['restaurants']);
                $price = ((isset($_POST['price']) && $_POST['price'] != '') ? $_POST['price'] : $productEdit['price']);
                $categories = ((isset($_POST['categories']) && !empty($_POST['categories'])) ? $_POST['categories'] : $productEdit['categories']);
                $description = ((isset($_POST['description']) && $_POST['description'] != '') ? $_POST['description'] : $productEdit['description']);
                $save_image = (($productEdit['image'] != '') ? $productEdit['image'] : '');

            }
            if (isset($_POST['add_product'])) {
                $name = $_POST['name'];
                $city = $_POST['city'];
                $categories = $_POST['categories'];
                $price = $_POST['price'];
                $restaurants = $_POST['restaurants'];
                $description = $_POST['description'];
                $image = '';
////////////////////////////////////
///

                $dbPath = '';
                $errors = array();
                $required = array('name', 'city', 'categories', 'price', 'restaurants', 'description');
                foreach ($required as $field) {
                    if ($_POST[$field] == '') {
                        $errors[] = 'All Filed are required';
                        break;
                    }
                }


//--------------------------------------------------------


                if (!empty($_FILES)) {

                    $Allowextension = array("jpeg", "jpg", "png");
                    $FileExtension = explode(".", $_FILES["image"]["name"]);
                    $extension = end($FileExtension);
                    echo $extension;
                    if (in_array($extension, $Allowextension) && ($_FILES["image"]["size"] <= 20971520)) {
                        if ($_FILES["image"]["error"] == 0) {
                            $c_image = $_FILES['image']['name'];
                            $c_image_tmp = $_FILES['image']['tmp_name'];
                            $new_address_image = '/restaurnats/admin_menu/admin_menu/images/' . $c_image;
                            move_uploaded_file($c_image_tmp, $new_address_image);

                        } else {

                            array_push($errors, "فایل به درستی آپلود نشد!!!");
                        }
                    } else {
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
                        $Add = mysqli_query($db, "UPDATE `foo`  SET  `name`='$name', `city`='$city', `restaurants`='$restaurants', `image`='$dbPath', `description`='$description', `price`='$price' WHERE `id` =$edit_id" );
                    }else{
                        $Add = mysqli_query($db, "INSERT INTO `foo`( `name`, `city`, `restaurants`, `price`, `categories`, `image`, `description`) VALUES ('$name','$city','$restaurants','$price','$categories','$dbPath','$description')");

                    }
                    if ($Add) {
                        header('Location: food.php');
                    } else {
                        echo 'Failed';
                    }
                }
            }
            ?>


            <form action="food.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1')?>" method="post" enctype="multipart/form-data">
                <div class="form-group col-md-3">
                    <label for="name">غذا: </label>
                    <input type="text" name="name" class="form-control" value="<?=$name?>" >

                </div>
                <div class="form-group col-md-3">
                    <label for="city">شهر: </label>
                    <select type="text" name="city" class="form-control">
                        <option value="<?=$city?>"></option>
                        <option>قم</option>
                        <option>تهران</option>
                        <option>fa</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="categories">نوع :</label>
                    <select type="text" name="categories" class="form-control">
                        <option value="<?=$categories?>" name="categories"></option>
                        <option>سنتی</option>
                        <option>فست فود</option>
                        <option>آ</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="restaurants">رستوران : </label>
                    <select type="text" name="restaurants" class="form-control">
                        <option value="<?=$restaurants?>"></option>
                        <?php
                      //  if(isset($_POST['city'])) {
                            $get_cat = "select * from `restaurants`";
                            $run_cat = @mysqli_query($db, "SET NAME utf8");
                            $run_cat = @mysqli_query($db, "SET CHARACTER SET utf8");
                            $run_cat = mysqli_query($db, $get_cat);
                            while ($row_cat = mysqli_fetch_array($run_cat)) {
                                $cat_id = $row_cat['id'];
                                $cat_title = $row_cat['city'];
                                echo "<option value='$cat_id'>$cat_title</option>";
                            }
                       // }
                        ?>

                    </select>
                </div>

                <div class="form-group col-md-3">
                    <labesl for="price">قيمت: </labesl>
                    <input type="text" name="price" class="form-control" value="<?=$price?>">
                </div>

                <div class="form-group col-md-3">
                    <?php if($save_image !='') :?>
                        <div class="save-image"><img src="<?= $save_image?>" alt="saved image" class="image-saved">
                            <a  href="food.php?delete_image=1&edit=<?=$edit_id?>" class="text-danger">Delete_Photo</a>
                        </div>

                    <?php else :?>
                        <label for="image">عکس غذا: </label>
                        <input type="file" name="image" class="form-control">
                    <?php endif;?>
                </div>
                <div class="form-group col-md-3">
                    <label for="description">توضیح : </label>
                    <textarea type="text" name="description" class="form-control"><?=$description?></textarea>
                </div>

                <div class="form-group pull-right">
                    <a href="food.php" class="btn btn-default" >Cancel</a>
                    <input type="submit" value="<?=((isset($_GET['edit']))?'Edit ':'Add ')?>Food" class=" btn btn-success" name="add_product">

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
                            <th>قیمت</th>
                            <th>نوع</th>
                            <th>رستوان</th>
                            <th>شهر</th>
                            <th>غذا</th>
                            <th><i class="fas fa-users"></i></th>
                            </thead>
                            <hr><br>
                            <tbody>
                            <?php while ($product = mysqli_fetch_assoc($query)) { ?>
                                <tr>
                                    <td><p data-placement="top" data-toggle="tooltip" title="Delete"><a href="food.php?delete='<?=$product['id'];?>'"><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete"  ><i class="fas fa-trash" ></button></a></p></td>
                                    <td><p data-placement="top" data-toggle="tooltip" title="Edit"><a href="food.php?edit='<?=$product['id'];?>'"><button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit" ><i class="far fa-edit"></i></button></a></p></td>
                                    <td><?=$product['price']?></td>
                                    <td><?=$product['categories'];?></td>
                                    <td><?= $product['restaurants'] ?></td>
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
