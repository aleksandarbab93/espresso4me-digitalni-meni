/* global document */
(function () {
	'use strict';

	var menuData = { categories: [] };

	document.addEventListener('DOMContentLoaded', function () {
		var input = document.getElementById('edm-menu-data');
		if (!input) return;

		try {
			menuData = JSON.parse(input.value) || { categories: [] };
		} catch (e) {
			menuData = { categories: [] };
		}

		if (!menuData.categories) menuData.categories = [];

		render();

		document.getElementById('edm-add-category').addEventListener('click', addCategory);
	});

	// -----------------------------------------------------------------------
	// Data mutations
	// -----------------------------------------------------------------------

	function uid() {
		return 'id_' + Math.random().toString(36).substr(2, 9);
	}

	function addCategory() {
		menuData.categories.push({ id: uid(), name: 'Nova kategorija', items: [] });
		render();
		sync();

		// Focus the new category name input
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

		// Focus the new item name input
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

	// -----------------------------------------------------------------------
	// Render
	// -----------------------------------------------------------------------

	function render() {
		var list   = document.getElementById('edm-categories-list');
		var notice = document.getElementById('edm-empty-notice');
		if (!list) return;

		// Clear
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

		// Header
		var header = document.createElement('div');
		header.className = 'edm-category-header';

		var nameInput = document.createElement('input');
		nameInput.type = 'text';
		nameInput.className = 'edm-cat-name';
		nameInput.value = cat.name;
		nameInput.placeholder = 'Naziv kategorije (npr. Kafa, Sokovi, Hrana…)';
		nameInput.addEventListener('input', function () {
			cat.name = this.value;
			sync();
		});

		var removeBtn = document.createElement('button');
		removeBtn.type = 'button';
		removeBtn.className = 'edm-remove-cat';
		removeBtn.textContent = 'Ukloni kategoriju';
		removeBtn.addEventListener('click', function () {
			if (cat.items.length === 0 || window.confirm('Ukloniti kategoriju "' + cat.name + '" i sve njene stavke?')) {
				removeCategory(cat.id);
			}
		});

		header.appendChild(nameInput);
		header.appendChild(removeBtn);
		wrap.appendChild(header);

		// Items list
		var itemsList = document.createElement('div');
		itemsList.className = 'edm-items-list';

		cat.items.forEach(function (item) {
			itemsList.appendChild(buildItem(cat.id, item));
		});
		wrap.appendChild(itemsList);

		// Add item button
		var addBtn = document.createElement('button');
		addBtn.type = 'button';
		addBtn.className = 'button button-secondary edm-add-item-btn';
		addBtn.textContent = '+ Dodaj stavku';
		addBtn.addEventListener('click', function () { addItem(cat.id); });
		wrap.appendChild(addBtn);

		return wrap;
	}

	function buildItem(catId, item) {
		var row = document.createElement('div');
		row.className = 'edm-item';
		row.dataset.id = item.id;

		var fields = document.createElement('div');
		fields.className = 'edm-item-fields';

		var nameInput = makeInput('edm-item-name', item.name, 'Naziv stavke (npr. Espresso)', function (v) { item.name = v; sync(); });
		var descInput = makeInput('edm-item-desc', item.description, 'Opis (opciono)', function (v) { item.description = v; sync(); });
		var priceInput = makeInput('edm-item-price', item.price, 'Cijena (€)', function (v) { item.price = v; sync(); });

		fields.appendChild(nameInput);
		fields.appendChild(descInput);
		fields.appendChild(priceInput);

		// Remove button (×)
		var rmBtn = document.createElement('button');
		rmBtn.type = 'button';
		rmBtn.className = 'edm-remove-item';
		rmBtn.title = 'Ukloni stavku';
		rmBtn.innerHTML = '&times;';
		rmBtn.addEventListener('click', function () { removeItem(catId, item.id); });

		row.appendChild(fields);
		row.appendChild(rmBtn);
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
