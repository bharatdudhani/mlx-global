<?php
$page_title = "Donate - MLX Consultancy";
include 'header.php';
?>

    <!-- Donate Section -->
    <div class="donate-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <h1 class="mb-4"><?php echo __t('donate_title'); ?></h1>
                    <p class="lead mb-5"><?php echo __t('donate_sub'); ?></p>
                    
                    <div class="donate-options">
                        <div class="donate-option">
                            <h3><?php echo __t('donate_wechat'); ?></h3>
                            <img src="wechat_qr_code.png" alt="WeChat QR Code" class="qr-code img-fluid">
                            <p><?php echo __t('donate_wechat_scan'); ?></p>
                        </div>
                        
                        <div class="donate-option">
                            <h3><?php echo __t('donate_alipay'); ?></h3>
                            <img src="alipay_qr_code.png" alt="Alipay QR Code" class="qr-code img-fluid">
                            <p><?php echo __t('donate_alipay_scan'); ?></p>
                        </div>

                        <div class="donate-option">
                            <h3><?php echo __t('donate_paypal'); ?></h3>
                            <a href="https://www.paypal.me/bharatdudhani" class="payment-button paypal" target="_blank" rel="noopener noreferrer">
                                <?php echo __t('donate_paypal_btn'); ?>
                            </a>
                            <p><?php echo __t('donate_paypal_note'); ?></p>
                        </div>
                    </div>
                    
                    <div class="mt-5">
                        <p><?php echo __t('donate_other'); ?> <a href="index.php#contact"><?php echo __t('donate_contact_us'); ?></a> <?php echo __t('donate_directly'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include 'footer.php'; ?>