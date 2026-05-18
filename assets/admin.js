/* global document, window */
(function () {
	'use strict';

	var menuData      = { categories: [] };
	var activePicker  = null;
	var dragState     = null; // item drag: { catId, subcatId, item }
	var catDragState  = null; // category drag: { catId }

	var GRIP_SVG = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="15" cy="19" r="1"/></svg>';

	// -------------------------------------------------------------------------
	// Lucide icon library (SVG paths — subset za meni)
	// Source: https://lucide.dev  |  MIT License
	// -------------------------------------------------------------------------
	var ICONS = {
		// Napici
		'coffee':       { label: 'Kafa',            svg: '<path d="M17 8h1a4 4 0 0 1 0 8h-1"/><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/>' },
		'cup-soda':     { label: 'Topli napici',     svg: '<path d="m6 8 1.75 12.28a2 2 0 0 0 2 1.72h4.54a2 2 0 0 0 2-1.72L18 8"/><path d="M5 8h14"/><path d="M7 15a6.47 6.47 0 0 1 5 0 6.47 6.47 0 0 0 5 0"/><rect x="5" y="4" width="14" height="4" rx="1"/>' },
		'glass-water':  { label: 'Voda',             svg: '<path d="M15.2 22H8.8a2 2 0 0 1-2-1.79L5 3h14l-1.81 17.21A2 2 0 0 1 15.2 22z"/><path d="M6 12a5 5 0 0 1 6 0 5 5 0 0 0 6 0"/>' },
		'beer':         { label: 'Pivo',             svg: '<path d="M17 11h1a3 3 0 0 1 0 6h-1"/><path d="M9 12v6"/><path d="M13 12v6"/><path d="M14 7.5c-1 0-1.44.5-3 .5s-2-.5-3-.5-1.44.5-3 .5"/><path d="M3 8h1"/><path d="M5 4h13a2 2 0 0 1 2 2v4a0 0 0 0 1 0 0H3a0 0 0 0 1 0 0V6a2 2 0 0 1 2-2z"/><path d="M5 20a2 2 0 0 0 4 0m-4 0a2 2 0 0 1 4 0m4 0a2 2 0 0 0 4 0m-4 0a2 2 0 0 1 4 0M5 20v-8h14v8"/>' },
		'wine':         { label: 'Vino',             svg: '<path d="M8 22h8"/><path d="M7 10h10"/><path d="M12 15v7"/><path d="M12 15a5 5 0 0 0 5-5c0-2-.5-4-2-8H9c-1.5 4-2 6-2 8a5 5 0 0 0 5 5z"/>' },
		'cocktail':     { label: 'Kokteli',          svg: '<path d="M8 22h8"/><path d="M12 11v11"/><path d="m19 3-7 8-7-8z"/>' },
		'flask-conical':{ label: 'Žestoka pića',     svg: '<path d="M10 2v7.527a2 2 0 0 1-.211.896L4.72 20.55a1 1 0 0 0 .9 1.45h12.76a1 1 0 0 0 .9-1.45l-5.069-10.127A2 2 0 0 1 14 9.527V2"/><path d="M8.5 2h7"/><path d="M7 16h10"/>' },
		'martini':      { label: 'Džin/Rum',         svg: '<path d="M8 22h8"/><path d="M12 11v11"/><path d="M17.5 6a.5.5 0 1 1 0-1"/><path d="M19 3H5l7 8z"/>' },
		// Hrana
		'utensils':     { label: 'Hrana',            svg: '<path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/>' },
		'pizza':        { label: 'Pizza',            svg: '<path d="M15 11h.01"/><path d="M11 15h.01"/><path d="M16 16h.01"/><path d="m2 16 20 6-6-20A20 20 0 0 0 2 16"/><path d="M5.71 17.11a17.04 17.04 0 0 1 11.4-11.4"/>' },
		'sandwich':     { label: 'Sendviči',         svg: '<path d="M3 11v3a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-3"/><path d="M12 19H4a1 1 0 0 1-1-1v-2a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-3.83"/><path d="m3 11 7.77-6.04a2 2 0 0 1 2.46 0L21 11H3z"/><path d="M12.97 19.77 7 15h12.5l-3.75 4.5a2 2 0 0 1-2.78.27z"/>' },
		'salad':        { label: 'Salate',           svg: '<path d="M7 21h10"/><path d="M12 21a9 9 0 0 0 9-9H3a9 9 0 0 0 9 9z"/><path d="M11.38 12a2.4 2.4 0 0 1-.4-4.77 2.4 2.4 0 0 1 3.2-2.77 2.4 2.4 0 0 1 3.47-.63 2.4 2.4 0 0 1 3.37 3.37 2.4 2.4 0 0 1-1.1 3.7 2.51 2.51 0 0 1 .03 1.1"/><path d="m13 12 4-4"/><path d="M10.9 7.25A3.99 3.99 0 0 0 4 10c0 .73.2 1.41.54 2"/>' },
		// Deserti
		'cake':         { label: 'Torte/Kolači',     svg: '<path d="M20 21v-8a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8"/><path d="M4 16s.5-1 2-1 2.5 2 4 2 2.5-2 4-2 2 1 2 1"/><path d="M2 21h20"/><path d="M7 8v2"/><path d="M12 8v2"/><path d="M17 8v2"/><path d="M7 4h.01"/><path d="M12 4h.01"/><path d="M17 4h.01"/>' },
		'ice-cream':    { label: 'Sladoled',         svg: '<path d="M12 17c5 0 8-2.69 8-6H4c0 3.31 3 6 8 6zm-4 4h8m-4-4v4M5.14 11a3.5 3.5 0 1 1 6.71 0"/><path d="M12.14 11a3.5 3.5 0 1 1 6.71 0"/><path d="M15.5 6.5a3.5 3.5 0 1 0-7 0"/>' },
		'croissant':    { label: 'Pekara',           svg: '<path d="m4.6 13.11 5.79-3.21C11.39 9.27 12 9.93 12 10.5V21a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 .9-1.67z"/><path d="m19.4 13.11-5.79-3.21C12.61 9.27 12 9.93 12 10.5V21a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-5a2 2 0 0 0-.9-1.67z"/><path d="M12 13a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/><path d="M12 3c-2.17 0-3.96.88-5.31 2h10.62C15.96 3.88 14.17 3 12 3z"/>' },
		// Sokovi
		'citrus':       { label: 'Sokovi',           svg: '<circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/><path d="M2 12h20"/>' },
		'milk':         { label: 'Mliječni',         svg: '<path d="M8 2h8"/><path d="M9 2v2.789a4 4 0 0 1-.672 2.219l-.656.984A4 4 0 0 0 7 10.212V20a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-9.789a4 4 0 0 0-.672-2.219l-.656-.984A4 4 0 0 1 15 4.788V2"/><path d="M7 15a6.47 6.47 0 0 1 5 0 6.47 6.47 0 0 0 5 0"/>' },
		// Ostalo
		'star':         { label: 'Specijali',        svg: '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>' },
		'sparkles':     { label: 'Posebna ponuda',   svg: '<path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3z"/><path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/>' },
		'tag':          { label: 'Akcije/Popusti',   svg: '<path d="M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z"/><path d="M7 7h.01"/>' },
		'menu':         { label: 'Ostalo',           svg: '<line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/>' },
	};

	// Predefinisane kategorije — ključ mora biti u ICONS
	var DEFAULT_CATEGORIES = [
		{ name: 'Topli napici',      icon: 'coffee'        },
		{ name: 'Bezalkoholna pića', icon: 'cup-soda'      },
		{ name: 'Piva',              icon: 'beer'          },
		{ name: 'Žestoka pića',      icon: 'flask-conical' },
	];

	// -------------------------------------------------------------------------
	// Init
	// -------------------------------------------------------------------------

	document.addEventListener('DOMContentLoaded', function () {
		var input = document.getElementById('edm-menu-data');
		if (!input) return;

		try {
			menuData = JSON.parse(input.value) || { categories: [] };
		} catch (e) {
			menuData = { categories: [] };
		}

		if (!menuData.categories) menuData.categories = [];

		if (menuData.categories.length === 0) {
			DEFAULT_CATEGORIES.forEach(function (cat) {
				menuData.categories.push({ id: uid(), name: cat.name, icon: cat.icon, items: [], subcategories: [] });
			});
			sync();
		}

		render();

		document.getElementById('edm-add-category').addEventListener('click', addCategory);
		document.addEventListener('click', function () { closeIconPicker(); });
	});

	// -------------------------------------------------------------------------
	// Data mutations
	// -------------------------------------------------------------------------

	function uid() {
		return 'id_' + Math.random().toString(36).substr(2, 9);
	}

	function addCategory() {
		menuData.categories.push({ id: uid(), name: 'Nova kategorija', icon: 'menu', items: [], subcategories: [] });
		render();
		sync();
		var cats = document.querySelectorAll('.edm-cat-name');
		if (cats.length) cats[cats.length - 1].select();
	}

	function removeCategory(catId) {
		menuData.categories = menuData.categories.filter(function (c) { return c.id !== catId; });
		render();
		sync();
	}

	function addItem(catId) {
		var cat = findById(menuData.categories, catId);
		if (!cat) return;
		cat.items.push({ id: uid(), name: '', description: '', price: '' });
		render();
		sync();
		var catEl = document.querySelector('.edm-category[data-id="' + catId + '"]');
		if (catEl) {
			var inputs = catEl.querySelectorAll('.edm-item-name');
			if (inputs.length) inputs[inputs.length - 1].focus();
		}
	}

	function removeItem(catId, itemId) {
		var cat = findById(menuData.categories, catId);
		if (!cat) return;
		cat.items = cat.items.filter(function (i) { return i.id !== itemId; });
		render();
		sync();
	}

	function addSubcategory(catId) {
		var cat = findById(menuData.categories, catId);
		if (!cat) return;
		if (!cat.subcategories) cat.subcategories = [];
		cat.subcategories.push({ id: uid(), name: 'Nova podkategorija', items: [] });
		render();
		sync();
		var catEl = document.querySelector('.edm-category[data-id="' + catId + '"]');
		if (catEl) {
			var inputs = catEl.querySelectorAll('.edm-subcat-name');
			if (inputs.length) inputs[inputs.length - 1].select();
		}
	}

	function removeSubcategory(catId, subcatId) {
		var cat = findById(menuData.categories, catId);
		if (!cat || !cat.subcategories) return;
		cat.subcategories = cat.subcategories.filter(function (s) { return s.id !== subcatId; });
		render();
		sync();
	}

	function addSubItem(catId, subcatId) {
		var cat = findById(menuData.categories, catId);
		if (!cat || !cat.subcategories) return;
		var subcat = findById(cat.subcategories, subcatId);
		if (!subcat) return;
		subcat.items.push({ id: uid(), name: '', description: '', price: '' });
		render();
		sync();
		var subcatEl = document.querySelector('.edm-subcategory[data-subid="' + subcatId + '"]');
		if (subcatEl) {
			var inputs = subcatEl.querySelectorAll('.edm-item-name');
			if (inputs.length) inputs[inputs.length - 1].focus();
		}
	}

	function removeSubItem(catId, subcatId, itemId) {
		var cat = findById(menuData.categories, catId);
		if (!cat || !cat.subcategories) return;
		var subcat = findById(cat.subcategories, subcatId);
		if (!subcat) return;
		subcat.items = subcat.items.filter(function (i) { return i.id !== itemId; });
		render();
		sync();
	}

	function sync() {
		var input = document.getElementById('edm-menu-data');
		if (input) input.value = JSON.stringify(menuData);
	}

	function findById(arr, id) {
		for (var i = 0; i < arr.length; i++) {
			if (arr[i].id === id) return arr[i];
		}
		return null;
	}

	function indexById(arr, id) {
		for (var i = 0; i < arr.length; i++) {
			if (arr[i].id === id) return i;
		}
		return -1;
	}

	// -------------------------------------------------------------------------
	// Drag and drop — items
	// -------------------------------------------------------------------------

	function moveItem(src, targetCatId, targetSubcatId) {
		if (src.catId === targetCatId && src.subcatId === (targetSubcatId || null)) return;
		var srcCat = findById(menuData.categories, src.catId);
		if (!srcCat) return;
		if (src.subcatId) {
			var srcSub = findById(srcCat.subcategories || [], src.subcatId);
			if (srcSub) srcSub.items = srcSub.items.filter(function (i) { return i.id !== src.item.id; });
		} else {
			srcCat.items = srcCat.items.filter(function (i) { return i.id !== src.item.id; });
		}
		var targetCat = findById(menuData.categories, targetCatId);
		if (!targetCat) return;
		if (targetSubcatId) {
			var targetSub = findById(targetCat.subcategories || [], targetSubcatId);
			if (targetSub) targetSub.items.push(src.item);
		} else {
			if (!targetCat.items) targetCat.items = [];
			targetCat.items.push(src.item);
		}
		render();
		sync();
	}

	function makeDropZone(list, targetCatId, targetSubcatId) {
		list.addEventListener('dragover', function (e) {
			if (!dragState || catDragState) return;
			e.preventDefault();
			e.dataTransfer.dropEffect = 'move';
			list.classList.add('edm-drop-active');
		});
		list.addEventListener('dragleave', function (e) {
			if (!list.contains(e.relatedTarget)) list.classList.remove('edm-drop-active');
		});
		list.addEventListener('drop', function (e) {
			e.preventDefault();
			list.classList.remove('edm-drop-active');
			if (!dragState) return;
			moveItem(dragState, targetCatId, targetSubcatId || null);
		});
	}

	function addDragHandle(row, catId, subcatId, item) {
		var handle = document.createElement('div');
		handle.className = 'edm-drag-handle';
		handle.draggable = true;
		handle.title = 'Prevuci za premještanje';
		handle.innerHTML = GRIP_SVG;
		handle.addEventListener('dragstart', function (e) {
			e.stopPropagation();
			dragState = { catId: catId, subcatId: subcatId || null, item: item };
			e.dataTransfer.effectAllowed = 'move';
			e.dataTransfer.setData('text/plain', item.id);
			setTimeout(function () { row.classList.add('edm-dragging'); }, 0);
		});
		handle.addEventListener('dragend', function () {
			row.classList.remove('edm-dragging');
			document.querySelectorAll('.edm-drop-active').forEach(function (el) { el.classList.remove('edm-drop-active'); });
			dragState = null;
		});
		row.insertBefore(handle, row.firstChild);
	}

	// -------------------------------------------------------------------------
	// Drag and drop — categories (reorder)
	// -------------------------------------------------------------------------

	function addCategoryDrag(wrap, cat) {
		var handle = document.createElement('div');
		handle.className = 'edm-cat-drag-handle';
		handle.draggable = true;
		handle.title = 'Prevuci za promjenu redosljeda';
		handle.innerHTML = GRIP_SVG;
		handle.addEventListener('dragstart', function (e) {
			e.stopPropagation();
			catDragState = { catId: cat.id };
			e.dataTransfer.effectAllowed = 'move';
			e.dataTransfer.setData('text/plain', cat.id);
			setTimeout(function () { wrap.classList.add('edm-cat-dragging'); }, 0);
		});
		handle.addEventListener('dragend', function () {
			wrap.classList.remove('edm-cat-dragging');
			document.querySelectorAll('.edm-drop-before, .edm-drop-after').forEach(function (el) {
				el.classList.remove('edm-drop-before', 'edm-drop-after');
			});
			catDragState = null;
		});
		wrap.addEventListener('dragover', function (e) {
			if (!catDragState || catDragState.catId === cat.id) return;
			e.preventDefault();
			document.querySelectorAll('.edm-drop-before, .edm-drop-after').forEach(function (el) {
				el.classList.remove('edm-drop-before', 'edm-drop-after');
			});
			var rect = wrap.getBoundingClientRect();
			wrap.classList.add(e.clientY < rect.top + rect.height / 2 ? 'edm-drop-before' : 'edm-drop-after');
		});
		wrap.addEventListener('dragleave', function (e) {
			if (!wrap.contains(e.relatedTarget)) {
				wrap.classList.remove('edm-drop-before', 'edm-drop-after');
			}
		});
		wrap.addEventListener('drop', function (e) {
			e.preventDefault();
			var before = wrap.classList.contains('edm-drop-before');
			wrap.classList.remove('edm-drop-before', 'edm-drop-after');
			if (!catDragState || catDragState.catId === cat.id) return;
			var fromIdx = indexById(menuData.categories, catDragState.catId);
			if (fromIdx === -1) return;
			var moved = menuData.categories.splice(fromIdx, 1)[0];
			var toIdx = indexById(menuData.categories, cat.id);
			if (toIdx === -1) { menuData.categories.push(moved); }
			else menuData.categories.splice(before ? toIdx : toIdx + 1, 0, moved);
			render();
			sync();
		});
		return handle;
	}

	// -------------------------------------------------------------------------
	// SVG helper
	// -------------------------------------------------------------------------

	function makeSvg(key, size) {
		size = size || 24;
		var info = ICONS[key] || ICONS['menu'];
		return '<svg xmlns="http://www.w3.org/2000/svg" width="' + size + '" height="' + size + '" viewBox="0 0 24 24" '
			+ 'fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">'
			+ info.svg + '</svg>';
	}

	// -------------------------------------------------------------------------
	// Icon picker
	// -------------------------------------------------------------------------

	function openIconPicker(cat, iconBtn) {
		closeIconPicker();

		var picker = document.createElement('div');
		picker.className = 'edm-icon-picker';

		var title = document.createElement('div');
		title.className = 'edm-icon-picker-title';
		title.textContent = 'Odaberite ikonu kategorije';
		picker.appendChild(title);

		var grid = document.createElement('div');
		grid.className = 'edm-icon-grid';

		Object.keys(ICONS).forEach(function (key) {
			var info = ICONS[key];
			var btn = document.createElement('button');
			btn.type = 'button';
			btn.className = 'edm-icon-option' + (key === cat.icon ? ' selected' : '');
			btn.title = info.label;
			btn.innerHTML = makeSvg(key, 22);
			btn.addEventListener('mousedown', function (e) {
				e.preventDefault();
				e.stopPropagation();
				cat.icon = key;
				iconBtn.innerHTML = makeSvg(key, 22);
				sync();
				closeIconPicker();
			});
			grid.appendChild(btn);
		});

		picker.appendChild(grid);
		document.body.appendChild(picker);
		activePicker = picker;

		var rect = iconBtn.getBoundingClientRect();
		picker.style.top  = (rect.bottom + window.scrollY + 6) + 'px';
		picker.style.left = rect.left + 'px';

		// Prevent going off screen right
		var maxLeft = window.innerWidth - picker.offsetWidth - 12;
		if (rect.left > maxLeft) picker.style.left = Math.max(8, maxLeft) + 'px';
	}

	function closeIconPicker() {
		if (activePicker && activePicker.parentNode) {
			activePicker.parentNode.removeChild(activePicker);
		}
		activePicker = null;
	}

	// -------------------------------------------------------------------------
	// Render
	// -------------------------------------------------------------------------

	function render() {
		var list   = document.getElementById('edm-categories-list');
		var notice = document.getElementById('edm-empty-notice');
		if (!list) return;

		while (list.firstChild) list.removeChild(list.firstChild);

		var isEmpty = !menuData.categories.length;
		if (notice) notice.style.display = isEmpty ? '' : 'none';

		menuData.categories.forEach(function (cat) {
			list.appendChild(buildCategory(cat));
		});
	}

	function buildCategory(cat) {
		var wrap = document.createElement('div');
		wrap.className = 'edm-category';
		wrap.dataset.id = cat.id;

		var header = document.createElement('div');
		header.className = 'edm-category-header';

		// Icon button
		var iconBtn = document.createElement('button');
		iconBtn.type = 'button';
		iconBtn.className = 'edm-icon-btn';
		iconBtn.title = 'Promijeni ikonu';
		iconBtn.innerHTML = makeSvg(cat.icon || 'menu', 22);
		iconBtn.addEventListener('click', function (e) {
			e.stopPropagation();
			openIconPicker(cat, iconBtn);
		});

		// Name input
		var nameInput = document.createElement('input');
		nameInput.type = 'text';
		nameInput.className = 'edm-cat-name';
		nameInput.value = cat.name;
		nameInput.placeholder = 'Naziv kategorije…';
		nameInput.addEventListener('input', function () { cat.name = this.value; sync(); });

		// Remove button
		var removeBtn = document.createElement('button');
		removeBtn.type = 'button';
		removeBtn.className = 'edm-remove-cat';
		removeBtn.textContent = 'Ukloni';
		removeBtn.addEventListener('click', function () {
			var totalItems = (cat.items || []).length;
			(cat.subcategories || []).forEach(function (s) { totalItems += (s.items || []).length; });
			if (totalItems === 0 || window.confirm('Ukloniti kategoriju "' + cat.name + '" i sve njene stavke?')) {
				removeCategory(cat.id);
			}
		});

		var catHandle = addCategoryDrag(wrap, cat);
		header.insertBefore(catHandle, header.firstChild);
		header.appendChild(iconBtn);
		header.appendChild(nameInput);
		header.appendChild(removeBtn);
		wrap.appendChild(header);

		// --- Subcategories (right after header) ---
		var subcatsList = document.createElement('div');
		subcatsList.className = 'edm-subcats-list';
		(cat.subcategories || []).forEach(function (subcat) {
			subcatsList.appendChild(buildSubcategory(cat.id, subcat));
		});
		wrap.appendChild(subcatsList);

		var addSubcatBtn = document.createElement('button');
		addSubcatBtn.type = 'button';
		addSubcatBtn.className = 'button button-secondary edm-add-subcat-btn';
		addSubcatBtn.textContent = '⊞  Dodaj podkategoriju';
		addSubcatBtn.addEventListener('click', function () { addSubcategory(cat.id); });
		wrap.appendChild(addSubcatBtn);

		// --- Separator ---
		var sep = document.createElement('div');
		sep.className = 'edm-items-sep';
		wrap.appendChild(sep);

		// --- Direct items (stavke bez podkategorije) ---
		var itemsLabel = document.createElement('div');
		itemsLabel.className = 'edm-items-section-label';
		itemsLabel.textContent = 'Stavke';
		wrap.appendChild(itemsLabel);

		var itemsList = document.createElement('div');
		itemsList.className = 'edm-items-list';

		if (!cat.items || cat.items.length === 0) {
			var emptyRow = document.createElement('div');
			emptyRow.className = 'edm-items-empty';
			emptyRow.textContent = 'Nema stavki — kliknite + Dodaj stavku';
			itemsList.appendChild(emptyRow);
		} else {
			cat.items.forEach(function (item) {
				itemsList.appendChild(buildItem(cat.id, item));
			});
		}
		makeDropZone(itemsList, cat.id, null);
		wrap.appendChild(itemsList);

		var addBtn = document.createElement('button');
		addBtn.type = 'button';
		addBtn.className = 'button button-secondary edm-add-item-btn';
		addBtn.textContent = '+ Dodaj stavku';
		addBtn.addEventListener('click', function () { addItem(cat.id); });
		wrap.appendChild(addBtn);

		return wrap;
	}

	function buildSubcategory(catId, subcat) {
		var wrap = document.createElement('div');
		wrap.className = 'edm-subcategory';
		wrap.dataset.subid = subcat.id;

		var header = document.createElement('div');
		header.className = 'edm-subcat-header';

		var nameInput = document.createElement('input');
		nameInput.type = 'text';
		nameInput.className = 'edm-subcat-name';
		nameInput.value = subcat.name;
		nameInput.placeholder = 'Naziv podkategorije…';
		nameInput.addEventListener('input', function () { subcat.name = this.value; sync(); });

		var removeBtn = document.createElement('button');
		removeBtn.type = 'button';
		removeBtn.className = 'edm-remove-subcat';
		removeBtn.textContent = 'Ukloni';
		removeBtn.addEventListener('click', function () {
			if ((subcat.items || []).length === 0 || window.confirm('Ukloniti podkategoriju "' + subcat.name + '" i sve njene stavke?')) {
				removeSubcategory(catId, subcat.id);
			}
		});

		header.appendChild(nameInput);
		header.appendChild(removeBtn);
		wrap.appendChild(header);

		var itemsList = document.createElement('div');
		itemsList.className = 'edm-items-list';

		if (!subcat.items || subcat.items.length === 0) {
			var emptyRow = document.createElement('div');
			emptyRow.className = 'edm-items-empty';
			emptyRow.textContent = 'Nema stavki — kliknite + Dodaj stavku';
			itemsList.appendChild(emptyRow);
		} else {
			subcat.items.forEach(function (item) {
				itemsList.appendChild(buildSubItem(catId, subcat.id, item));
			});
		}
		wrap.appendChild(itemsList);

		makeDropZone(itemsList, catId, subcat.id);

		var addBtn = document.createElement('button');
		addBtn.type = 'button';
		addBtn.className = 'button button-secondary edm-add-item-btn edm-add-subitem-btn';
		addBtn.textContent = '+ Dodaj stavku';
		addBtn.addEventListener('click', function () { addSubItem(catId, subcat.id); });
		wrap.appendChild(addBtn);

		return wrap;
	}

	function buildSubItem(catId, subcatId, item) {
		var row = document.createElement('div');
		row.className = 'edm-item';
		row.dataset.id = item.id;

		var fields = document.createElement('div');
		fields.className = 'edm-item-fields';

		fields.appendChild(makeInput('edm-item-name',  item.name,        'Naziv (npr. Espresso)',  function (v) { item.name = v; sync(); }));
		fields.appendChild(makeInput('edm-item-desc',  item.description, 'Opis (opciono)',         function (v) { item.description = v; sync(); }));
		fields.appendChild(makeInput('edm-item-price', item.price,       'Cijena €',               function (v) { item.price = v; sync(); }));

		var rmBtn = document.createElement('button');
		rmBtn.type = 'button';
		rmBtn.className = 'edm-remove-item';
		rmBtn.title = 'Ukloni stavku';
		rmBtn.innerHTML = '&times;';
		rmBtn.addEventListener('click', function () { removeSubItem(catId, subcatId, item.id); });

		row.appendChild(fields);
		row.appendChild(rmBtn);
		addDragHandle(row, catId, subcatId, item);
		return row;
	}

	function buildItem(catId, item) {
		var row = document.createElement('div');
		row.className = 'edm-item';
		row.dataset.id = item.id;

		var fields = document.createElement('div');
		fields.className = 'edm-item-fields';

		fields.appendChild(makeInput('edm-item-name',  item.name,        'Naziv (npr. Espresso)',  function (v) { item.name = v; sync(); }));
		fields.appendChild(makeInput('edm-item-desc',  item.description, 'Opis (opciono)',         function (v) { item.description = v; sync(); }));
		fields.appendChild(makeInput('edm-item-price', item.price,       'Cijena €',               function (v) { item.price = v; sync(); }));

		var rmBtn = document.createElement('button');
		rmBtn.type = 'button';
		rmBtn.className = 'edm-remove-item';
		rmBtn.title = 'Ukloni stavku';
		rmBtn.innerHTML = '&times;';
		rmBtn.addEventListener('click', function () { removeItem(catId, item.id); });

		row.appendChild(fields);
		row.appendChild(rmBtn);
		addDragHandle(row, catId, null, item);
		return row;
	}

	function makeInput(className, value, placeholder, onChange) {
		var el = document.createElement('input');
		el.type = 'text';
		el.className = className;
		el.value = value || '';
		el.placeholder = placeholder;
		el.addEventListener('input', function () { onChange(this.value); });
		return el;
	}

})();
