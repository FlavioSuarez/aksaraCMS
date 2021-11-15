<div class="jumbotron jumbotron-fluid bg-light gradient">
	<div class="container">
		<div class="text-center text-md-left">
			<h3 class="mb-0<?php echo (!$meta->description ? ' mt-3' : null); ?>">
				<?php echo $meta->title; ?>
			</h3>
			<p class="lead">
				<?php echo truncate($meta->description, 256); ?>
			</p>
		</div>
	</div>
</div>

<div class="container pt-3 pb-3">
	<div class="row">
		<div class="col-md-10 offset-1">
			<?php
				if($results)
				{
					foreach($results as $key => $val)
					{
						echo '
							<blockquote class="blockquote' . ($key ? ' mt-5' : null) . '">
								<a href="' . go_to($val->announcement_slug) . '" class="--xhr">
									<h5 class="mb-0">
										' . $val->title . '
									</h5>
								</a>
								<p class="lead">
									' . truncate($val->content, 160) . '
								</p>
								<footer class="blockquote-footer">
									' . phrase('valid_until') . ' ' . $val->end_date . '
								</footer>
							</blockquote>
						';
					}
					
					echo $template->pagination;
				}
				else
				{
					echo '
						<div class="alert alert-warning">
							<i class="mdi mdi-information"></i>
							' . phrase('no_announcement_is_available') . '
						</div>
					';
				}
			?>
		</div>
	</div>
</div>
