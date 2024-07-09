<link rel="stylesheet" href="../css/sidebar.css">
<div class="sidebar">
    <ul>
        <?php if (isset($_SESSION['Admin']) && $_SESSION['Admin']==1):?>
        <li><a href="../admin/dashboard.php"><i class="fas fa-comments"><img src="../assets/img/menu-dots.png" alt="Home"
                        class="icon"></i><span class="link-text">Admin</span></a></li>
        <?php endif ?>
        <hr>
        <li><a href="../index.php"><i class="fas fa-comments"><img src="../assets/img/comment.png" alt="Home"
                        class="icon"></i><span class="link-text">Forum</span></a></li>
        <hr>
        <li><a href="../bookmarks.php"><i class="fas fa-question"></i><img src="../assets/img/bookmark.png" alt="Help"
                    class="icon"><span class="link-text">bookmarks</span></a></li>
        <hr>
        <li><a href="#" onclick="shareContent()"><i class="fas fa-share-alt"><img src="../assets/img/share.png" alt="Home"
                        class="icon"></i><span class="link-text">Share</span></a></li>
        <hr>
        <li><a href="about.php"><i class="fas fa-info-circle"><img src="../assets/img/question.png" alt="About"
                        class="icon"></i><span class="link-text">About</span></a></li>
        <hr>
        <li><a href="contact.php"><i class="fas fa-envelope"><img src="../assets/img/phone-call.png" alt="Contact"
                        class="icon"></i><span class="link-text">Contact Us</span></a></li>
        <hr>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"><img src="../assets/img/exit.png" alt="Home"
                        class="icon"></i><span class="link-text">Logout</span></a></li>
        <hr>
    </ul>
</div>