<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <!-- Home Nav -->
        <li class="nav-item">
            <a class="nav-link <?php echo (uri_string() == '') ? "" : "collapsed" ?>" href=".">
                <i class="bi bi-grid"></i>
                <span>Home</span>
            </a>
        </li>

        <!-- Keranjang Nav -->
        <li class="nav-item">
            <a class="nav-link <?php echo (uri_string() == 'keranjang') ? "" : "collapsed" ?>" href="keranjang">
                <i class="bi bi-cart-check"></i>
                <span>Keranjang</span>
            </a>
        </li>

        <!-- Produk and Transaksi Nav for Admin -->
        <?php if (session()->get('role') == 'admin') { ?>
            <li class="nav-item">
                <a class="nav-link <?php echo (uri_string() == 'produk') ? "" : "collapsed" ?>" href="produk">
                    <i class="bi bi-receipt"></i>
                    <span>Produk</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (uri_string() == 'transaksi') ? "" : "collapsed" ?>" href="transaksi">
                    <i class="bi bi-credit-card"></i>
                    <span>Transaksi</span>
                </a>
            </li>
        <?php } ?>

        <!-- Profile Nav -->
        <li class="nav-item">
            <a class="nav-link <?php echo (uri_string() == 'profile') ? "" : "collapsed" ?>" href="profile">
                <i class="bi bi-person"></i>
                <span>Profile</span>
            </a>
        </li>

        <!-- FAQ Nav -->
        <li class="nav-item">
            <a class="nav-link <?php echo (uri_string() == 'faq') ? "" : "collapsed" ?>" href="faq">
                <i class="bi bi-question-circle"></i>
                <span>FAQ</span>
            </a>
        </li>

        <!-- Contact Nav -->
        <li class="nav-item">
            <a class="nav-link <?php echo (uri_string() == 'contact') ? "" : "collapsed" ?>" href="contact">
                <i class="bi bi-envelope"></i>
                <span>Contact</span>
            </a>
        </li>

    </ul>

</aside><!-- End Sidebar -->
