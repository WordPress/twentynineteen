(function() {
	/**
	 * Prepends an element to a container.
	 *
	 * @param {Element} container
	 * @param {Element} element
	 */
	function prependElement(container, element) {
		if (container.firstChild) {
			return container.insertBefore(element, container.firstChild);
		} else {
			return container.appendChild(element);
		}
	}

	/**
	 * Shows an element by adding a hidden className.
	 *
	 * @param {Element} element
	 */
	function showElement(element) {
		// classList.remove is not supported in IE11
		element.className = element.className.replace('is-hidden', '');
	}

	/**
	 * Hides an element by removing the hidden className.
	 *
	 * @param {Element} element
	 */
	function hideElement(element) {
		// classList.add is not supported in IE11
		if (!element.classList.contains('is-hidden')) {
			element.className += ' is-hidden';
		}
	}

	/**
	 * Toggles the element visibility.
	 *
	 * @param {Element} element
	 */
	function toggleElementVisibility(element) {
		if (element.classList.contains('is-hidden')) {
			showElement(element);
		} else {
			hideElement(element);
		}
	}

	var navContainer = document.querySelector('.main-navigation ');
	// Adds the necessary UI to operate the menu.
	navContainer.innerHTML +=
		'<button class="main-menu-more is-hidden" aria-label="More"><svg width="24" height="24" xmlns="http://www.w3.org/2000/svg"><path d="M6 14c-1.209 0-2-.841-2-2.006C4 10.83 4.791 10 6 10c1.22 0 2 .83 2 1.994C8 13.16 7.22 14 6 14zm6 0c-1.209 0-2-.841-2-2.006C10 10.83 10.791 10 12 10c1.22 0 2 .83 2 1.994C14 13.16 13.22 14 12 14zm6 0c-1.209 0-2-.841-2-2.006C16 10.83 16.791 10 18 10c1.22 0 2 .83 2 1.994C20 13.16 19.22 14 18 14z" fill="#FFF" fill-rule="evenodd"/></svg></button>' +
		'<ul class="hidden-links is-hidden"></ul>';
	var toggleButton = document.querySelector('.main-navigation .main-menu-more');
	var visibleList = document.querySelector('.main-navigation .main-menu');
	var hiddenList = document.querySelector('.main-navigation .hidden-links');
	var breaks = [];

	/**
	 * Returns the currently available space in the menu container.
	 *
	 * @returns {number} Available space
	 */
	function getAvailableSpace() {
		return toggleButton.classList.contains('hidden') ? navContainer.offsetWidth : navContainer.offsetWidth - toggleButton.offsetWidth - 30;
	}

	/**
	 * Returns whether the current menu is overflowing or not.
	 *
	 * @returns {boolean} Is overflowing
	 */
	function isOverflowingNavivation() {
		return visibleList.offsetWidth > getAvailableSpace();
	}

	/**
	 * Refreshes the list item from the menu depending on the menu size
	 */
	function updateNavigationMenu() {
		if (isOverflowingNavivation()) {
			// Record the width of the list
			breaks.push(visibleList.offsetWidth);
			// Move item to the hidden list
			prependElement(hiddenList, visibleList.lastChild);
			// Show the toggle button
			showElement(toggleButton);
		} else {
			// There is space for another item in the nav
			if (getAvailableSpace() > breaks[breaks.length - 1]) {
				// Move the item to the visible list
				visibleList.appendChild(hiddenList.firstChild);
				breaks.pop();
			}

			// Hide the dropdown btn if hidden list is empty
			if (breaks.length < 1) {
				hideElement(toggleButton);
				hideElement(hiddenList);
			}
		}

		// Recur if the visible list is still overflowing the nav
		if (isOverflowingNavivation()) {
			updateNavigationMenu();
		}
	}

	// Event listeners
	window.addEventListener('resize', function() {
		updateNavigationMenu();
	});

	toggleButton.addEventListener('click', function() {
		toggleElementVisibility(hiddenList);
	});

	updateNavigationMenu();
})();
