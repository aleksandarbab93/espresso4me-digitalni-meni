<?php
/**
 * Digitalni Meni — Landing page. Served at /digitalni-meni/
 * Uses the theme's header and footer.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$register_url = home_url( '/dodaj-kafic/' );

// Find first published listing that has menu data (for demo link)
$demo_listings = get_posts( [
	'post_type'      => 'job_listing',
	'posts_per_page' => 1,
	'post_status'    => 'publish',
	'meta_query'     => [ [ 'key' => '_edm_menu_data', 'compare' => 'EXISTS' ] ],
] );
if ( empty( $demo_listings ) ) {
	$demo_listings = get_posts( [ 'post_type' => 'job_listing', 'posts_per_page' => 1, 'post_status' => 'publish' ] );
}
$demo_url = ! empty( $demo_listings ) ? home_url( '/meni/' . $demo_listings[0]->post_name . '/' ) : home_url( '/digitalni-meni/' );

get_header();
?>

<style>
/* ─── Landing page variables ────────────────────────────── */
:root {
	--lp-brown:      #2b1e12;
	--lp-brown-deep: #1e1510;
	--lp-accent:     #9a5927;
	--lp-accent-dk:  #7a4220;
	--lp-accent-bg:  rgba(154,89,39,.12);
	--lp-bg:         #faf8f5;
	--lp-muted:      #7a6655;
	--lp-border:     #e8ddd4;
	--lp-alt:        #f3ede7;
}

/* ─── Reset inside our wrapper ──────────────────────────── */
.edm-lp-page *,
.edm-lp-page *::before,
.edm-lp-page *::after { box-sizing: border-box; }

.edm-lp-page {
	font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
	color: var(--lp-brown);
	line-height: 1.6;
}

.edm-lp-page a { color: inherit; }
.edm-lp-page img { display: block; max-width: 100%; }

/* ─── Buttons ────────────────────────────────────────────── */
.edm-lp-page .lp-btn {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 7px;
	font-weight: 700;
	border-radius: 10px;
	padding: 13px 24px;
	font-size: 0.95rem;
	text-decoration: none;
	cursor: pointer;
	border: 2px solid transparent;
	transition: background .15s, color .15s, border-color .15s, transform .1s;
	line-height: 1;
	-webkit-tap-highlight-color: transparent;
}
.edm-lp-page .lp-btn:active { transform: scale(.97); }

