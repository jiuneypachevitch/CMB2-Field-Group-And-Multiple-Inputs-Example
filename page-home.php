<?php
// Template Name: Menu da Semana
?>

<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<section class="container">
			<h2 class="subtitulo"><?php the_title(); ?></h2>
			<ul>
				<?php
				/** Get the field data */
				$food_menu_items_group = get_field_cmb2('food_menu_items_group');
				/** If have data */
				if (isset($food_menu_items_group)) {
					/** listing all menu groups and items */
					foreach ($food_menu_items_group as $menu_group) {
				?>
						<div class="menu-item grid-8">
							<h2>
								<?php echo $menu_group['food_menu_items_name']; ?>
							</h2>
							<ul>
								<?php
								if (isset($menu_group['food_menu_items_item'])) {
									foreach ($menu_group['food_menu_items_item'] as $menu_item) {
										if ($menu_item['item_name'] != '') {
								?>
											<li>
												<span><sup>R$</sup><?php echo $menu_item['item_price']; ?></span>
												<div>
													<h3><?php echo $menu_item['item_name']; ?></h3>
													<p><?php echo $menu_item['item_description']; ?></p>
												</div>
											</li>
								<?php
										}
									}
								}
								?>
						</div>
				<?php
					}
				}
				?>
			</ul>
		</section>
<?php endwhile;
endif; ?>
<?php get_footer(); ?>
