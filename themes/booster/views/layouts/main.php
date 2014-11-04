<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="language" content="en"/>
        
        <link rel="shortcut icon" href="/favicons/favicon.ico">
        <link rel="apple-touch-icon" sizes="57x57" href="<?php echo Yii::app()->request->baseUrl; ?>/favicons/apple-touch-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="114x114" href="<?php echo Yii::app()->request->baseUrl; ?>/favicons/apple-touch-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="72x72" href="<?php echo Yii::app()->request->baseUrl; ?>/favicons/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="144x144" href="<?php echo Yii::app()->request->baseUrl; ?>/favicons/apple-touch-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="60x60" href="<?php echo Yii::app()->request->baseUrl; ?>/favicons/apple-touch-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="120x120" href="<?php echo Yii::app()->request->baseUrl; ?>/favicons/apple-touch-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="76x76" href="<?php echo Yii::app()->request->baseUrl; ?>/favicons/apple-touch-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="152x152" href="<?php echo Yii::app()->request->baseUrl; ?>/favicons/apple-touch-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="<?php echo Yii::app()->request->baseUrl; ?>/favicons/apple-touch-icon-180x180.png">
        <link rel="icon" type="image/png" href="<?php echo Yii::app()->request->baseUrl; ?>/favicons/favicon-192x192.png" sizes="192x192">
        <link rel="icon" type="image/png" href="<?php echo Yii::app()->request->baseUrl; ?>/favicons/favicon-160x160.png" sizes="160x160">
        <link rel="icon" type="image/png" href="<?php echo Yii::app()->request->baseUrl; ?>/favicons/favicon-96x96.png" sizes="96x96">
        <link rel="icon" type="image/png" href="<?php echo Yii::app()->request->baseUrl; ?>/favicons/favicon-16x16.png" sizes="16x16">
        <link rel="icon" type="image/png" href="<?php echo Yii::app()->request->baseUrl; ?>/favicons/favicon-32x32.png" sizes="32x32">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="msapplication-TileImage" content=<?php echo Yii::app()->request->baseUrl; ?>"/favicons/mstile-144x144.png">
        <meta name="msapplication-config" content=<?php echo Yii::app()->request->baseUrl; ?>"/favicons/browserconfig.xml">
        <!--<link rel="icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico" type="image/x-icon"/>-->
        <!-- custom css -->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/myTennisApp.css"
              <!-- blueprint CSS framework -->
              <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css"
              media="screen, projection"/>
            <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css"
                  media="print"/>
            <!--[if lt IE 8]>
            <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css"
                  media="screen, projection"/>
            <![endif]-->

            <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>

    <body>

        <div class="container-fluid" id="page">
            <?php
            /* @var $user User */
            /* @var $isGuest bool */
            $isGuest = Yii::app()->user->isGuest;
            if (!$isGuest) {
                $user = User::model()->findByPk(Yii::app()->user->id);
                $userTypes = array(
                    'coach' => $user->isCoach(),
                    'sponsor' => $user->isSponsor(),
                    'athlete' => $user->isAthlete(),
                    'clubAdmin' => $user->isClubAdmin(),
                );
            }
            $this->widget('bootstrap.widgets.TbNavbar', array(
                'type' => null, // null or 'inverse'
                'brand' => Yii::app()->name,
                'collapse' => true, // requires bootstrap-responsive.css
                'items' => array(
                    array(
                        'class' => 'bootstrap.widgets.TbMenu',
                        'items' => array(
                            array(
                                'label' => 'O Meu Perfil',
                                'visible' => !$isGuest,
                                'url' => array('/user/update', 'id' => Yii::app()->user->id),
                            ),
                            array(
                                'label' => 'Atletas',
                                'visible' => !$isGuest && $user->canListAthletes(),
                                'url' => array('/user/index', 'userType' => UserType::model()->getAthlete()->primaryKey),
                            ),
                            array(
                                'label' => 'Treinadores',
                                'url' => array('/user/index', 'userType' => UserType::model()->getCoach()->primaryKey),
                                'visible' => !$isGuest && $user->canListCoaches(),
                            ),
                            array(
                                'label' => 'Clubes',
                                'url' => array('/club/index'),
                                'visible' => !$isGuest && $user->canListClubs(),
                            ),
                            array(
                                'label' => 'Horário',
                                'url' => array('/practiceSession/index', 
                                    'userID' => $isGuest ? "" : $user->primaryKey),
                                'visible' => !$isGuest,
                            ),
                            array(
                                'label' => 'Sobre',
                                'url' => array('/site/page', 'view' => 'about')
                            ),
                            array(
                                'label' => 'Login',
                                'url' => array('/site/login'), 'visible' => Yii::app()->user->isGuest
                            ),
                            array(
                                'label' => 'Logout (' . Yii::app()->user->name . ')',
                                'url' => array('/site/logout'), 'visible' => !Yii::app()->user->isGuest
                            )
                        ),
                    ),
                ),
            ));
            ?>
            <!-- mainmenu -->
            <div class="container-fluid" style="margin-top:80px">
                <?php if (isset($this->breadcrumbs)): ?>
                    <?php
                    $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
                        'links' => $this->breadcrumbs,
                    ));
                    ?><!-- breadcrumbs -->
                <?php endif ?>

                <?php echo $content; ?>
                <hr/>
                <div id="footer">
                    Copyright &copy; <?php echo date('Y'); ?> by Diogo Neves.<br/>
                    Todos os direitos reservados.<br/>
                    <?php echo Yii::powered(); ?>
                </div>
                <!-- footer -->
            </div>
        </div>
        <!-- page -->
    </body>
</html>