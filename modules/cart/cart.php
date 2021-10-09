<script>
    function buyNow() {
        document.getElementById("frm").submit()
    }
</script>
<!--	Cart	-->
<?php
include("PHPMailer-master/src/Exception.php");
include("PHPMailer-master/src/OAuth.php");
include("PHPMailer-master/src/PHPMailer.php");
include("PHPMailer-master/src/POP3.php");
include("PHPMailer-master/src/SMTP.php");

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

if (isset($_SESSION['cart'])) {
    if (isset($_POST['sbm'])) {
        foreach ($_POST['quantity'] as $prd_id => $val) {
            $_SESSION['cart'][$prd_id] = $val;
        }
    }
    $arr = array();
    foreach ($_SESSION['cart'] as $prd_id => $qtt) {
        $arr[] = $prd_id;
    }
    $str_id = implode(", ", $arr);
    $sql = "SELECT * FROM product WHERE prd_id IN ($str_id)";
    $query = mysqli_query($conn, $sql);
?>
    <div id="my-cart">
        <div class="row">
            <div class="cart-nav-item col-lg-7 col-md-7 col-sm-12">Thông tin sản phẩm</div>
            <div class="cart-nav-item col-lg-2 col-md-2 col-sm-12">Tùy chọn</div>
            <div class="cart-nav-item col-lg-3 col-md-3 col-sm-12">Giá</div>
        </div>
        <form method="post">
            <?php
            $totals = 0;
            while ($row = mysqli_fetch_array($query)) {
                $totals_price = $_SESSION['cart'][$row['prd_id']] * $row['prd_price'];
                $totals += $totals_price
            ?>
                <div class="cart-item row">
                    <div class="cart-thumb col-lg-7 col-md-7 col-sm-12">
                        <img src="admin/img/products/<?php echo $row['prd_image'] ?>">
                        <h4><?php echo $row['prd_name'] ?></h4>
                    </div>

                    <div class="cart-quantity col-lg-2 col-md-2 col-sm-12">
                        <input type="number" name="quantity[<?php echo $row['prd_id'] ?>]" id="quantity" class="form-control form-blue quantity" value="<?php echo $_SESSION['cart'][$row['prd_id']] ?>" min="1">
                    </div>
                    <div class="cart-price col-lg-3 col-md-3 col-sm-12"><b><?php echo number_format($totals_price) ?></b><a href="modules/cart/del_cart.php?prd_id=<?php echo $row['prd_id'] ?>">Xóa</a></div>
                </div>
            <?php } ?>
            <div class="row">
                <div class="cart-thumb col-lg-7 col-md-7 col-sm-12">
                    <button id="update-cart" class="btn btn-success" type="submit" name="sbm">Cập nhật giỏ hàng</button>
                </div>
                <div class="cart-total col-lg-2 col-md-2 col-sm-12"><b>Tổng cộng:</b></div>
                <div class="cart-price col-lg-3 col-md-3 col-sm-12"><b><?php echo number_format($totals) ?></b></div>
            </div>
        </form>

    </div>
    <!--	End Cart	-->

    <!--	Customer Info	-->
    <?php
    if (isset($_POST['name']) && isset($_POST['phone']) && isset($_POST['mail']) && isset($_POST['add'])) {
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['mail'];
        $add = $_POST['add'];

        $str_body = "";
        $str_body .= '
        <p>
            <b>Khách hàng:</b> ' . $name . '<br>
            <b>Điện thoại:</b> ' . $phone . '<br>
            <b>Địa chỉ:</b> ' . $add . '<br>
        </p>
   ';
        $str_body .= '
        <table border="1" cellspacing="0" cellpadding="10" bordercolor="#305eb3" width="100%">
	        <tr bgcolor="#305eb3">
    	        <td width="70%"><b><font color="#FFFFFF">Sản phẩm</font></b></td>
                <td width="10%"><b><font color="#FFFFFF">Số lượng</font></b></td>
                <td width="20%"><b><font color="#FFFFFF">Tổng tiền</font></b></td>
            </tr>
    ';
        $sql = "SELECT * FROM product WHERE prd_id IN ($str_id)";
        $query = mysqli_query($conn, $sql);
        $totals = 0;
        while ($row = mysqli_fetch_array($query)) {
            $totals_price = $_SESSION['cart'][$row['prd_id']] * $row['prd_price'];
            $totals += $totals_price;
            $str_body .= '
            <tr>
    	        <td width="70%">' . $row['prd_name'] . '</td>
                <td width="10%">' . $_SESSION['cart'][$row['prd_id']] . '</td>
                <td width="20%">' . number_format($totals_price) . ' đ</td>
            </tr>
        ';
        }
        $str_body .= '
            <tr>
                <td colspan="2" width="70%"></td>
                <td width="20%"><b><font color="#FF0000">' . number_format($totals) . ' đ</font></b></td>
            </tr>
        </table>
    ';
        $str_body .= '
        <p>
	        Cám ơn quý khách đã mua hàng tại Shop của chúng tôi, bộ phận giao hàng sẽ liên hệ với quý khách để xác nhận sau 5 phút kể từ khi đặt hàng thành công và chuyển hàng đến quý khách chậm nhất sau 24 tiếng.
        </p>
    ';
        $mail = new PHPMailer(true);                              // Passing 'true' enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'vietpro.shop28@gmail.com';                 // SMTP username
            // $mail->Password = 'vietpr0sh0p';                           // SMTP password
            $mail->Password = 'rnqqtpbwsivtqopl';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, 'ssl' also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            //Recipients
            $mail->CharSet = 'UTF-8';
            $mail->setFrom('quantri.vietproshop@gmail.com', 'Vietpro Mobile Shop');                // Gửi mail tới Mail Server
            $mail->addAddress($email);               // Gửi mail tới mail người nhận
            //$mail->addReplyTo('ceo.vietpro@gmail.com', 'Information');
            $mail->addCC('quantri.vietproshop@gmail.com');
            //$mail->addBCC('bcc@example.com');

            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Xác nhận đơn hàng từ Vietpro Mobile Shop';
            $mail->Body    = $str_body;
            $mail->AltBody = 'Mô tả đơn hàng';

            $mail->send();
            header('location:index.php?page_layout=success');
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    ?>


    <div id="customer">
        <form method="post" id="frm">
            <div class="row">

                <div id="customer-name" class="col-lg-4 col-md-4 col-sm-12">
                    <input placeholder="Họ và tên (bắt buộc)" type="text" name="name" class="form-control" required>
                </div>
                <div id="customer-phone" class="col-lg-4 col-md-4 col-sm-12">
                    <input placeholder="Số điện thoại (bắt buộc)" type="text" name="phone" class="form-control" required>
                </div>
                <div id="customer-mail" class="col-lg-4 col-md-4 col-sm-12">
                    <input placeholder="Email (bắt buộc)" type="text" name="mail" class="form-control" required>
                </div>
                <div id="customer-add" class="col-lg-12 col-md-12 col-sm-12">
                    <input placeholder="Địa chỉ nhà riêng hoặc cơ quan (bắt buộc)" type="text" name="add" class="form-control" required>
                </div>

            </div>
        </form>
        <div class="row">
            <div class="by-now col-lg-6 col-md-6 col-sm-12">
                <a href="#" onclick="buyNow()">
                    <b>Mua ngay</b>
                    <span>Giao hàng tận nơi siêu tốc</span>
                </a>
            </div>
            <div class="by-now col-lg-6 col-md-6 col-sm-12">
                <a href="#">
                    <b>Trả góp Online</b>
                    <span>Vui lòng call (+84) 0988 550 553</span>
                </a>
            </div>
        </div>
    </div>
<?php
} else {
    echo '<div class="alert text-danger alert-danger mt-3">Giỏ hàng của bạn hiện tại không có sản phẩm nào!</div>';
}

?>
<!--	End Customer Info	-->