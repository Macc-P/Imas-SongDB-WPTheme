<!-- ���o�C���łȂ��ꍇ�A�������烍�[�h��ʊ֌W�ƑS�L�����X�g -->
<?php if(!wp_is_mobile()): ?>
<!-- ���[�h��� -->
<link href="<?php echo get_stylesheet_directory_uri(); ?>/resources/starlight-loading.min.css" rel="stylesheet" />
<script src="<?php echo get_stylesheet_directory_uri(); ?>/resources/starlight-loading.min.js"></script>

<!-- JQuery�֌W -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<link href="<?php echo get_stylesheet_directory_uri(); ?>/resources/jquery.flipster.css" rel="stylesheet" />
<script src="<?php echo get_stylesheet_directory_uri(); ?>/resources/jquery.flipster.min.js"></script>

<!-- ���ۂ̃��X�g -->
<div class="musiclist">
<ul>
<!-- �������烋�[�v -->
<?php
$paged = (int) get_query_var('paged');
$args = array(
	'posts_per_page' => 3000,
	'paged' => $paged,
	'orderby' => 'post_date',
	'order' => 'DESC',
	'post_type' => 'music',
	'post_status' => 'publish'
);
$the_query = new WP_Query($args);
if ( $the_query->have_posts() ) :
	while ( $the_query->have_posts() ) : $the_query->the_post();
?>
    <li><?php the_post_thumbnail('medium'); ?><br><a href="<?php the_permalink(); ?>"><div align="center"><?php the_title(); ?></div></a></li>

<?php endwhile; endif; ?>
  </ul>
<!-- FlipStar�Z�b�e�B���O -->
<script>
$(function() {
	$('.musiclist').flipster({
		style: 'coverflow',
		start: '1',
    fadeIn: 400,
    loop: true,
    keyboard: true,
    touch: true,
	});
});</script></div>
<?php wp_reset_query(); ?>
<?php endif; ?>
<!-- �����܂Ń��[�h��ʊ֌W�ƑS�L�����X�g -->
