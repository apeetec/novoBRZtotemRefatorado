<?php

	get_header('single'); 
	$dirbase = get_template_directory();
	$post_id = get_the_ID();
	$titulo = get_the_title($post_id);
	$capa = get_post_meta( $post_id, 'campo_capa', true);
	$cidade = get_post_meta( $post_id, 'campo_cidade', true);
	$logo = get_post_meta( $post_id, 'campo_logo', true);
	$cor = get_post_meta( $post_id, 'campo_escolha_a_cor', true);
	$mapa_aerea = get_post_meta( $post_id, 'campo_campo_visao_aerea', true);
	$mapa_google = get_post_meta( $post_id, 'campo_mapagoogle', true);
	$galeria = get_post_meta( $post_id, 'campotextos', true);
	$diferenciais = get_post_meta( $post_id, 'campo_diferenciais', true);
	$diferenciais_lazer = get_post_meta( $post_id, 'campo_diferenciais_lazer', true);
	$diferenciais_lazer_imagem_lateral = get_post_meta( $post_id, 'campo_imagem_lateral_conforto', true);
	$confortos = get_post_meta( $post_id, 'campo_conforto', true);

	$vídeos = get_post_meta( $post_id, 'campo_videos', true);
	$grupo_lazer = get_post_meta( $post_id, 'campo_lazer', true);
	$implantacao = get_post_meta( $post_id, 'campo_imagem_implantacao', true);
	$espec_implantacao = get_post_meta( $post_id, 'campo_especificacoes', true);
	$grupo_plantas = get_post_meta( $post_id, 'campo_plantas', true);
	$grupo_especs = get_post_meta( $post_id, 'campo_especificacoes_grupo', true);
	$texto_legal_implantacao = get_post_meta( $post_id, 'campo_texto_legal_implantacao', true);
?>

<article class="painel-single">
	<section class="banner">
		<img loading="lazy" class="banner-top-single" src="<?php echo $capa;?>" alt="Capa do empreendimento <?php echo $titulo;?>">
	</section>
	<div class="row no-gap">
		<div class="col s12 m3 l2 box-logo-main" style="background-color:<?php echo $cor; ?>;">
			<img class="logo" src="<?php echo $logo;?>" alt="Logo do empreendimento <?php echo $titulo;?>">
		</div>
		<div class="col s12 m9 l10">
			<div class="gradient">
				<img src="<?php bloginfo('template_url'); ?>/img/gradient.png" alt="">
			</div>
			<div class="row box-buttons-modals">
				<div class="col s12 m6 l6 center">
					<button tabindex="0" class="btn waves-effect btn-flat" popovertarget="localizacao"><span class="button-marker" style="background-color:<?php echo $cor; ?>;"></span><span class="text">Localização</span></button>
				</div>
				<div class="col s12 m6 l6 center">
					<button tabindex="0" class="btn waves-effect btn-flat" popovertarget="diferenciais"><span class="button-marker" style="background-color:<?php echo $cor; ?>;"></span><span class="text">Diferenciais</span></button>
				</div>
				<div class="col s12 m6 l6 center">
					<button tabindex="0" class="btn waves-effect btn-flat" popovertarget="imagens"><span class="button-marker" style="background-color:<?php echo $cor; ?>;"></span><span class="text">Imagens</span></button>
				</div>
				<div class="col s12 m6 l6 center">
					<button tabindex="0" class="btn waves-effect btn-flat" popovertarget="videos"><span class="button-marker" style="background-color:<?php echo $cor; ?>;"></span><span class="text">Vídeos</span></button>
				</div>
				<div class="col s12 m6 l6 center">
					<button tabindex="0" class="btn waves-effect btn-flat" popovertarget="plantas"><span class="button-marker" style="background-color:<?php echo $cor; ?>;"></span><span class="text">Plantas</span></button>
				</div>
				<div class="col s12 m6 l6 center">
					<button tabindex="0" class="btn waves-effect btn-flat" popovertarget="ficha"><span class="button-marker" style="background-color:<?php echo $cor; ?>;"></span><span class="text">Ficha técnica</span></button>
				</div>
			</div>
		</div>
	</div>
</article>

<!-- Modal de localização -->
<?php		
if(!empty($mapa_aerea) || !empty($mapa_google)){
	require_once $dirbase . '/modais/modal-localizacao.php';
}
?>
<!-- Modal de diferenciais -->
<?php		
if(!empty($diferenciais) || !empty($diferenciais_lazer) || !empty($diferenciais_lazer_imagem_lateral) || !empty($confortos)){
		require_once $dirbase . '/modais/modal-diferenciais.php';
}
?>	
<!-- Modal Imagens -->
<?php		
if(!empty($galeria)){
		require_once $dirbase . '/modais/modal-imagens.php';
}
?>
<!-- Modal vídeos -->
<?php		
if(!empty($vídeos)){
		require_once $dirbase . '/modais/modal-videos.php';
}
?>
<!-- Modal Plantas -->
<?php
if(!empty($grupo_plantas)){
	require_once $dirbase . '/modais/modal-plantas.php';
}
?>
<!-- Modal Ficha Técnica -->
<?php
if(!empty($espec_implantacao)){
	require_once $dirbase . '/modais/modal-ficha.php';
}
?>

<?php

	get_footer();
?>