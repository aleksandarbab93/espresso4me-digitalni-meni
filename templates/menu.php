<?php
/**
 * Standalone public menu template for espresso4me Digitalni Meni.
 * Variables available: $listing_name, $listing_url, $logo_url, $menu_data
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$categories = $menu_data['categories'] ?? [];
$has_items  = ! empty( $categories );
?>
<!DOCTYPE html>
<html lang="bs">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
	<meta name="theme-color" content="#2b1e12">
	<title><?php echo esc_html( $listing_name ); ?> — Meni</title>
	<meta name="description" content="Digitalni meni lokala <?php echo esc_attr( $listing_name ); ?>">
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
			--shadow: 0 1px 4px rgba(43,30,18,.07);
		}

		html { font-size: 16px; -webkit-text-size-adjust: 100%; }

		body {
			font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
			background: var(--bg);
			color: var(--brown);
			min-height: 100dvh;
			display: flex;
			flex-direction: column;
		}

		/* ── Header ─────────────────────────────────── */

		.menu-header {
			background: var(--brown);
			color: #fff;
			padding: env(safe-area-inset-top, 0) 0 0;
		}

		.menu-header-inner {
			max-width: 600px;
			margin: 0 auto;
			padding: 24px 20px 20px;
			display: flex;
			align-items: center;
			gap: 16px;
		}

		.menu-logo {
			width: 60px;
			height: 60px;
			border-radius: 50%;
			object-fit: cover;
			border: 2px solid rgba(255,255,255,.2);
			flex-shrink: 0;
		}

		.menu-logo-placeholder {
			width: 60px;
			height: 60px;
			border-radius: 50%;
			background: var(--accent);
			display: flex;
			align-items: center;
			justify-content: center;
			flex-shrink: 0;
		}

		.menu-header-text { flex: 1; min-width: 0; }

		.menu-title {
			font-size: 1.25rem;
			font-weight: 700;
			line-height: 1.2;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}

		.menu-back-link {
			display: inline-flex;
			align-items: center;
			gap: 4px;
			font-size: 0.78rem;
			color: rgba(255,255,255,.65);
			text-decoration: none;
			margin-top: 4px;
			transition: color .15s;
		}
		.menu-back-link:hover { color: #fff; }

		.menu-pill {
			background: var(--accent);
			color: #fff;
			font-size: 0.7rem;
			font-weight: 600;
			letter-spacing: .04em;
			padding: 3px 10px;
			border-radius: 20px;
			align-self: flex-start;
			margin-top: 2px;
		}

		/* ── Main content ────────────────────────────── */

		main {
			flex: 1;
			max-width: 600px;
			width: 100%;
			margin: 0 auto;
			padding: 20px 16px 32px;
		}

		/* ── Category ────────────────────────────────── */

		.menu-category {
			margin-bottom: 24px;
		}

		.menu-category-title {
			font-size: 0.7rem;
			font-weight: 700;
			letter-spacing: .1em;
			text-transform: uppercase;
			color: var(--accent);
			padding: 0 0 8px;
			border-bottom: 2px solid var(--accent);
			margin-bottom: 2px;
		}

		/* ── Item ────────────────────────────────────── */

		.menu-item {
			display: flex;
			align-items: baseline;
			justify-content: space-between;
			gap: 12px;
			padding: 12px 0;
			border-bottom: 1px solid var(--border);
		}

		.menu-item:last-child {
			border-bottom: none;
		}

		.menu-item-info { flex: 1; min-width: 0; }

		.menu-item-name {
			font-size: 0.95rem;
			font-weight: 600;
			line-height: 1.3;
			color: var(--brown);
		}

		.menu-item-desc {
			font-size: 0.82rem;
			color: var(--muted);
			margin-top: 2px;
			line-height: 1.4;
		}

		.menu-item-price {
			font-size: 0.95rem;
			font-weight: 700;
			color: var(--accent);
			white-space: nowrap;
			flex-shrink: 0;
		}

		/* ── Empty state ─────────────────────────────── */

		.menu-empty {
			text-align: center;
			padding: 60px 20px;
			color: var(--muted);
		}

		.menu-empty-icon { font-size: 3rem; margin-bottom: 12px; }
		.menu-empty p { font-size: 0.9rem; line-height: 1.6; }

		/* ── Footer ──────────────────────────────────── */

		.menu-footer {
			text-align: center;
			padding: 16px;
			padding-bottom: calc(16px + env(safe-area-inset-bottom, 0));
			font-size: 0.75rem;
			color: var(--muted);
			border-top: 1px solid var(--border);
		}

		.menu-footer a {
			color: var(--accent);
			text-decoration: none;
			font-weight: 600;
		}
	</style>
</head>
<body>

<header class="menu-header">
	<div class="menu-header-inner">

		<?php if ( $logo_url ) : ?>
			<img class="menu-logo" src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $listing_name ); ?>" />
		<?php else : ?>
			<div class="menu-logo-placeholder">
				<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M17 8h1a4 4 0 0 1 0 8h-1"/><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V8z"/>
					<line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/>
				</svg>
			</div>
		<?php endif; ?>

		<div class="menu-header-text">
			<div class="menu-title"><?php echo esc_html( $listing_name ); ?></div>
			<a class="menu-back-link" href="<?php echo esc_url( $listing_url ); ?>">
				← Stranica lokala
			</a>
		</div>

		<div class="menu-pill">Meni</div>

	</div>
</header>

<main>
	<?php if ( $has_items ) : ?>

		<?php foreach ( $categories as $category ) : ?>
			<?php if ( empty( $category['name'] ) && empty( $category['items'] ) ) continue; ?>

			<section class="menu-category">

				<?php if ( ! empty( $category['name'] ) ) : ?>
					<h2 class="menu-category-title"><?php echo esc_html( $category['name'] ); ?></h2>
				<?php endif; ?>

				<?php foreach ( (array) ( $category['items'] ?? [] ) as $item ) : ?>
					<?php if ( empty( $item['name'] ) ) continue; ?>

					<div class="menu-item">
						<div class="menu-item-info">
							<div class="menu-item-name"><?php echo esc_html( $item['name'] ); ?></div>
							<?php if ( ! empty( $item['description'] ) ) : ?>
								<div class="menu-item-desc"><?php echo esc_html( $item['description'] ); ?></div>
							<?php endif; ?>
						</div>
						<?php if ( ! empty( $item['price'] ) ) : ?>
							<div class="menu-item-price"><?php echo esc_html( $item['price'] ); ?> €</div>
						<?php endif; ?>
					</div>

				<?php endforeach; ?>

			</section>
		<?php endforeach; ?>

	<?php else : ?>

		<div class="menu-empty">
			<div class="menu-empty-icon">🍽</div>
			<p>Meni još nije dostupan.<br>Provjerite ponovo uskoro.</p>
		</div>

	<?php endif; ?>
</main>

<footer class="menu-footer">
	Digitalni meni | <a href="<?php echo esc_url( home_url( '/' ) ); ?>">espresso4.me</a>
</footer>

</body>
</html>
