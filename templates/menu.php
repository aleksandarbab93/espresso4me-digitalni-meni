<?php
/**
 * Standalone public menu template.
 * Variables: $listing_name, $listing_url, $logo_url, $menu_data
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$categories = $menu_data['categories'] ?? [];
$has_items  = ! empty( $categories );


function edm_svg( $key, $size = 44, $color = 'currentColor', $stroke_width = '1.6' ) {
	static $svgs = null;
	if ( $svgs === null ) {
		$svgs = [
			'coffee'         => '<path d="M17 8h1a4 4 0 0 1 0 8h-1"/><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/>',
			'cup-soda'       => '<path d="m6 8 1.75 12.28a2 2 0 0 0 2 1.72h4.54a2 2 0 0 0 2-1.72L18 8"/><path d="M5 8h14"/><path d="M7 15a6.47 6.47 0 0 1 5 0 6.47 6.47 0 0 0 5 0"/><rect x="5" y="4" width="14" height="4" rx="1"/>',
			'glass-water'    => '<path d="M15.2 22H8.8a2 2 0 0 1-2-1.79L5 3h14l-1.81 17.21A2 2 0 0 1 15.2 22z"/><path d="M6 12a5 5 0 0 1 6 0 5 5 0 0 0 6 0"/>',
			'beer'           => '<path d="M17 11h1a3 3 0 0 1 0 6h-1"/><path d="M9 12v6"/><path d="M13 12v6"/><path d="M14 7.5c-1 0-1.44.5-3 .5s-2-.5-3-.5-1.44.5-3 .5"/><path d="M3 8h1"/><path d="M5 4h13a2 2 0 0 1 2 2v4a0 0 0 0 1 0 0H3a0 0 0 0 1 0 0V6a2 2 0 0 1 2-2z"/><path d="M5 20a2 2 0 0 0 4 0m-4 0a2 2 0 0 1 4 0m4 0a2 2 0 0 0 4 0m-4 0a2 2 0 0 1 4 0M5 20v-8h14v8"/>',
			'wine'           => '<path d="M8 22h8"/><path d="M7 10h10"/><path d="M12 15v7"/><path d="M12 15a5 5 0 0 0 5-5c0-2-.5-4-2-8H9c-1.5 4-2 6-2 8a5 5 0 0 0 5 5z"/>',
			'cocktail'       => '<path d="M8 22h8"/><path d="M12 11v11"/><path d="m19 3-7 8-7-8z"/>',
			'flask-conical'  => '<path d="M10 2v7.527a2 2 0 0 1-.211.896L4.72 20.55a1 1 0 0 0 .9 1.45h12.76a1 1 0 0 0 .9-1.45l-5.069-10.127A2 2 0 0 1 14 9.527V2"/><path d="M8.5 2h7"/><path d="M7 16h10"/>',
			'martini'        => '<path d="M8 22h8"/><path d="M12 11v11"/><path d="M17.5 6a.5.5 0 1 1 0-1"/><path d="M19 3H5l7 8z"/>',
			'utensils'       => '<path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/>',
			'pizza'          => '<path d="M15 11h.01"/><path d="M11 15h.01"/><path d="M16 16h.01"/><path d="m2 16 20 6-6-20A20 20 0 0 0 2 16"/><path d="M5.71 17.11a17.04 17.04 0 0 1 11.4-11.4"/>',
			'sandwich'       => '<path d="M3 11v3a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-3"/><path d="M12 19H4a1 1 0 0 1-1-1v-2a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-3.83"/><path d="m3 11 7.77-6.04a2 2 0 0 1 2.46 0L21 11H3z"/><path d="M12.97 19.77 7 15h12.5l-3.75 4.5a2 2 0 0 1-2.78.27z"/>',
			'salad'          => '<path d="M7 21h10"/><path d="M12 21a9 9 0 0 0 9-9H3a9 9 0 0 0 9 9z"/><path d="M11.38 12a2.4 2.4 0 0 1-.4-4.77 2.4 2.4 0 0 1 3.2-2.77 2.4 2.4 0 0 1 3.47-.63 2.4 2.4 0 0 1 3.37 3.37 2.4 2.4 0 0 1-1.1 3.7 2.51 2.51 0 0 1 .03 1.1"/><path d="m13 12 4-4"/><path d="M10.9 7.25A3.99 3.99 0 0 0 4 10c0 .73.2 1.41.54 2"/>',
			'cake'           => '<path d="M20 21v-8a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8"/><path d="M4 16s.5-1 2-1 2.5 2 4 2 2.5-2 4-2 2 1 2 1"/><path d="M2 21h20"/><path d="M7 8v2"/><path d="M12 8v2"/><path d="M17 8v2"/><path d="M7 4h.01"/><path d="M12 4h.01"/><path d="M17 4h.01"/>',
			'ice-cream'      => '<path d="M12 17c5 0 8-2.69 8-6H4c0 3.31 3 6 8 6zm-4 4h8m-4-4v4M5.14 11a3.5 3.5 0 1 1 6.71 0"/><path d="M12.14 11a3.5 3.5 0 1 1 6.71 0"/><path d="M15.5 6.5a3.5 3.5 0 1 0-7 0"/>',
			'croissant'      => '<path d="m4.6 13.11 5.79-3.21C11.39 9.27 12 9.93 12 10.5V21a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 .9-1.67z"/><path d="m19.4 13.11-5.79-3.21C12.61 9.27 12 9.93 12 10.5V21a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-5a2 2 0 0 0-.9-1.67z"/><path d="M12 13a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/><path d="M12 3c-2.17 0-3.96.88-5.31 2h10.62C15.96 3.88 14.17 3 12 3z"/>',
			'citrus'         => '<circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/><path d="M2 12h20"/>',
			'milk'           => '<path d="M8 2h8"/><path d="M9 2v2.789a4 4 0 0 1-.672 2.219l-.656.984A4 4 0 0 0 7 10.212V20a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-9.789a4 4 0 0 0-.672-2.219l-.656-.984A4 4 0 0 1 15 4.788V2"/><path d="M7 15a6.47 6.47 0 0 1 5 0 6.47 6.47 0 0 0 5 0"/>',
			'star'           => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
			'sparkles'       => '<path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3z"/><path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/>',
			'tag'            => '<path d="M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z"/><path d="M7 7h.01"/>',
			'menu'           => '<line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/>',
		];
	}
	$paths = $svgs[ $key ] ?? $svgs['menu'];
	return sprintf(
		'<svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="%s" stroke-width="%s" stroke-linecap="round" stroke-linejoin="round">%s</svg>',
		$size, $size,
		esc_attr( $color ),
		esc_attr( $stroke_width ),
		$paths
	);
}
?>
<!DOCTYPE html>
<html lang="bs">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
	<meta name="theme-color" content="#1a1a1a">
	<title><?php echo esc_html( $listing_name ); ?> — Meni</title>
	<meta name="robots" content="noindex">
	<style>
		*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

		:root {
			--brown:  #2b1e12;
			--accent: #c8773a;
			--bg:     #faf8f5;
			--card:   #ffffff;
			--muted:  #7a6655;
			--border: #ede8e3;
		}

		html { font-size: 16px; -webkit-text-size-adjust: 100%; }

		body {
			font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
			background: var(--bg);
			color: var(--brown);
			min-height: 100dvh;
			display: flex;
			flex-direction: column;
		}

		/* ── Header ──────────────────────────────── */

		.m-header {
			background: var(--brown);
			color: #fff;
			padding-top: env(safe-area-inset-top, 0);
		}

		.m-header-inner {
			max-width: 600px;
			margin: 0 auto;
			padding: 16px 16px 14px;
			display: flex;
			align-items: center;
			gap: 12px;
		}

		.m-back-btn {
			display: none;
			align-items: center;
			gap: 5px;
			background: rgba(255,255,255,.12);
			border: none;
			color: #fff;
			font-size: 0.8rem;
			font-weight: 600;
			padding: 5px 12px 5px 8px;
			border-radius: 20px;
			cursor: pointer;
			flex-shrink: 0;
			transition: background .15s;
			-webkit-tap-highlight-color: transparent;
		}

		.m-back-btn.show { display: flex; }
		.m-back-btn:hover { background: rgba(255,255,255,.22); }

		.m-logo-link {
			flex-shrink: 0;
			display: flex;
			border-radius: 50%;
			text-decoration: none;
			transition: opacity .15s;
		}

		.m-logo-link:hover { opacity: .85; }

		.m-logo {
			width: 48px; height: 48px;
			border-radius: 50%;
			object-fit: cover;
			border: 2px solid rgba(255,255,255,.2);
			display: block;
		}

		.m-logo-placeholder {
			width: 48px; height: 48px;
			border-radius: 50%;
			background: var(--accent);
			display: flex; align-items: center; justify-content: center;
		}

		.m-title-link { text-decoration: none; color: inherit; }
		.m-title-link:hover .m-title { opacity: .85; }

		.m-header-text { flex: 1; min-width: 0; }

		.m-title {
			font-size: 1.05rem;
			font-weight: 700;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}

		.m-subtitle { font-size: 0.73rem; margin-top: 2px; }
		.m-subtitle a { color: rgba(255,255,255,.55); text-decoration: none; }
		.m-subtitle a:hover { color: #fff; }

		.m-badge {
			background: var(--accent);
			color: #fff;
			font-size: 0.62rem;
			font-weight: 700;
			letter-spacing: .07em;
			text-transform: uppercase;
			padding: 4px 10px;
			border-radius: 20px;
			flex-shrink: 0;
		}

		/* ── Detail mode header ─────────────────── */

		.m-header--detail .m-header-text,
		.m-header--detail .m-badge { display: none; }

		.m-header--detail .m-back-btn {
			font-size: 0.82rem;
			letter-spacing: .04em;
			text-transform: uppercase;
		}

		.m-header--detail .m-logo-link {
			width: 38px;
			height: 38px;
			margin-left: auto;
			order: 99;
		}

		.m-header--detail .m-logo {
			width: 38px;
			height: 38px;
		}

		.m-header--detail .m-logo-placeholder {
			width: 38px;
			height: 38px;
		}

		/* ── Main ────────────────────────────────── */

		main {
			flex: 1;
			max-width: 600px;
			width: 100%;
			margin: 0 auto;
		}

		/* ── Grid — category cards ───────────────── */

		.m-grid {
			display: grid;
			grid-template-columns: repeat(2, 1fr);
			gap: 10px;
			padding: 14px 12px 32px;
		}

		@media (min-width: 520px) {
			.m-grid { grid-template-columns: repeat(3, 1fr); }
		}

		.m-cat-card {
			background: var(--card);
			border: 2px solid var(--border);
			border-radius: 14px;
			padding: 20px 10px 16px;
			text-align: center;
			cursor: pointer;
			-webkit-tap-highlight-color: transparent;
			display: flex;
			flex-direction: column;
			align-items: center;
			gap: 8px;
			justify-content: center;
			transition: border-color .15s, box-shadow .15s, transform .1s;
			user-select: none;
		}

		.m-cat-card:hover {
			border-color: var(--accent);
			box-shadow: 0 2px 14px rgba(200,119,58,.14);
		}

		.m-cat-card:active { transform: scale(.95); border-color: var(--accent); }

		.m-cat-icon {
			color: var(--accent);
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.m-cat-name {
			font-size: 0.72rem;
			font-weight: 700;
			color: var(--brown);
			text-transform: uppercase;
			letter-spacing: .03em;
			line-height: 1.3;
		}

		.m-cat-count {
			font-size: 0.65rem;
			color: var(--muted);
			margin-top: -3px;
		}

		/* ── Mobile ─────────────────────────────── */

		@media (max-width: 480px) {
			.m-header-inner {
				padding: 10px 12px;
				gap: 10px;
			}

			.m-logo,
			.m-logo-placeholder { width: 40px; height: 40px; }

			.m-title { font-size: 0.95rem; }

			.m-grid {
				gap: 8px;
				padding: 12px 10px 24px;
			}

			.m-cat-card {
				padding: 18px 10px 14px;
			}

			.m-detail { padding: 12px 10px 32px; }
		}

		/* ── Detail view ─────────────────────────── */

		.m-detail { display: none; padding: 16px 12px 40px; }
		.m-detail.show { display: block; }

		.m-detail-title {
			font-size: 0.7rem;
			font-weight: 700;
			letter-spacing: .1em;
			text-transform: uppercase;
			color: var(--accent);
			padding-bottom: 10px;
			border-bottom: 2px solid var(--accent);
			margin-bottom: 4px;
		}

		/* ── Subcategory header ─────────────────── */

		.m-subcat-header {
			font-size: 0.65rem;
			font-weight: 700;
			letter-spacing: .09em;
			text-transform: uppercase;
			color: var(--muted);
			padding: 18px 0 8px;
			border-bottom: 1px solid var(--border);
		}

		/* ── Item row ────────────────────────────── */

		.m-item {
			display: flex;
			align-items: baseline;
			justify-content: space-between;
			gap: 12px;
			padding: 12px 0;
			border-bottom: 1px solid var(--border);
		}

		.m-item:last-child { border-bottom: none; }
		.m-item-info { flex: 1; min-width: 0; }

		.m-item-name {
			font-size: 0.92rem;
			font-weight: 600;
			color: var(--brown);
			line-height: 1.3;
		}

		.m-item-desc {
			font-size: 0.78rem;
			color: var(--muted);
			margin-top: 2px;
			line-height: 1.4;
		}

		.m-item-price {
			font-size: 0.95rem;
			font-weight: 700;
			color: var(--accent);
			white-space: nowrap;
			flex-shrink: 0;
		}

		/* ── Empty ───────────────────────────────── */

		.m-empty { text-align: center; padding: 60px 20px; color: var(--muted); }
		.m-empty-icon { font-size: 3rem; margin-bottom: 12px; }
		.m-empty p { font-size: 0.9rem; line-height: 1.7; }

		/* ── Footer ──────────────────────────────── */

		.m-footer {
			text-align: center;
			padding: 14px 16px;
			padding-bottom: calc(14px + env(safe-area-inset-bottom, 0));
			font-size: 0.72rem;
			color: var(--muted);
			border-top: 1px solid var(--border);
		}

		.m-footer a { color: var(--accent); text-decoration: none; font-weight: 600; }
	</style>
</head>
<body>

<header class="m-header">
	<div class="m-header-inner">

		<button class="m-back-btn" id="js-back" onclick="edm_goBack(event)">
			<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
			<span id="js-back-label"></span>
		</button>

		<a class="m-logo-link" href="<?php echo esc_url( $listing_url ); ?>" id="js-logo">
			<?php if ( $logo_url ) : ?>
				<img class="m-logo" src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $listing_name ); ?>" />
			<?php else : ?>
				<div class="m-logo-placeholder">
					<?php echo edm_svg( 'coffee', 22, '#fff', '2' ); ?>
				</div>
			<?php endif; ?>
		</a>

		<div class="m-header-text">
			<a class="m-title-link" href="<?php echo esc_url( $listing_url ); ?>">
				<div class="m-title"><?php echo esc_html( $listing_name ); ?></div>
			</a>
		</div>

		<div class="m-badge">Meni</div>

	</div>
</header>

<main>

	<?php if ( $has_items ) : ?>

		<div class="m-grid" id="js-grid">
			<?php foreach ( $categories as $index => $cat ) :
				if ( empty( $cat['name'] ) && empty( $cat['items'] ) && empty( $cat['subcategories'] ) ) continue;
				$icon_key   = $cat['icon'] ?? 'menu';
			?>
			<div class="m-cat-card"
				onclick="edm_open(<?php echo (int) $index; ?>)"
				role="button" tabindex="0"
				onkeydown="if(event.key==='Enter')edm_open(<?php echo (int) $index; ?>)">
				<div class="m-cat-icon">
					<?php echo edm_svg( $icon_key, 36, '#c8773a' ); ?>
				</div>
				<div class="m-cat-name"><?php echo esc_html( $cat['name'] ); ?></div>
			</div>
			<?php endforeach; ?>
		</div>

		<div class="m-detail" id="js-detail"></div>

	<?php else : ?>

		<div class="m-empty">
			<div class="m-empty-icon">🍽</div>
			<p>Meni još nije dostupan.<br>Provjerite ponovo uskoro.</p>
		</div>

	<?php endif; ?>

</main>

<footer class="m-footer">
	Digitalni meni &nbsp;·&nbsp; <a href="<?php echo esc_url( home_url( '/' ) ); ?>">espresso4.me</a>
</footer>

<script>
/* Category data for JS detail view */
var EDM_CATS = <?php echo json_encode( $categories, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP ); ?>;

/* Lucide SVG paths — must match PHP $icon_svgs above */
var EDM_ICONS = {
	'coffee':        '<path d="M17 8h1a4 4 0 0 1 0 8h-1"/><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/>',
	'cup-soda':      '<path d="m6 8 1.75 12.28a2 2 0 0 0 2 1.72h4.54a2 2 0 0 0 2-1.72L18 8"/><path d="M5 8h14"/><path d="M7 15a6.47 6.47 0 0 1 5 0 6.47 6.47 0 0 0 5 0"/><rect x="5" y="4" width="14" height="4" rx="1"/>',
	'glass-water':   '<path d="M15.2 22H8.8a2 2 0 0 1-2-1.79L5 3h14l-1.81 17.21A2 2 0 0 1 15.2 22z"/><path d="M6 12a5 5 0 0 1 6 0 5 5 0 0 0 6 0"/>',
	'beer':          '<path d="M17 11h1a3 3 0 0 1 0 6h-1"/><path d="M9 12v6"/><path d="M13 12v6"/><path d="M14 7.5c-1 0-1.44.5-3 .5s-2-.5-3-.5-1.44.5-3 .5"/><path d="M3 8h1"/><path d="M5 4h13a2 2 0 0 1 2 2v4a0 0 0 0 1 0 0H3a0 0 0 0 1 0 0V6a2 2 0 0 1 2-2z"/><path d="M5 20a2 2 0 0 0 4 0m-4 0a2 2 0 0 1 4 0m4 0a2 2 0 0 0 4 0m-4 0a2 2 0 0 1 4 0M5 20v-8h14v8"/>',
	'wine':          '<path d="M8 22h8"/><path d="M7 10h10"/><path d="M12 15v7"/><path d="M12 15a5 5 0 0 0 5-5c0-2-.5-4-2-8H9c-1.5 4-2 6-2 8a5 5 0 0 0 5 5z"/>',
	'cocktail':      '<path d="M8 22h8"/><path d="M12 11v11"/><path d="m19 3-7 8-7-8z"/>',
	'flask-conical': '<path d="M10 2v7.527a2 2 0 0 1-.211.896L4.72 20.55a1 1 0 0 0 .9 1.45h12.76a1 1 0 0 0 .9-1.45l-5.069-10.127A2 2 0 0 1 14 9.527V2"/><path d="M8.5 2h7"/><path d="M7 16h10"/>',
	'martini':       '<path d="M8 22h8"/><path d="M12 11v11"/><path d="M17.5 6a.5.5 0 1 1 0-1"/><path d="M19 3H5l7 8z"/>',
	'utensils':      '<path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/>',
	'pizza':         '<path d="M15 11h.01"/><path d="M11 15h.01"/><path d="M16 16h.01"/><path d="m2 16 20 6-6-20A20 20 0 0 0 2 16"/><path d="M5.71 17.11a17.04 17.04 0 0 1 11.4-11.4"/>',
	'sandwich':      '<path d="M3 11v3a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-3"/><path d="M12 19H4a1 1 0 0 1-1-1v-2a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-3.83"/><path d="m3 11 7.77-6.04a2 2 0 0 1 2.46 0L21 11H3z"/><path d="M12.97 19.77 7 15h12.5l-3.75 4.5a2 2 0 0 1-2.78.27z"/>',
	'salad':         '<path d="M7 21h10"/><path d="M12 21a9 9 0 0 0 9-9H3a9 9 0 0 0 9 9z"/><path d="M11.38 12a2.4 2.4 0 0 1-.4-4.77 2.4 2.4 0 0 1 3.2-2.77 2.4 2.4 0 0 1 3.47-.63 2.4 2.4 0 0 1 3.37 3.37 2.4 2.4 0 0 1-1.1 3.7 2.51 2.51 0 0 1 .03 1.1"/><path d="m13 12 4-4"/><path d="M10.9 7.25A3.99 3.99 0 0 0 4 10c0 .73.2 1.41.54 2"/>',
	'cake':          '<path d="M20 21v-8a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8"/><path d="M4 16s.5-1 2-1 2.5 2 4 2 2.5-2 4-2 2 1 2 1"/><path d="M2 21h20"/><path d="M7 8v2"/><path d="M12 8v2"/><path d="M17 8v2"/><path d="M7 4h.01"/><path d="M12 4h.01"/><path d="M17 4h.01"/>',
	'ice-cream':     '<path d="M12 17c5 0 8-2.69 8-6H4c0 3.31 3 6 8 6zm-4 4h8m-4-4v4M5.14 11a3.5 3.5 0 1 1 6.71 0"/><path d="M12.14 11a3.5 3.5 0 1 1 6.71 0"/><path d="M15.5 6.5a3.5 3.5 0 1 0-7 0"/>',
	'croissant':     '<path d="m4.6 13.11 5.79-3.21C11.39 9.27 12 9.93 12 10.5V21a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 .9-1.67z"/><path d="m19.4 13.11-5.79-3.21C12.61 9.27 12 9.93 12 10.5V21a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-5a2 2 0 0 0-.9-1.67z"/><path d="M12 13a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/><path d="M12 3c-2.17 0-3.96.88-5.31 2h10.62C15.96 3.88 14.17 3 12 3z"/>',
	'citrus':        '<circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/><path d="M2 12h20"/>',
	'milk':          '<path d="M8 2h8"/><path d="M9 2v2.789a4 4 0 0 1-.672 2.219l-.656.984A4 4 0 0 0 7 10.212V20a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-9.789a4 4 0 0 0-.672-2.219l-.656-.984A4 4 0 0 1 15 4.788V2"/><path d="M7 15a6.47 6.47 0 0 1 5 0 6.47 6.47 0 0 0 5 0"/>',
	'star':          '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
	'sparkles':      '<path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3z"/><path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/>',
	'tag':           '<path d="M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z"/><path d="M7 7h.01"/>',
	'menu':          '<line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/>',
};

function edm_svg_js(key, size) {
	var paths = EDM_ICONS[key] || EDM_ICONS['menu'];
	return '<svg xmlns="http://www.w3.org/2000/svg" width="' + size + '" height="' + size + '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">' + paths + '</svg>';
}

function edm_open(idx) {
	var cat = EDM_CATS[idx];
	if (!cat) return;

	var directItems = cat.items || [];
	var subcats     = cat.subcategories || [];
	var totalItems  = directItems.length;
	subcats.forEach(function (s) { totalItems += (s.items || []).length; });

	var html = '<div class="m-detail-title">' + esc(cat.name) + '</div>';

	if (!totalItems) {
		html += '<p style="padding:20px 0;color:#aaa;font-style:italic;font-size:.85rem;">Nema stavki u ovoj kategoriji.</p>';
	} else {
		directItems.forEach(function (item) {
			if (!item.name) return;
			var desc  = item.description ? '<div class="m-item-desc">' + esc(item.description) + '</div>' : '';
			var price = item.price ? '<div class="m-item-price">' + esc(item.price) + '€</div>' : '';
			html += '<div class="m-item"><div class="m-item-info"><div class="m-item-name">' + esc(item.name) + '</div>' + desc + '</div>' + price + '</div>';
		});
		subcats.forEach(function (subcat) {
			var subItems = (subcat.items || []).filter(function (i) { return i.name; });
			if (!subItems.length) return;
			html += '<div class="m-subcat-header">' + esc(subcat.name) + '</div>';
			subItems.forEach(function (item) {
				var desc  = item.description ? '<div class="m-item-desc">' + esc(item.description) + '</div>' : '';
				var price = item.price ? '<div class="m-item-price">' + esc(item.price) + '€</div>' : '';
				html += '<div class="m-item"><div class="m-item-info"><div class="m-item-name">' + esc(item.name) + '</div>' + desc + '</div>' + price + '</div>';
			});
		});
	}

	document.getElementById('js-detail').innerHTML = html;
	document.getElementById('js-grid').style.display = 'none';
	document.getElementById('js-detail').classList.add('show');
	document.getElementById('js-back').classList.add('show');
	document.getElementById('js-back-label').textContent = cat.name;
	document.querySelector('.m-header').classList.add('m-header--detail');
	window.scrollTo({ top: 0, behavior: 'smooth' });
}

function edm_goBack(e) {
	e.preventDefault();
	document.getElementById('js-grid').style.display = '';
	document.getElementById('js-detail').classList.remove('show');
	document.getElementById('js-back').classList.remove('show');
	document.querySelector('.m-header').classList.remove('m-header--detail');
}

function esc(t) {
	var d = document.createElement('div');
	d.textContent = String(t || '');
	return d.innerHTML;
}
</script>

</body>
</html>
