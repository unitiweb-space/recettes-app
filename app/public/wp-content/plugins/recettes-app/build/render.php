<?php

/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
?>


<?php
function render_cpt_block_recette()
{
	ob_start();

	$args = array(
		'post_type' => 'recette',
		'posts_per_page' => 10,
	);

	$loop = new WP_Query($args);

	if ($loop->have_posts()) :
		echo '<div class="wp-block-query alignwide">'; // Conteneur principal
		echo '<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--50);padding-right:0;padding-bottom:var(--wp--preset--spacing--50);padding-left:0">';
		echo '<div class="wp-block-post-template" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: var(--wp--preset--spacing--30);">';

		while ($loop->have_posts()) : $loop->the_post();

?>
			<div class="cpt-item wp-block-post">
				<div class="image-container" style="overflow: hidden; position: relative;">
					<div class="overflow">
						<a href="<?php the_permalink(); ?>">
							<div class="img-container"><?php the_post_thumbnail(); ?></div>
						</a>
					</div>
				</div>
				<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--2);padding-top:0;">
					<h2 class="wp-block-post-title" style="font-size: var(--wp--preset--font-size--Large);">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</h2>
					<div class="wp-block-template-part">
						<div class="wp-block-post-excerpt" style="color: var(--wp--preset--color--contrast-2); font-size: var(--wp--preset--font-size--medium);">
							<?php the_excerpt('excerpt_length'); ?>
							<br>
							<a class="button" href="<?php the_permalink(); ?>">Voir la recettes</a>
						</div>
					</div>

					<?php
					global $post;
					$path = $_SERVER['PHP_SELF']; // $path = /home/httpd/html/index.php
					$file = basename($path);

					// Vérification du slug ou d'une classe spécifique pour afficher le contenu
					if ($loop['post_title'] === 'single-recette.html') {
						// Afficher des champs ou du contenu spécifique au modèle 'single-recette.html'
					?>

						<h2>Temps de cuisson</h2>
						<?php $option = carbon_get_the_post_meta('temps_cuisson'); ?>
						<div class="custom-fields"><?= esc_html($option); ?></div>

						<h2>Les ingrédients</h2>
						<!-- Affichage des champs personnalisés uniquement sur les pages individuelles -->
						<?php
						$ingredients = carbon_get_the_post_meta('recette_ingredient');
						if ($ingredients) {
							foreach ($ingredients as $option): ?>
								<div class="custom-fields"><?= esc_html($option['quantity']) ?> <?= esc_html($option['ingredient']) ?></div>
						<?php endforeach;
						}
						?>
					<?php
					}
					?>
				</div>
			</div>

<?php
		endwhile;

		echo '</div>'; // Fin de .wp-block-post-template
		echo '</div>'; // Fin de .wp-block-group
		echo '</div>'; // Fin de .wp-block-query

		wp_reset_postdata();
	else :
		echo '<p>Aucun contenu trouvé.</p>';
	endif;

	return ob_get_clean();
}

echo render_cpt_block_recette();
