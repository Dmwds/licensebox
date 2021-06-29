<?php 
header('Cache-Control: no-cache'); 
require_once '../includes/lb_helper.php'; // Include LicenseBox external/client API helper file
$api = new LicenseBoxAPI(); // Initialize a new LicenseBoxAPI object
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8"/>
    <title>MyScript - Updater</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.8.2/css/bulma.min.css" crossorigin="anonymous"/>
    <style type="text/css">
      body, html {
        background: #F4F5F7;
      }
    </style>
  </head>
  <body>
    <?php
      $update_data = $api->check_update(); // First let's check if there are any updates available or not
    ?>
    <div class="container" style="padding-top: 20px;"> 
      <div class="section">
        <div class="columns is-centered">
          <div class="column is-two-fifths">
            <center>
              <h1 class="title" style="padding-top: 20px;">MyScript Updater</h1><br>
            </center>
            <div class="box">
              <?php if($update_data['status']){ ?>
                <article class="message is-success">
                  <div class="message-body">
                    Please backup your database and script files before upgrading.
                  </div>
                </article>
              <?php } ?>
              <p class="subtitle is-5" style="margin-bottom: 0px">
                <?php 
                  echo $update_data['message']; // You can also show update notification/summary here instead.
                ?>
              </p>
              <div class='content'>
                <?php if($update_data['status']){ ?>
                  <p><?php echo $update_data['changelog']; ?></p><?php 
                  $update_id = null;
                  $has_sql = null;
                  $version = null;
                  if(!empty($_POST['update_id'])){
                    $update_id = strip_tags(trim($_POST["update_id"]));
                    $has_sql = strip_tags(trim($_POST["has_sql"]));
                    $version = strip_tags(trim($_POST["version"]));
                    echo '<progress id="prog" value="0" max="100.0" class="progress is-success" style="margin-bottom: 10px;"></progress>';
                    // Once we have the update_id we can use LicenseBoxAPI's download_update() function for downloading and installing the update.
                    $api->download_update(
                      $_POST['update_id'],
                      $_POST['has_sql'], 
                      $_POST['version'], 
                      null, // Pass license code if you don't want to use the local .lic file
                      null, // Pass client name if you don't want to use the local .lic file
                      array(
                        'db_host' => 'localhost', // Pass your database hostname for update sql import
                        'db_user' => '', // Pass your database username for update sql import
                        'db_pass' => '', // Pass your database password for update sql import
                        'db_name' => '' // Pass your database name for update sql import
                      )
                    );
                  }else{ ?>
                    <form action="index.php" method="POST">
                      <input type="hidden" class="form-control" value="<?php echo $update_data['update_id']; ?>" name="update_id">
                      <input type="hidden" class="form-control" value="<?php echo $update_data['has_sql']; ?>" name="has_sql">
                      <input type="hidden" class="form-control" value="<?php echo $update_data['version']; ?>" name="version">
                      <center>
                        <button type="submit" class="button is-warning is-rounded">Download & Install Update</button>
                      </center>
                    </form><?php 
                  }
                } ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="content has-text-centered">
      <p>Copyright <?php echo date('Y'); ?> CodeMonks, All rights reserved.</p><br>
    </div>
  </body>
</html>