<?php
get_template_part('sitehensu/godo');

$term_id = get_queried_object_id(); // タームIDの取得
$term_idmenu = $taxonomy.'_'; //「taxonomyname_ + termID」を取得

$place = get_field('place',$term_idmenu.$term_id);//場所の出力
$address =  get_field('address',$term_idmenu.$term_id);

?>

<?php get_header(); ?>

<?php get_template_part('template-parts/page-header'); ?>
<?php get_template_part('template-parts/breadcrumb'); ?>

<div class="section siteContent">
<div class="container">
<div class="row">

<div class="col mainSection mainSection-col-two" id="main" role="main">

<!-- OGP -->
<meta name="description" content="<?php echo $place; ?>で開催のライブ「<?php echo get_the_archive_title();?>」の曲情報です。">
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@<?php echo $site_twitter; ?>" />
<meta name="twitter:creator" content="@<?php echo $creator_twitter; ?>" />
<meta property="og:title" content="「<?php echo get_the_archive_title();?>」｜<?php bloginfo('name'); ?>">
<meta property="og:description" content="<?php echo $place; ?>で開催のライブ「<?php echo get_the_archive_title();?>」の曲情報です。">
<meta property="og:image" content="<?php echo get_stylesheet_directory_uri();?>/resources/mic_icon.png">

<!-- 構造化データ -->
<?php get_template_part('parts/tax/live_markup'); ?>

<!-- CD情報用CSS（OSにより分岐） -->
<?php if(wp_is_mobile()): ?>
<style type="text/css">
<!-- スマホ用CSS -->
.cdname{font-size:15px;}
</style>
<?php endif; ?>

<?php if(!wp_is_mobile()): ?>
<!-- PC用CSS -->
<style type="text/css">
.cdname{font-size:20px;}
</style>
<?php endif; ?>
<!-- ここまでCD情報用CSS -->
<header class="archive-header">
<div class="cdinfo" style="height:auto;">
 <?php
/*-------------------------------------------*/
/*  Archive title
/*-------------------------------------------*/
$page_for_posts = lightning_get_page_for_posts();
// Use post top page（ Archive title wrap to div ）
if ( $page_for_posts['post_top_use'] || get_post_type() != 'post' ) {
  if ( is_year() || is_month() || is_day() || is_tag() || is_author() || is_tax() || is_category() ) {
      $archiveTitle = preg_replace('/\d{4}\-\d{1,2}\-\d{1,2}/', '' , get_the_archive_title() );
      preg_match('/\d{4}\-\d{1,2}\-\d{1,2}/' , get_the_archive_title(), $date_match);
      $day = preg_replace('/(\d{4})\-(\d{1,2})\-(\d{1,2})/', '$1年$2月$3日' , array_shift($date_match)) ;

      $archiveTitle_html = '<img src="'.get_stylesheet_directory_uri().'/resources/mic_icon.png" class="cdicon">
      <div class="cdname"><p style="font-weight: bold;border-bottom: dotted 3px gray;margin: 0.3em 0px;">'. $archiveTitle .'</p>
      開催日：'.$day.'<br>
      開催場所：'.$place.'<br>
      <span Style="font-size: small;">住所：'.$address.'</span><br></div></div></header>';
      echo apply_filters( 'lightning_mainSection_archiveTitle' , $archiveTitle_html );
  }
}

/*-------------------------------------------*/
/*  Archive description
/*-------------------------------------------*/
  if ( is_category() || is_tax() || is_tag() ) {
    $category_description = term_description();
    $page = get_query_var( 'paged', 0 );
    if ( ! empty( $category_description ) && $page == 0 ) {
      $archiveDescription_html = '<div class="archive-meta">' . $category_description . '</div>';
      echo apply_filters( 'lightning_mainSection_archiveDescription' , $archiveDescription_html );
    }
  }

$postType = lightning_get_post_type();

do_action('lightning_loop_before'); ?>
<!-- CD購入情報の表示 -->
<div class="archive-meta">