.edm-lp-page .lp-btn--primary   { background: var(--lp-accent); color: #fff; border-color: var(--lp-accent); }
.edm-lp-page .lp-btn--primary:hover { background: var(--lp-accent-dk); border-color: var(--lp-accent-dk); }

.edm-lp-page .lp-btn--ghost     { background: rgba(255,255,255,.1); color: #fff; border-color: rgba(255,255,255,.25); }
.edm-lp-page .lp-btn--ghost:hover { background: rgba(255,255,255,.2); }

.edm-lp-page .lp-btn--outline   { background: transparent; color: var(--lp-accent); border-color: var(--lp-accent); }
.edm-lp-page .lp-btn--outline:hover { background: var(--lp-accent); color: #fff; }

.edm-lp-page .lp-btn--lg { padding: 15px 30px; font-size: 1rem; border-radius: 12px; }

/* ─── Section base ───────────────────────────────────────── */
.edm-lp-page .lp-section {
	padding: 80px 20px;
	background: var(--lp-bg);
}
.edm-lp-page .lp-section--alt  { background: var(--lp-alt); }
.edm-lp-page .lp-section--dark { background: var(--lp-brown); color: #fff; }

.edm-lp-page .lp-container { max-width: 1100px; margin: 0 auto; }

.edm-lp-page .lp-section-head {
	text-align: center;
	margin-bottom: 52px;
}
.edm-lp-page .lp-section-head h2 {
	font-size: 2.1rem;
	font-weight: 800;
	letter-spacing: -.025em;
	margin: 0 0 10px;
	line-height: 1.2;
}
.edm-lp-page .lp-section-head h2 em { color: var(--lp-accent); font-style: normal; }
.edm-lp-page .lp-section-head p {
	font-size: 1rem;
	color: var(--lp-muted);
	max-width: 480px;
	margin: 0 auto;
	line-height: 1.65;
}
.edm-lp-page .lp-section--dark .lp-section-head h2 { color: #fff; }
.edm-lp-page .lp-section--dark .lp-section-head p  { color: rgba(255,255,255,.55); }

/* ─── Hero ───────────────────────────────────────────────── */
.edm-lp-page .lp-hero {
	background: var(--lp-brown);
	color: #fff;
	padding: 72px 20px 64px;
	overflow: hidden;
}

.edm-lp-page .lp-hero-inner {
	max-width: 1100px;
	margin: 0 auto;
	display: grid;
	grid-template-columns: 1fr auto;
	gap: 56px;
	align-items: center;
}

.edm-lp-page .lp-hero-badge {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	background: var(--lp-accent-bg);
	color: var(--lp-accent);
	border: 1px solid rgba(154,89,39,.35);
	font-size: 0.74rem;
	font-weight: 700;
	letter-spacing: .08em;
	text-transform: uppercase;
	padding: 5px 12px;
	border-radius: 20px;
	margin-bottom: 22px;
}

.edm-lp-page .lp-hero h1 {
	font-size: 4.6rem;
	font-weight: 800;
	line-height: 1.08;
	letter-spacing: -.04em;
	margin: 0 0 22px;
	color: #fff;
}
.edm-lp-page .lp-hero h1 em { color: var(--lp-accent); font-style: normal; }

.edm-lp-page .lp-hero-sub {
	font-size: 1.1rem;
	color: rgba(255,255,255,.9);
	line-height: 1.7;
	max-width: 470px;
	margin-bottom: 36px;
}

.edm-lp-page .lp-hero-btns {
	display: flex;
	gap: 12px;
	flex-wrap: wrap;
}

/* ─── iPhone 3D mockup ───────────────────────────────────── */
.edm-lp-page .lp-iphone-wrap {
	flex-shrink: 0;
	position: relative;
}

/* Phone body — dark titanium frame */
.edm-lp-page .lp-iphone {
	width: 252px;
	position: relative;
	background: linear-gradient(148deg,
		#5a5a5a 0%,
		#333 18%,
		#141414 45%,
		#1c1c1e 72%,
		#0a0a0a 100%
	);
	border-radius: 54px;
	padding: 14px;
	transform: perspective(1000px) rotateY(-20deg) rotateX(7deg);
	transform-style: preserve-3d;
	box-shadow:
		-26px 36px 80px rgba(0,0,0,.88),
		-10px 14px 28px rgba(0,0,0,.6),
		4px -4px 18px rgba(0,0,0,.2),
		inset 0 0 0 1.5px rgba(255,255,255,.16),
		inset 1px 1px 0 rgba(255,255,255,.1),
		inset -1px -1px 0 rgba(0,0,0,.4);
}

/* Visible left side edge (due to rotation) */
.edm-lp-page .lp-iphone::after {
	content: '';
	position: absolute;
	left: -4px;
	top: 22px;
	bottom: 22px;
	width: 4px;
	background: linear-gradient(180deg, #3a3a3a 0%, #111 40%, #1e1e1e 70%, #0a0a0a 100%);
	border-radius: 3px 0 0 3px;
}

/* Silent toggle (left side) */
.edm-lp-page .lp-iphone-mute {
	position: absolute;
	left: -5px; top: 26px;
	width: 5px; height: 20px;
	background: linear-gradient(90deg, #2e2e2e, #181818);
	border-radius: 3px 0 0 3px;
	box-shadow: -2px 1px 5px rgba(0,0,0,.6);
}

/* Volume up */
.edm-lp-page .lp-iphone-vol-up {
	position: absolute;
	left: -5px; top: 60px;
	width: 5px; height: 36px;
	background: linear-gradient(90deg, #2e2e2e, #181818);
	border-radius: 3px 0 0 3px;
	box-shadow: -2px 1px 5px rgba(0,0,0,.6);
}

/* Volume down */
.edm-lp-page .lp-iphone-vol-dn {
	position: absolute;
	left: -5px; top: 106px;
	width: 5px; height: 36px;
	background: linear-gradient(90deg, #2e2e2e, #181818);
	border-radius: 3px 0 0 3px;
	box-shadow: -2px 1px 5px rgba(0,0,0,.6);
}

/* Power button (right side) */
.edm-lp-page .lp-iphone-power {
	position: absolute;
	right: -5px; top: 84px;
	width: 5px; height: 60px;
	background: linear-gradient(90deg, #181818, #2e2e2e);
	border-radius: 0 3px 3px 0;
	box-shadow: 2px 1px 5px rgba(0,0,0,.6);
}

/* Screen glass */
.edm-lp-page .lp-iphone-screen {
	background: #faf8f5;
	border-radius: 42px;
	overflow: hidden;
	position: relative;
	box-shadow:
		inset 0 0 0 1px rgba(0,0,0,.14),
		inset 0 3px 10px rgba(0,0,0,.22);
}

/* Glass shine overlay */
.edm-lp-page .lp-iphone-screen::before {
	content: '';
	position: absolute;
	top: 0; left: 0; right: 0;
	height: 52%;
	background: linear-gradient(155deg,
		rgba(255,255,255,.14) 0%,
		rgba(255,255,255,.04) 45%,
		transparent 70%
	);
	pointer-events: none;
	z-index: 20;
	border-radius: 42px 42px 0 0;
}

/* Status bar */
.edm-lp-page .lp-iphone-statusbar {
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding: 8px 16px 4px;
	background: #faf8f5;
}
.edm-lp-page .lp-iphone-statusbar-time {
	font-size: 8.5px;
	font-weight: 700;
	color: var(--lp-brown);
	letter-spacing: .02em;
}
.edm-lp-page .lp-iphone-statusbar-icons {
	display: flex;
	align-items: center;
	gap: 4px;
}
.edm-lp-page .lp-iphone-statusbar-icons svg {
	color: var(--lp-brown);
}

/* Dynamic Island */
.edm-lp-page .lp-iphone-di {
	width: 94px;
	height: 28px;
	background: #000;
	border-radius: 14px;
	margin: 10px auto 6px;
	display: flex;
	align-items: center;
	justify-content: flex-end;
	padding-right: 13px;
	gap: 5px;
	position: relative;
	z-index: 1;
}
.edm-lp-page .lp-iphone-di-cam {
	width: 10px; height: 10px;
	background: #111;
	border-radius: 50%;
	box-shadow: 0 0 0 2px rgba(255,255,255,.04), inset 0 0 5px rgba(50,100,255,.2);
}
.edm-lp-page .lp-iphone-di-dot {
	width: 5px; height: 5px;
	background: #1a1a1a;
	border-radius: 50%;
}

/* App header inside screen */
.edm-lp-page .lp-phone-header {
	background: var(--lp-brown);
	padding: 8px 11px 8px;
	display: flex;
	align-items: center;
	gap: 7px;
}
.edm-lp-page .lp-phone-logo {
	width: 24px; height: 24px;
	border-radius: 50%;
	background: var(--lp-accent);
	border: 1.5px solid rgba(255,255,255,.15);
	flex-shrink: 0;
	display: flex; align-items: center; justify-content: center;
}
.edm-lp-page .lp-phone-logo svg { color: #fff; }
.edm-lp-page .lp-phone-info { flex: 1; min-width: 0; }
.edm-lp-page .lp-phone-name { font-size: 8px; font-weight: 700; color: #fff; line-height: 1.2; }
.edm-lp-page .lp-phone-sub  { font-size: 6px; color: rgba(255,255,255,.45); }
.edm-lp-page .lp-phone-tag  {
	background: var(--lp-accent); color: #fff;
	font-size: 5.5px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase;
	padding: 2px 5px; border-radius: 6px;
}

/* Category grid */
.edm-lp-page .lp-phone-grid {
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: 6px;
	padding: 8px;
}

.edm-lp-page .lp-phone-card {
	background: #fff;
	border: 1.5px solid #ede8e3;
	border-radius: 11px;
	padding: 16px 8px 13px;
	display: flex;
	flex-direction: column;
	align-items: center;
	gap: 6px;
}
.edm-lp-page .lp-phone-card svg { color: var(--lp-accent); }
.edm-lp-page .lp-phone-card-name {
	font-size: 7px; font-weight: 700; text-transform: uppercase;
	color: var(--lp-brown); letter-spacing: .04em; text-align: center; line-height: 1.3;
}

/* Home bar */
.edm-lp-page .lp-iphone-bar {
	width: 90px; height: 4px;
	background: rgba(0,0,0,.14);
	border-radius: 2px;
	margin: 8px auto 10px;
}

/* ─── Features — 3 cards ─────────────────────────────────── */
.edm-lp-page .lp-feat-cards {
	display: grid;
	grid-template-columns: repeat(3, 1fr);
	gap: 24px;
}

.edm-lp-page .lp-feat-card {
	background: #fff;
	border: 1px solid var(--lp-border);
	border-radius: 20px;
	padding: 32px 28px 28px;
	transition: box-shadow .2s, transform .2s;
}
.edm-lp-page .lp-feat-card:hover {
	box-shadow: 0 10px 32px rgba(43,30,18,.09);
	transform: translateY(-4px);
}

.edm-lp-page .lp-feat-circle {
	width: 72px; height: 72px;
	border-radius: 50%;
	display: flex; align-items: center; justify-content: center;
	margin-bottom: 22px;
	color: #fff;
	flex-shrink: 0;
}
.edm-lp-page .lp-feat-circle--1 { background: var(--lp-accent); }
.edm-lp-page .lp-feat-circle--2 { background: #2d7a4f; }
.edm-lp-page .lp-feat-circle--3 { background: #1e5ba8; }

.edm-lp-page .lp-feat-card h3 {
	font-size: 1.1rem; font-weight: 700;
	margin: 0 0 8px;
}
.edm-lp-page .lp-feat-card > p {
	font-size: 0.875rem; color: var(--lp-muted); line-height: 1.6;
	margin: 0 0 18px;
}

.edm-lp-page .lp-feat-list {
	list-style: none;
	margin: 0; padding: 0;
}
.edm-lp-page .lp-feat-list li {
	font-size: 0.875rem;
	color: var(--lp-brown);
	padding: 5px 0;
	display: flex;
	align-items: flex-start;
	gap: 9px;
	border-bottom: 1px solid #f5ede7;
}
.edm-lp-page .lp-feat-list li:last-child { border-bottom: none; }
.edm-lp-page .lp-feat-list li::before {
	content: '';
	width: 18px; height: 18px;
	flex-shrink: 0;
	margin-top: 1px;
	background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%2316a34a' stroke-width='2.8' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='20 6 9 17 4 12'/%3E%3C/svg%3E");
	background-size: contain;
	background-repeat: no-repeat;
	background-position: center;
}

/* ─── How it works ───────────────────────────────────────── */
.edm-lp-page .lp-steps {
	display: grid;
	grid-template-columns: repeat(3, 1fr);
	gap: 32px;
	position: relative;
}
.edm-lp-page .lp-steps::before {
	content: '';
	position: absolute;
	top: 28px;
	left: calc(16.66% + 20px);
	right: calc(16.66% + 20px);
	height: 2px;
	background: var(--lp-border);
}
.edm-lp-page .lp-step { text-align: center; position: relative; }
.edm-lp-page .lp-step-num {
	width: 56px; height: 56px;
	background: var(--lp-accent);
	color: #fff;
	font-size: 1.3rem; font-weight: 800;
	border-radius: 50%;
	display: flex; align-items: center; justify-content: center;
	margin: 0 auto 18px;
	box-shadow: 0 4px 16px rgba(154,89,39,.38);
	position: relative;
	z-index: 1;
}
.edm-lp-page .lp-step h3 { font-size: 1rem; font-weight: 700; margin: 0 0 8px; }
.edm-lp-page .lp-step p  { font-size: 0.875rem; color: var(--lp-muted); line-height: 1.65; margin: 0; }

/* ─── Pricing ────────────────────────────────────────────── */
.edm-lp-page .lp-pricing {
	display: grid;
	grid-template-columns: repeat(2, 380px);
	gap: 24px;
	justify-content: center;
}

.edm-lp-page .lp-price-card {
	background: #fff;
	border: 2px solid var(--lp-border);
	border-radius: 20px;
	padding: 36px 32px 32px;
	position: relative;
}
.edm-lp-page .lp-price-card--featured { border-color: var(--lp-accent); }

.edm-lp-page .lp-price-badge {
	position: absolute;
	top: -14px; left: 50%;
	transform: translateX(-50%);
	background: var(--lp-accent);
	color: #fff;
	font-size: 0.7rem; font-weight: 700; letter-spacing: .07em; text-transform: uppercase;
	padding: 4px 14px;
	border-radius: 20px;
	white-space: nowrap;
}

.edm-lp-page .lp-price-name   { font-size: 1.1rem; font-weight: 700; margin: 0 0 6px; }
.edm-lp-page .lp-price-desc   { font-size: 0.875rem; color: var(--lp-muted); margin: 0 0 18px; }
.edm-lp-page .lp-price-amount { font-size: 3rem; font-weight: 800; color: var(--lp-accent); letter-spacing: -.04em; line-height: 1; }
.edm-lp-page .lp-price-amount sup { font-size: 1.2rem; font-weight: 700; vertical-align: top; margin-top: .4rem; }
.edm-lp-page .lp-price-period { font-size: 0.82rem; color: var(--lp-muted); margin: 2px 0 22px; }
.edm-lp-page .lp-price-divider { border: none; border-top: 1px solid var(--lp-border); margin: 0 0 20px; }

.edm-lp-page .lp-price-list   { list-style: none; margin: 0 0 26px; padding: 0; }
.edm-lp-page .lp-price-list li {
	font-size: 0.875rem;
	color: var(--lp-brown);
	padding: 6px 0;
	display: flex;
	align-items: flex-start;
	gap: 10px;
	border-bottom: 1px solid #f5ede7;
}
.edm-lp-page .lp-price-list li:last-child { border-bottom: none; }
.edm-lp-page .lp-price-list li::before {
	content: '';
	width: 18px; height: 18px;
	flex-shrink: 0;
	margin-top: 1px;
	background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%239a5927' stroke-width='2.8' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='20 6 9 17 4 12'/%3E%3C/svg%3E");
	background-size: contain;
	background-repeat: no-repeat;
	background-position: center;
}
.edm-lp-page .lp-price-card .lp-btn { width: 100%; }

/* ─── CTA strip ──────────────────────────────────────────── */
.edm-lp-page .lp-cta-strip {
	background: var(--lp-brown);
	padding: 80px 20px;
	text-align: center;
	color: #fff;
}
.edm-lp-page .lp-cta-strip h2 {
	font-size: 2.2rem; font-weight: 800; letter-spacing: -.025em; margin: 0 0 12px;
}
.edm-lp-page .lp-cta-strip h2 em { color: var(--lp-accent); font-style: normal; }
.edm-lp-page .lp-cta-strip p  {
	font-size: 1rem; color: rgba(255,255,255,.6); margin: 0 0 32px;
	max-width: 420px; margin-left: auto; margin-right: auto;
}
.edm-lp-page .lp-cta-btns { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }

/* ─── Responsive ─────────────────────────────────────────── */
@media (max-width: 960px) {
	.edm-lp-page .lp-hero-inner  { grid-template-columns: 1fr; text-align: center; gap: 44px; }
	.edm-lp-page .lp-hero h1     { font-size: 3.4rem; }
	.edm-lp-page .lp-hero-sub    { margin-left: auto; margin-right: auto; }
	.edm-lp-page .lp-hero-btns   { justify-content: center; }
	.edm-lp-page .lp-iphone-wrap { order: -1; display: flex; justify-content: center; }
	.edm-lp-page .lp-feat-cards  { grid-template-columns: 1fr; max-width: 480px; margin: 0 auto; }
	.edm-lp-page .lp-pricing     { grid-template-columns: 1fr; max-width: 420px; }
}

@media (max-width: 640px) {
	.edm-lp-page .lp-hero     { padding: 52px 16px 44px; }
	.edm-lp-page .lp-hero h1  { font-size: 2.6rem; }
	.edm-lp-page .lp-iphone   { width: 210px; }
	.edm-lp-page .lp-section  { padding: 56px 16px; }
	.edm-lp-page .lp-section-head { margin-bottom: 38px; }
	.edm-lp-page .lp-section-head h2 { font-size: 1.75rem; }
	.edm-lp-page .lp-steps    { grid-template-columns: 1fr; gap: 28px; }
	.edm-lp-page .lp-steps::before { display: none; }
	.edm-lp-page .lp-cta-strip h2  { font-size: 1.8rem; }
	.edm-lp-page .lp-price-card    { padding: 28px 22px 24px; }
	.edm-lp-page .lp-feat-cards    { max-width: 100%; }
}

@media (max-width: 480px) {
	.edm-lp-page .lp-hero h1  { font-size: 2.1rem; }
	.edm-lp-page .lp-iphone   { width: 185px; transform: perspective(900px) rotateY(-10deg) rotateX(4deg); }
}
</style>

<div class="edm-lp-page">

<!-- ── HERO ───────────────────────────────────────────────── -->
<section class="lp-hero">
	<div class="lp-hero-inner">

		<div class="lp-hero-content">
			<div class="lp-hero-badge">
				<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
				Digitalni meni na espresso4.me
			</div>

			<h1>Digitalni meni<br>za <em>vaš lokal</em></h1>

			<p class="lp-hero-sub">
				Registrujte se na espresso4.me, kreirajte meni za nekoliko minuta i podijelite QR kod sa gostima — bez tehničke pomoći.
			</p>

			<div class="lp-hero-btns">
				<a href="<?php echo esc_url( $register_url ); ?>" class="lp-btn lp-btn--primary lp-btn--lg">
					Kreirajte besplatno
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
				</a>
				<a href="<?php echo esc_url( $demo_url ); ?>" class="lp-btn lp-btn--ghost lp-btn--lg" target="_blank" rel="noopener">Pogledaj demo</a>
			</div>
		</div>

		<div class="lp-iphone-wrap">
			<div class="lp-iphone">
				<!-- Physical side buttons -->
				<div class="lp-iphone-mute"></div>
				<div class="lp-iphone-vol-up"></div>
				<div class="lp-iphone-vol-dn"></div>
				<div class="lp-iphone-power"></div>

				<!-- Screen glass -->
				<div class="lp-iphone-screen">
					<!-- Status bar -->
					<div class="lp-iphone-statusbar">
						<span class="lp-iphone-statusbar-time">9:41</span>
						<div class="lp-iphone-statusbar-icons">
							<svg width="11" height="8" viewBox="0 0 17 12" fill="currentColor"><rect x="0" y="4" width="3" height="8" rx="1" opacity=".4"/><rect x="4.5" y="2.5" width="3" height="9.5" rx="1" opacity=".6"/><rect x="9" y="0.5" width="3" height="11.5" rx="1" opacity=".8"/><rect x="13.5" y="0" width="3" height="12" rx="1"/></svg>
							<svg width="11" height="8" viewBox="0 0 24 18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M1.5 6C5.5 2 10 0 12 0s6.5 2 10.5 6"/><path d="M4.5 9.5C7 7 9.5 6 12 6s5 1 7.5 3.5"/><path d="M7.5 13C9 11.5 10.5 11 12 11s3 .5 4.5 2"/><circle cx="12" cy="16" r="1.5" fill="currentColor"/></svg>
							<svg width="22" height="8" viewBox="0 0 33 12" fill="none"><rect x="0.5" y="0.5" width="28" height="11" rx="3.5" stroke="currentColor" stroke-opacity=".35"/><rect x="2" y="2" width="22" height="8" rx="2" fill="currentColor"/><path d="M30.5 4v4a2 2 0 0 0 0-4z" fill="currentColor" opacity=".4"/></svg>
						</div>
					</div>
					<!-- Dynamic Island -->
					<div class="lp-iphone-di">
						<span class="lp-iphone-di-dot"></span>
						<span class="lp-iphone-di-cam"></span>
					</div>

					<!-- App header -->
					<div class="lp-phone-header">
						<div class="lp-phone-logo">
							<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 8h1a4 4 0 0 1 0 8h-1"/><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
						</div>
						<div class="lp-phone-info">
							<div class="lp-phone-name">Cafe Bar Espresso</div>
							<div class="lp-phone-sub">espresso4.me</div>
						</div>
						<div class="lp-phone-tag">Meni</div>
					</div>

					<!-- Category grid -->
					<div class="lp-phone-grid">
						<?php
						$phone_cards = [
							[ 'Kafa',        '<path d="M17 8h1a4 4 0 0 1 0 8h-1"/><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/>' ],
							[ 'Hladne Kafe', '<path d="M2 12h20M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>' ],
							[ 'Čajevi',      '<path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>' ],
							[ 'Sokovi',      '<path d="M12 22a7 7 0 0 0 7-7c0-2-1-3.9-3-5.5s-3.5-4-4-6.5c-.5 2.5-2 4.9-4 6.5C6 11.1 5 13 5 15a7 7 0 0 0 7 7z"/>' ],
							[ 'Pivo',        '<path d="M17 11h1a3 3 0 0 1 0 6h-1"/><path d="M9 12v6"/><path d="M13 12v6"/><path d="M14 7.5c-1 0-1.44.5-3 .5s-2-.5-3-.5-1.44.5-3 .5"/><path d="M5 4h13a2 2 0 0 1 2 2v4H3V6a2 2 0 0 1 2-2z"/><path d="M5 20H19"/>' ],
							[ 'Hrana',       '<path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/>' ],
						];
						foreach ( $phone_cards as $card ) : ?>
						<div class="lp-phone-card">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><?php echo $card[1]; ?></svg>
							<div class="lp-phone-card-name"><?php echo esc_html( $card[0] ); ?></div>
						</div>
						<?php endforeach; ?>
					</div>

					<!-- Home indicator bar -->
					<div class="lp-iphone-bar"></div>
				</div>
			</div>
		</div>

	</div>
</section>

<!-- ── FEATURES ───────────────────────────────────────────── -->
<section class="lp-section" id="usluge">
	<div class="lp-container">
		<div class="lp-section-head">
			<h2>Sve što vam treba za <em>digitalni meni</em></h2>
			<p>Jednostavan alat prilagođen kafićima, restoranima i barovima u Crnoj Gori</p>
		</div>

		<div class="lp-feat-cards">

			<div class="lp-feat-card">
				<div class="lp-feat-circle lp-feat-circle--1">
					<svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="5" y="5" width="3" height="3"/><rect x="16" y="5" width="3" height="3"/><rect x="16" y="16" width="3" height="3"/></svg>
				</div>
				<h3>QR Kod &amp; Digitalni Meni</h3>
				<p>Svaki lokal dobija jedinstven QR kod koji gosti skeniraju i odmah vide vaš meni — bez aplikacije.</p>
				<ul class="lp-feat-list">
					<li>Automatski generisan QR kod</li>
					<li>Direktan link za goste</li>
					<li>Neograničene kategorije i stavke</li>
					<li>Uvijek ažuran, bez štampanja</li>
				</ul>
			</div>

			<div class="lp-feat-card">
				<div class="lp-feat-circle lp-feat-circle--2">
					<svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
				</div>
				<h3>Lako kreiranje i ažuriranje</h3>
				<p>Upravljajte menijem kroz intuitivan admin panel. Promjene su vidljive gostima odmah, u realnom vremenu.</p>
				<ul class="lp-feat-list">
					<li>Kategorije i podkategorije</li>
					<li>Drag &amp; drop organizacija</li>
					<li>Opisi i cijene za svaku stavku</li>
					<li>Mobilni admin — radite s telefona</li>
				</ul>
			</div>

			<div class="lp-feat-card">
				<div class="lp-feat-circle lp-feat-circle--3">
					<svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
				</div>
				<h3>Vidljivost na espresso4.me</h3>
				<p>Registrujte lokal na portalu i budite pronađeni od strane novih gostiju koji traže mjesta za kafa u Crnoj Gori.</p>
				<ul class="lp-feat-list">
					<li>Listing na mapi Crne Gore</li>
					<li>Kontakt info i radno vrijeme</li>
					<li>Recenzije i ocjene gostiju</li>
					<li>Besplatna osnovna registracija</li>
				</ul>
			</div>

		</div>
	</div>
</section>

<!-- ── HOW IT WORKS ───────────────────────────────────────── -->
<section class="lp-section lp-section--alt" id="kako-funkcionise">
	<div class="lp-container">
		<div class="lp-section-head">
			<h2>Tri koraka do <em>digitalnog menija</em></h2>
			<p>Postavite vaš digitalni meni brže nego što možete da popijete espresso</p>
		</div>

		<div class="lp-steps">
			<div class="lp-step">
				<div class="lp-step-num">1</div>
				<h3>Registrujte lokal</h3>
				<p>Kreirajte nalog na espresso4.me i dodajte vaš lokal za nekoliko minuta. Potreban vam je samo naziv, adresa i opis.</p>
			</div>

			<div class="lp-step">
				<div class="lp-step-num">2</div>
				<h3>Napravite meni</h3>
				<p>Kroz intuitivni admin panel dodajte kategorije, podkategorije i stavke sa cijenama. Povucite i prevucite da reorganizujete.</p>
			</div>

			<div class="lp-step">
				<div class="lp-step-num">3</div>
				<h3>Podijelite QR kod</h3>
				<p>Preuzmite vaš QR kod, odštampajte ga i postavite na stolove ili ulaz. Gosti skeniraju i odmah vide meni.</p>
			</div>
		</div>
	</div>
</section>

<!-- ── PRICING ────────────────────────────────────────────── -->
<section class="lp-section" id="cijene">
	<div class="lp-container">
		<div class="lp-section-head">
			<h2>Transparentne <em>cijene</em></h2>
			<p>Bez skrivenih troškova. Odaberite paket koji odgovara vašem lokalu.</p>
		</div>

		<div class="lp-pricing">
			<div class="lp-price-card">
				<div class="lp-price-name">Starter</div>
				<div class="lp-price-desc">Savršeno za lokale koji tek počinju</div>
				<div class="lp-price-amount"><sup>€</sup>—</div>
				<div class="lp-price-period">— / mjesečno</div>
				<hr class="lp-price-divider">
				<ul class="lp-price-list">
					<li>Digitalni meni za 1 lokal</li>
					<li>QR kod generisanje</li>
					<li>Neograničene kategorije</li>
					<li>Neograničene stavke</li>
					<li>Mobilno optimizovano</li>
				</ul>
				<a href="<?php echo esc_url( $register_url ); ?>" class="lp-btn lp-btn--outline">Počni besplatno</a>
			</div>

			<div class="lp-price-card lp-price-card--featured">
				<div class="lp-price-badge">Preporučeno</div>
				<div class="lp-price-name">Pro</div>
				<div class="lp-price-desc">Za ozbiljne lokale sa više potreba</div>
				<div class="lp-price-amount"><sup>€</sup>—</div>
				<div class="lp-price-period">— / mjesečno</div>
				<hr class="lp-price-divider">
				<ul class="lp-price-list">
					<li>Sve iz Starter paketa</li>
					<li>Više lokala</li>
					<li>Prioritetna podrška</li>
					<li>Branding (logo, boje)</li>
					<li>Napredna analitika</li>
				</ul>
				<a href="<?php echo esc_url( $register_url ); ?>" class="lp-btn lp-btn--primary">Odaberi Pro</a>
			</div>
		</div>
	</div>
</section>

<!-- ── CTA BOTTOM ─────────────────────────────────────────── -->
<section class="lp-cta-strip">
	<h2>Spremi za <em>digitalni meni</em>?</h2>
	<p>Pridružite se lokalima koji već koriste espresso4.me digitalni meni.</p>
	<div class="lp-cta-btns">
		<a href="<?php echo esc_url( $register_url ); ?>" class="lp-btn lp-btn--primary lp-btn--lg">Kreirajte besplatno</a>
		<a href="<?php echo esc_url( $demo_url ); ?>" class="lp-btn lp-btn--ghost lp-btn--lg" target="_blank" rel="noopener">Pogledaj demo</a>
	</div>
</section>

</div><!-- .edm-lp-page -->

<?php get_footer(); ?>
