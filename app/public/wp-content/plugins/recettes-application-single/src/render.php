<?php

/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

function render_cpt_block_recette()
{
	ob_start();

	if (is_singular('recette')) : // Vérifie si nous sommes sur une page individuelle de type recette
?>
		<div class="cpt-item wp-block-post">
			<div class="image-container" style="overflow: hidden; position: relative;">
				<div class="overflow">
					<?php if (has_post_thumbnail()) : ?>
						<div class="img-container"><?php the_post_thumbnail(); ?></div>
					<?php endif; ?>
				</div>
			</div>
			<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--2);padding-top:0;">
				<h1 class="wp-block-post-title" style="font-size: var(--wp--preset--font-size--large);">
					<?php the_title(); ?>
				</h1>
				<div class="wp-block-template-part">

					<!-- Récupérer le temps de cuisson -->
					<?php
					$cooking_time = carbon_get_the_post_meta('temps_cuisson');
					if ($cooking_time) : ?>
						<p><strong>Temps de cuisson :</strong> <?php echo esc_html($cooking_time); ?></p>
					<?php endif; ?>

					<div class="wp-block-post-content" style="color: var(--wp--preset--color--contrast-2); font-size: var(--wp--preset--font-size--medium);">
						<?php the_content(); ?>
					</div>
				</div>

				<!-- Affichage des champs personnalisés -->
				<div class="custom-fields">
					<?php
					// Récupérer les ingrédients
					$ingredients = carbon_get_the_post_meta('recette_ingredient');
					if ($ingredients) : ?>
						<h3>Ingrédients</h3>
						<ul>
							<?php foreach ($ingredients as $ingredient) : ?>
								<li><?php echo esc_html($ingredient['quantity'] . ' ' . $ingredient['ingredient']); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<!-- Récupérer les étapes -->
					<?php
					$etapes = carbon_get_the_post_meta('recette_etapes');
					if ($etapes) : ?>
						<h3>Étapes de la recette</h3>
						<ol>
							<?php foreach ($etapes as $etape) : ?>
								<li><?php echo esc_html($etape['description']); ?></li>
							<?php endforeach; ?>
						</ol>
					<?php endif; ?>
				</div>

			</div>
		</div>
<?php
	else :
		echo '<p>Aucun contenu trouvé.</p>';
	endif;

	return ob_get_clean();
}

echo render_cpt_block_recette();
