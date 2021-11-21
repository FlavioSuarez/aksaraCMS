<style type="text/css">
	#footer-wrapper
	{
		display: none
	}
</style>
<div class="full-height d-flex align-items-center justify-content-center">
	<div class="container-fluid pt-3 pb-3">
		<form action="<?php echo current_page(); ?>" method="POST" class="--validate-form" enctype="multipart/form-data">
			<div class="pr-2 pl-2">
				<div class="form-group">
					<input type="text" name="username" class="form-control" id="username_input" placeholder="<?php echo phrase('enter_your_username_or_email'); ?>" />
				</div>
				<div class="form-group relative">
					<input type="password" name="password" class="form-control" id="password_input" placeholder="<?php echo phrase('enter_password'); ?>" autocomplete="new-password" />
					<i class="mdi mdi-2x password-peek text-muted mdi-eye-outline absolute top right mt-2 mr-2" data-parent=".form-group" data-peek=".form-control" style="z-index:3"></i>
				</div>
				<?php
					if($years)
					{
						$option						= null;
						
						foreach($years as $key => $val)
						{
							$option					.= '<option value="' . $val->value . '"' . ($val->selected ? ' selected' : null) . '>' . $val->label . '</option>';
						}
						
						echo '
							<div class="form-group">
								<select name="year" class="form-control" placeholder="' . phrase('choose_year') . '" id="year_input">
									' . $option . '
								</select>
							</div>
						';
					}
				?>
				
				<div class="--validation-callback mb-3"></div>
				
				<div class="form-group">
					<label class="mt-2">
						<a href="<?php echo current_page('forgot'); ?>" class="--xhr">
							<?php echo phrase('forgot_password'); ?>
						</a>
					</label>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary btn-block">
						<i class="mdi mdi-check"></i> 
						<?php echo phrase('sign_in'); ?>
					</button>
				</div>
			</div>
		</form>
		<?php if(get_setting('frontend_registration')){ ?>
			<div class="pr-2 pl-2">
				<p class="text-center text-muted">
					<?php echo phrase('do_not_have_an_account'); ?>
					<a href="<?php echo base_url('auth/register'); ?>" class="--xhr">
						<b>
							<?php echo phrase('register_an_account'); ?>
						</b>
					</a>
				</p>
			</div>
		<?php } ?>
		<?php if(get_setting('frontend_registration') && ((get_setting('google_client_id') && get_setting('google_client_secret')) || (get_setting('facebook_app_id') && get_setting('facebook_app_secret')))){ ?>
			<div class="pr-2 pl-2">
				<p class="text-center text-muted pt-2">
					<?php echo phrase('or_sign_in_with_your_social_account'); ?>
				</p>
				<div class="row">
					<?php if(get_setting('google_client_id') && get_setting('google_client_secret')) { ?>
					<div class="col-6">
						<p>
							<a href="<?php echo base_url('auth/google'); ?>" class="btn btn-outline-danger btn-block btn-sm">
								<i class="mdi mdi-google"></i>
								<?php echo phrase('google'); ?>
							</a>
						</p>
					</div>
					<?php } ?>
					<?php if(get_setting('facebook_app_id') && get_setting('facebook_app_secret')) { ?>
					<div class="col-6">
						<p>
							<a href="<?php echo base_url('auth/facebook'); ?>" class="btn btn-outline-primary btn-block btn-sm">
								<i class="mdi mdi-facebook"></i>
								<?php echo phrase('facebook'); ?>
							</a>
						</p>
					</div>
					<?php } ?>
				</div>
			</div>
		<?php } ?>
	</div>
</div>
