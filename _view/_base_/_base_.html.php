<!DOCTYPE html>
<html lang="<?=LANG?>">
    <head>
        <meta charset="UTF-8" />
        <meta name="author" content="Piseth Kem">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />

        <title><?= $title.' - '.SITE_NAME ?></title>
        <link href="https://fonts.googleapis.com/css?family=Dangrek&amp;subset=khmer" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="/static/css/app.css" rel="stylesheet">
        <link href="/static/css/main.css" rel="stylesheet">
    </head>
    <body page="<?= @$page ?>">
        <div class="wrapper">
            <header>
                <span class="menu-icon"> &#9776; ម៉ឺនុយ </span>
            </header>
            <nav id="nav-main">
                <!-- Shown on all pages -->
                <a href="/">ទំព័រដើម</a>
                <a href="/human-resource">ធនធានមនុស្ស</a>

                <!-- Page specific navs -->
                <?= @$nav ?>

                <!-- Shown on system pages -->
                <?php if(@$_SESSION['isSuperUser']): ?>
                    <a href="/manage-system">គ្រប់គ្រងប្រព័ន្ធ</a>
                <?php endif; ?>

                <!-- Account related navs -->
                <?php if(isset($_SESSION['userid'])): ?>
                    <a id="account" href="/account">គណនី</a>
                    <a id="logout" class="caution" href="/logout">ចាកចេញ</a>
                <?php endif; ?>
            </nav>
            <main class="group">
                <div id="main-left">
                    <section id="section-main">
                        <div class="message"><?= @$_SESSION['message'] ?></div>
                        <?= @$section ?>
                    </section>
                    <article>
                    </article>
                </div>
                <div id="main-right">
                    <aside>
                    </aside>
                </div>
            </main>
            <footer>
                <div id="copyright" title="កែម ពិសិដ្ឋ [017 228 500]">
                    &copy; <?php echo date('Y').' '.SITE_NAME ?> - រក្សារសិទ្ធគ្រប់យ៉ាង
                </div>
            </footer>
        </div>

        <script src="/static/js/app.js"></script>
        <script src="/static/js/main.js"></script>
        <?= @$js ?>
    </body>
</html>
