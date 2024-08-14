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
              <a class="button" href="<?php the_permalink(); ?>">Voir la recette</a>
            </div>
          </div>

          <?php if (is_singular('recette')) : ?>
            <!-- Affichage des champs personnalisés uniquement sur les pages individuelles -->
            <div class="custom-fields">
              <?php
              // Récupérer les ingrédients
              $ingredients = carbon_get_the_post_meta('recette_ingredient');
              if ($ingredients) : ?>
                <h3>Ingrédients</h3>
                <ul>
                  <?php foreach ($ingredients as $ingredient) : ?>
                    <li><?php echo esc_html($ingredient['quantity'] . ' ' . $ingredient['name']); ?></li>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>

              <!-- Récupérer le temps de cuisson -->
              <?php
              $cooking_time = carbon_get_the_post_meta('temps_cuisson');
              if ($cooking_time) : ?>
                <p><strong>Temps de cuisson :</strong> <?php echo esc_html($cooking_time) . ' minutes'; ?></p>
              <?php endif; ?>

              <!-- Récupérer les étapes -->
              <?php
              $etapes = carbon_get_the_post_meta('recette_etapes');
              if ($etapes) : ?>
                <h3>Étapes de la recette</h3>
                <ol>
                  <?php foreach ($etapes as $etape) : ?>
                    <li><?php echo esc_html($etape['etape'] . '. ' . $etape['description']); ?></li>
                  <?php endforeach; ?>
                </ol>
              <?php endif; ?>
            </div>
          <?php endif; ?>

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
