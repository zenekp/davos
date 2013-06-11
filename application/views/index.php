<h1><a href="#" onclick="login();" id="fb_connect">Connect to Facebook</a></h1>

<?php if ( isset($flash) && ! empty($flash) ) : ?>
  <h2><?php echo $flash ?></h2>
<?php endif ?>

<form action="/davos/app/setup" method="post" accept-charset="utf-8" class="js-fb-details" >
  <input type="hidden" name="access_token" value="" id="fb_access_token" />
  <input type="hidden" name="signed_request" value="" id="fb_signed_request" />
  <input type="hidden" name="user_id" value="" id="fb_user_id"/>
  <input type="submit" value="START" id="submit-button" onclick="start();" style="display:none;" />
</form>

<h1 id="start_message" style="display:none;">Creating Video...</h1>