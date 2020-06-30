

<?php
require_once 'inc/header.php';
//	require_once 'inc/silder.php';
?>
<?php
        error_reporting(0);
        $accessToken = Session::get('accesstoken');
        $userData = Session::get('userData');
        $customer_id = Session::get('customer_id');
        $facebookId = $userData['id']; 
        if(isset($_POST['ordersubmit']) && isset($facebookId)) {
            if($_POST['phoneFacebook'] == ' ') {
                 echo '<h3 style ="margin-left: 75%;margin-top: 4px;padding-top: -41px;color: red; ">Vui lòng điền đủ thông tin</h3>' ;
            }else{

                $phone = $_POST['phoneFacebook'] ;
                $insertCustomerFb = $customer->insertFacebook($userData, $phone);
                $insertOrder = $cart->insert_order_FB($facebookId);
                $deteleCart = $cart->delete_all_in_cart();
                header('location:success.php');
            }
        }
        else if (isset($_POST['ordersubmit']) && isset($customer_id)) {
            $insertOrder = $cart->insert_order($customer_id);
            $deteleCart = $cart->delete_all_in_cart();
            header('location:success.php');
        }

?>
<style>
    .box_left {
        width: 60%;
        border: 1px solid;
        float: left;
        padding: 3px;
        margin-top: 12px;

    }

    .box_right {
        width: 37%;
        border: 1px solid;
        float: right;
        padding: 3px;
        margin-top: 12px;
        margin-bottom: 20px;
    }

    .ordersubmit {
        padding: 5px;
        background: red;
        font-size: 20px;
        color: fff;
        border: none;
        width: 100px;
        margin-left: 73%;
        border-radius: 10%;
        cursor: pointer;
    }

    .ordersubmit:hover {
        background: black;
    }

    .content_top {
        display: contents;
    }
</style>


<form action="" method="POST">
<div class="main">
    <div class="content">
        <div class="section group">
            <div class="content_top">
                <div class="heading">
                    <h3>Payment Offline</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="clear"></div>
    <div class="box_left">
        <table class="tblone">
            <tr>
                <th width="5%">ID</th>
                <th width="25%">Product Name</th>
                <th width="10%">Image</th>
                <th width="25%">Price</th>
                <th width="5%">Quantity</th>
                <th width="30%">Total Price</th>
            </tr>
            <?php
            //Hiển thị sản phẩm
            $get_product_cart = $cart->get_product_cart();
            if ($get_product_cart) {
                $i = 0;
                $subtotal = 0;
                while ($result = $get_product_cart->fetch_assoc()) {
                    $i++
            ?>
                    <tr>
                        <td><?php echo  $i; ?></td>
                        <td><?php echo  $result['productName']; ?></td>
                        <td><img src="admin/uploads/<?php echo  $result['image_cart']; ?>" alt="" /></td>
                        <td><?php echo $fm->formatDollars($result['price']); ?></td>
                        <td>
                            <input type="hidden" name="cartid" value="<?php echo $result['cartId']; ?>">
                            <?php echo  $result['quantity']; ?>
                        </td>
                        <td>
                            <?php
                            $total = $result['price'] *  $result['quantity'];
                            if (isset($total)) echo $fm->formatDollars($total);

                            ?></td>
                    </tr>

            <?php
                    $subtotal += $total;
                }
            }
            ?>

        </table>

        <table style="float:right;text-align:left;" width="40%">
            <tr>
                <th>Grand Total :</th>
                <td>
                    <?php
                    if (isset($subtotal)) {
                        echo $fm->formatDollars($grandToltal = $subtotal);
                        session::set('sum', $grandToltal);
                    }
                    ?>
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="box_right">
    <table class="tblone">
        <?php
        $id = Session::get('customer_id');
        $check_profileCustomer = $customer->show_profile_customer($id);
        if ($check_profileCustomer) {
            while ($result = $check_profileCustomer->fetch_assoc()) {
        ?>
    
                <tr>
                    <td>Name</td>
                    <td><?php echo  $result["fullname"]; ?></td>
                </tr>
                <tr>
                    <td>Addrees</td>
                    <td><?php echo  $result["address_cs"]; ?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><?php echo  $result["email"]; ?></td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td><?php echo  $result["phone"]; ?></td>

                </tr>
            <?php
            }
        } else if (isset($accessToken)) {
            
            ?>
           
                <tr>
                    <td>Name</td>
                    <td><?php echo $userData["name"]  ?> </td>
                </tr>
                <tr>
                    <td>Addrees</td>
                    <td> <?php echo  $userData["location"]["name"] ?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td> <?php echo  $userData["email"] ?></td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td><input type="text" name="phoneFacebook" value="<?php echo $phone ?>"> </td>
                </tr>
                                                                  
            <?php
        }
            ?>
    </table>


</div>
</div>
</div>
     <input type="submit" name="ordersubmit" class="ordersubmit" , value="ORDER">
            </form> 

<!-- <a href="?orderid=order" class="ordersubmit">ORDER</a> -->

</div>
</div>

<?php
require_once 'inc/footer.php';
?>