<?php get_template_part('share'); ?>

<?php if ( !is_paged() ) : // 1ページ目のみに表示 ?>
  
  
  <?php if ( !empty(get_field('shop',$term_idmenu.$term_id)) ) : // Amazon情報が登録されている場合にのみ表示 ?>
<div class="msgbox">
  <div class="msgboxtop">このライブの映像ディスクを購入する</div>
  <div class="msgboxbody">
<?php
echo get_field('shop',$term_idmenu.$term_id);//出力
  ?>
</div>
  <div class="msgboxfoot">
  </div>
</div>
  <?php endif;?>


  <?php if ( !empty(get_field('movie',$term_idmenu.$term_id)) ) : // Amazon情報が登録されている場合にのみ表示 ?>
<div class="msgbox">
  <div class="msgboxtop">TV・生放送放送情報</div>
  <div class="msgboxbody">
<?php
echo get_field('movie',$term_idmenu.$term_id);//出力
  ?>
</div>
  <div class="msgboxfoot">
  </div>
</div>
  <?php endif;?>



<!-- メンバー情報の表示 -->
<?php get_template_part('parts/tax/unit_member'); ?>

<?php if(wp_is_mobile()): ?>
<style type="text/css">
<!-- スマホ用CSS -->
.cdname{font-size:15px;}
.idolicon_cd{padding:4px;width:60px;margin-bottom:0px;}
</style>
<?php endif; ?>

<!-- セットリスト -->
<?php get_template_part('parts/tax/live_setlist'); ?>

<?php if(!wp_is_mobile()): ?>
<!-- PC用CSS -->
<style type="text/css">
.cdname{font-size:20px;}
.idolicon_cd{padding:4px;width:70px;margin-bottom:0px;}
</style>
<?php endif; ?>

<?php endif; ?>
</div>

<div class="postList">

<?php 
foreach (SCF::get_term_meta($term_id, $taxonomy, 'same_setlist') as $field) {
  if(!empty($field)){
    $same_setlist = TRUE;
    }
}
$setlist_hantei = count(SCF::get_term_meta( $term_id, $taxonomy, 'setlist' )) >= 2;
if (have_posts() and !$setlist_hantei and !$same_setlist) : ?>

  <?php if( apply_filters( 'is_lightning_extend_loop' , false ) ): ?>

    <?php do_action( 'lightning_extend_loop' ); ?>

  <?php elseif (file_exists(get_stylesheet_directory( ).'/module_loop_'.$postType['slug'].'.php') && $postType != 'post' ): ?>

    <?php while ( have_posts() ) : the_post(); ?>
    <?php get_template_part('module_loop_'.$postType['slug']); ?>
    <?php endwhile; ?>

  <?php else: ?>

    <?php while ( have_posts() ) : the_post();?>
    <?php get_template_part('module_loop_post_music'); ?>
    <?php endwhile;?>

  <?php endif; // loop() ?>

  <?php
  the_posts_pagination(array (
                          'mid_size'  => 1,
                          'prev_text' => '&laquo;',
                          'next_text' => '&raquo;',
                          'type'      => 'list',
                          'before_page_number' => '<span class="meta-nav screen-reader-text">' . __ ( 'Page', 'lightning' ) . ' </span>'
                      ) );
  ?>

  <?php else: // hove_posts() ?>

  <div class="well"><p><?php _e('No posts.','lightning');?></p></div>

<?php endif; // have_post() ?>

</div><!-- [ /.postList ] -->

<?php do_action('lightning_loop_after'); ?>

</div><!-- [ /.mainSection ] -->

<div class="col subSection sideSection sideSection-col-two">
<?php get_sidebar(get_post_type()); ?>
</div><!-- [ /.subSection ] -->

</div><!-- [ /.row ] -->
</div><!-- [ /.container ] -->
</div><!-- [ /.siteContent ] -->
 <?php get_footer(); ?>
