<p <?php echo get_block_wrapper_attributes(); ?>>
	<?php esc_html_e('Recettes Application – hello from a dynamic block!', 'recettes-application'); ?>
</p>

<?php
$args = array(
	'post_type' => 'votre_cpt', // Remplacez 'votre_cpt' par le slug de votre CPT
	'posts_per_page' => 10, // Ajustez le nombre de posts à afficher
);

$loop = new WP_Query($args);

if ($loop->have_posts()) :
	while ($loop->have_posts()) : $loop->the_post();
?>
		<div class="cpt-item">
			<h2><?php the_title(); ?></h2>
			<div class="cpt-content">
				<?php the_excerpt(); ?>
			</div>
			<a href="<?php the_permalink(); ?>">Lire plus</a>
		</div>
<?php
	endwhile;
	wp_reset_postdata();
else :
	echo '<p>Aucun contenu trouvé.</p>';
endif;
?>