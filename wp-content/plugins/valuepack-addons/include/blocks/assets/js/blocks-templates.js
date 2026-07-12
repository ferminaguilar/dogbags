'use strict';

(($) => {
	if (typeof elementor === 'undefined' || typeof elementorCommon === 'undefined') {
		return;
	}

	const PAGE_SIZE = 24;

	elementor.on('preview:loaded', () => {

		let dialog = null;
		let libraryState = {
			allElements: [],
			tags: [],
			elementTypes: [],
			types: [],
			currentTab: 'section',
			currentCategory: 'all',
			currentElementType: 'all',
			allowedCategories: [],
			searchText: '',
			currentPage: 1,
			initialized: false
		};

		function parseCategoryList(raw) {
			const str = String(raw || '').trim().toLowerCase();
			if (!str) return [];
			const parts = str.split(/[,\s|]+/g).map((s) => s.trim()).filter(Boolean);
			const cleaned = parts
				.map((s) => s.replace(/[^a-z0-9_-]/g, ''))
				.filter(Boolean);
			return Array.from(new Set(cleaned));
		}

		function applyProductCategoryConstraints() {
			const requested = (typeof vp_library_syncer !== 'undefined') ? parseCategoryList(vp_library_syncer.product_category) : [];
			if (!requested.length || !Array.isArray(libraryState.tags) || !libraryState.tags.length) {
				libraryState.allowedCategories = [];
				return;
			}
			const available = new Set(libraryState.tags.map((t) => String(t.slug || '').toLowerCase()));
			const allowed = requested.filter((slug) => available.has(slug));
			libraryState.allowedCategories = allowed;
			if (allowed.length === 1) {
				libraryState.currentCategory = allowed[0];
			}
		}

		function getFilteredElements() {
			const { allElements, currentTab, currentCategory, currentElementType, allowedCategories, searchText } = libraryState;
			const search = String(searchText).toLowerCase().trim();
			return allElements.filter((item) => {
				const name = String(item.title || '').toLowerCase();
				const type = String(item.type || '').toLowerCase();
				const category = String(item.category || '').toLowerCase();
				const elementType = String(item.element_type || '').toLowerCase();
				const matchesSearch = !search || name.includes(search);
				const tabSlug = (currentTab || 'section').toLowerCase();
				const matchesTab = (tabSlug === 'page' && type === 'page') || (tabSlug === 'section' && type === 'block');
				const matchesCategory = currentCategory === 'all' || category.includes((currentCategory || '').toLowerCase());
				const matchesElementType = currentElementType === 'all' || elementType.includes((currentElementType || '').toLowerCase());
				const matchesAllowedCategories = !Array.isArray(allowedCategories) || allowedCategories.length === 0
					? true
					: allowedCategories.some((slug) => category.includes(String(slug).toLowerCase()));
				return matchesSearch && matchesTab && matchesCategory && matchesElementType && matchesAllowedCategories;
			});
		}

		function getCurrentPageSlice() {
			const filtered = getFilteredElements();
			const start = (libraryState.currentPage - 1) * PAGE_SIZE;
			return filtered.slice(start, start + PAGE_SIZE);
		}

		function hasMorePages() {
			const filtered = getFilteredElements();
			const start = libraryState.currentPage * PAGE_SIZE;
			return start < filtered.length;
		}

		function getTotalFiltered() {
			return getFilteredElements().length;
		}

		function renderTemplates(append) {
			const container = $('#vp-library-modal #elementor-template-library-templates-container');
			const slice = getCurrentPageSlice();
			const itemTemplate = wp.template('elementor-vp-library-modal-item');
			const html = itemTemplate({ elements: slice });
			if (!append) {
				container.empty();
				libraryState.currentPage = 1;
			}
			if (slice.length) {
				container.append(html);
			}
			updateLoadMoreButton();
			bindInsertButtons();
			if (!append && getTotalFiltered() === 0) {
				container.append('<div class="vp-no-results">No templates match your filters.</div>');
			}
		}

		function updateLoadMoreButton() {
			const $btn = $('#vp-library-modal .vp-load-more-btn');
			const total = getTotalFiltered();
			const shown = Math.min(libraryState.currentPage * PAGE_SIZE, total);
			if ($btn.length) {
				if (hasMorePages()) {
					$btn.removeClass('vp-load-more-done').show().find('.vp-load-more-text').text(`Load more (${shown} of ${total})`);
				} else {
					if (total > PAGE_SIZE) {
						$btn.show().find('.vp-load-more-text').text(`All ${total} templates loaded`);
						$btn.addClass('vp-load-more-done');
					} else {
						$btn.hide();
					}
				}
			}
		}

		function applyFiltersAndRender() {
			libraryState.currentPage = 1;
			renderTemplates(false);
		}

		function setupFilterListeners() {
			$(document).off('keyup.vpLibrary', '#vp-library-modal #elementor-template-library-filter-text').on('keyup.vpLibrary', '#vp-library-modal #elementor-template-library-filter-text', function () {
				libraryState.searchText = $(this).val();
				applyFiltersAndRender();
			});
			$(document).off('change.vpLibrary', '#vp-library-modal #elementor-template-library-filter-subtype').on('change.vpLibrary', '#vp-library-modal #elementor-template-library-filter-subtype', function () {
				if (Array.isArray(libraryState.allowedCategories) && libraryState.allowedCategories.length === 1) {
					$(this).val(libraryState.allowedCategories[0]).trigger('change.select2');
					libraryState.currentCategory = libraryState.allowedCategories[0];
					return;
				}
				libraryState.currentCategory = $(this).val() || 'all';
				applyFiltersAndRender();
			});
			$(document).off('change.vpLibrary', '#vp-library-modal #elementor-template-library-filter-element-type').on('change.vpLibrary', '#vp-library-modal #elementor-template-library-filter-element-type', function () {
				libraryState.currentElementType = $(this).val() || 'all';
				applyFiltersAndRender();
			});
		}

		function setActiveTab(tab) {
			libraryState.currentTab = tab;
			$('#vp-library-modal .elementor-template-library-menu-item').removeClass('elementor-active');
			$('#vp-tab-' + tab).addClass('elementor-active');
			if (tab === 'page') {
				$('#vp-library-modal #elementor-template-library-filter-element-type').val('all').trigger('change');
				// $('#vp-library-modal #elementor-template-library-element-type-filter').hide();
			} else {
				// $('#vp-library-modal #elementor-template-library-element-type-filter').show();
			}
			applyFiltersAndRender();
		}

		function bindInsertButtons() {
			$('#vp-library-modal .elementor-template-library-template-insert').off('click.vpInsert').on('click.vpInsert', function () {
				const $button = $(this);
				const templateName = $button.closest('.elementor-template-library-template').find('.vp-elementor-template-library-template-name').text();
				$button.addClass('loading').prop('disabled', true);
				$button.find('.elementor-button-title').text('Inserting...');
				showLoader();
				const config = {
					data: {
						source: 'vp-template-library',
						edit_mode: true,
						display: true,
						template_id: $button.data('id'),
						with_page_settings: false
					},
					success: function (data) {
						if (data && data.elementor_data) {
							$button.find('.elementor-button-title').text('✓ Inserted!');
							$button.css({ 'background': 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)', 'transform': 'scale(1.05)' });
							setTimeout(function () {
								elementor.reloadPreview();
								elementor.getPreviewView().addChildModel(data.elementor_data);
								const successMsg = $('<div class="vp-notice vp-success">Template "' + templateName + '" inserted successfully!</div>');
								successMsg.css({ 'position': 'fixed', 'top': '20px', 'right': '20px', 'z-index': '999999', 'animation': 'slideInRight 0.3s ease-out' });
								$('body').append(successMsg);
								setTimeout(function () { successMsg.fadeOut(300, function () { $(this).remove(); }); }, 3000);
								dialog.hide();
								hideLoader();
								activateUpdateButton();
							}, 500);
						} else {
							$button.removeClass('loading').prop('disabled', false);
							$button.find('.elementor-button-title').text('Insert');
							$('<div class="vp-notice vp-error">The element can\'t be loaded from the server.</div>').prependTo($('#vp-library-modal #elementor-template-library-templates-container'));
							hideLoader();
						}
					},
					error: function () {
						$button.removeClass('loading').prop('disabled', false);
						$button.find('.elementor-button-title').text('Insert');
						$('<div class="vp-notice vp-error">The element can\'t be loaded from the server.</div>').prependTo($('#vp-library-modal #elementor-template-library-templates-container'));
						hideLoader();
					}
				};
				return elementorCommon.ajax.addRequest('get_template_data', config);
			});
		}

		function showLoader() {
			$('#vp-library-modal #elementor-template-library-templates').hide();
			$('#vp-library-modal .elementor-loader-wrapper').show();
		}

		function hideLoader() {
			$('#vp-library-modal #elementor-template-library-templates').show();
			$('#vp-library-modal .elementor-loader-wrapper').hide();
		}

		function activateUpdateButton() {
			jQuery('#elementor-panel-saver-button-publish').toggleClass('elementor-disabled');
			jQuery('#elementor-panel-saver-button-save-options').toggleClass('elementor-disabled');
			jQuery('#elementor-panel-saver-button-publish').trigger('click');
		}

		function loadTemplates() {
			showLoader();
			const apiEndpoints = (typeof vp_library_syncer !== 'undefined' && vp_library_syncer.api_endpoints) ? vp_library_syncer.api_endpoints : {};
			const apiBaseUrl = (typeof vp_library_syncer !== 'undefined' && vp_library_syncer.api_url) ? vp_library_syncer.api_url : 'https://vpaddons.com/';
			const templatesUrl = apiEndpoints.templates || apiBaseUrl + 'wp-json/vp-library/v1/templates';
			const taxonomiesUrl = apiEndpoints.taxonomies || apiBaseUrl + 'wp-json/vp-library/v1/taxonomies';

			$.when(
				$.ajax({ url: templatesUrl, method: 'GET', dataType: 'json' }),
				$.ajax({ url: taxonomiesUrl + '?taxonomy=template-categories', method: 'GET', dataType: 'json' }),
				$.ajax({ url: taxonomiesUrl + '?taxonomy=element-type', method: 'GET', dataType: 'json' }),
				$.ajax({ url: taxonomiesUrl + '?taxonomy=type', method: 'GET', dataType: 'json' })
			).done(function (templatesResponse, tagsResponse, elementTypesResponse, typesResponse) {
				const templatesData = templatesResponse[0];
				const tagsData = tagsResponse[0];
				const elementTypesData = elementTypesResponse[0];
				const typesData = typesResponse[0];

				if (templatesData && templatesData.elements) {
					const filteredElements = templatesData.elements.filter(item => item.user_show === 'Yes');
					libraryState.allElements = filteredElements;
					libraryState.tags = (tagsData || []).map(tag => ({ slug: tag.slug, title: tag.name }));
					libraryState.elementTypes = (elementTypesData || []).map(t => ({ slug: t.slug, title: t.name }));
					libraryState.types = [{ slug: 'section', title: 'Section' }, { slug: 'page', title: 'Page' }];
					libraryState.currentPage = 1;
					libraryState.currentTab = 'section';
					libraryState.currentCategory = 'all';
					libraryState.currentElementType = 'all';
					libraryState.searchText = '';

					applyProductCategoryConstraints();

					const allowed = Array.isArray(libraryState.allowedCategories) ? libraryState.allowedCategories : [];
					const tagsForUI = allowed.length
						? libraryState.tags.filter((t) => allowed.includes(String(t.slug || '').toLowerCase()))
						: libraryState.tags;

					const combinedData = {
						elements: [],
						tags: tagsForUI,
						elementTypes: libraryState.elementTypes,
						types: libraryState.types
					};

					const itemOrderTemplate = wp.template('elementor-vp-library-modal-order');
					const elementTypeOrderTemplate = wp.template('elementor-vp-library-modal-element-type-order');
					const headerMenuTemplate = wp.template('elementor-vp-library-header-menu');

					$('#vp-library-modal #elementor-template-library-templates-container').empty();
					$(itemOrderTemplate(combinedData)).appendTo($('#vp-library-modal #elementor-template-library-filter-toolbar-remote'));
					$(elementTypeOrderTemplate(combinedData)).appendTo($('#vp-library-modal #elementor-template-library-filter-toolbar-remote'));
					$(headerMenuTemplate(combinedData)).appendTo($('#vp-library-modal #elementor-vp-library-header-menu'));

					$('#vp-library-modal .vp-load-more-wrap').show();

					$('#vp-library-modal #elementor-template-library-filter-subtype').select2({ allowClear: true, placeholder: $('#vp-library-modal #elementor-template-library-filter-subtype').data('placeholder') });
					$('#vp-library-modal #elementor-template-library-filter-element-type').select2({ allowClear: true, placeholder: $('#vp-library-modal #elementor-template-library-filter-element-type').data('placeholder') });

					if (Array.isArray(libraryState.allowedCategories) && libraryState.allowedCategories.length === 1) {
						$('#vp-library-modal #elementor-template-library-filter').hide();
						$('#vp-library-modal #elementor-template-library-filter-subtype')
							.val(libraryState.allowedCategories[0])
							.trigger('change.select2');
					} else {
						$('#vp-library-modal #elementor-template-library-filter').show();
					}

					setupFilterListeners();
					$('#vp-library-modal .elementor-template-library-menu-item').off('click.vpTab').on('click.vpTab', function () {
						setActiveTab($(this).data('tab'));
					});
					const defaultTab = 'section';
					setActiveTab(defaultTab);

					$('#vp-library-modal .vp-load-more-btn').off('click.vpLoadMore').on('click.vpLoadMore', function () {
						if ($(this).hasClass('vp-load-more-done')) return;
						libraryState.currentPage++;
						renderTemplates(true);
					});

					const $scrollContainer = $('#vp-library-modal .elementor-templates-modal__content');
					if ($scrollContainer.length) {
						$scrollContainer.off('scroll.vpInfinite').on('scroll.vpInfinite', function () {
							if (!hasMorePages()) return;
							const el = this;
							const threshold = 150;
							if (el.scrollTop + el.clientHeight >= el.scrollHeight - threshold) {
								libraryState.currentPage++;
								renderTemplates(true);
							}
						});
					}

					libraryState.initialized = true;
					hideLoader();
				} else {
					$('#vp-library-modal #elementor-template-library-templates-container').html('<div class="vp-notice vp-error">The library can\'t be loaded from the server.</div>');
					hideLoader();
				}
			}).fail(function () {
				$('#vp-library-modal #elementor-template-library-templates-container').html('<div class="vp-notice vp-error">The library can\'t be loaded from the server.</div>');
				hideLoader();
			});
		}

		const text = $('#tmpl-elementor-add-section').text().replace(
			'<div class="elementor-add-section-drag-title',
			'<div class="elementor-add-section-area-button vp-library-modal-btn" title="Template Library">Template Library</div><div class="elementor-add-section-drag-title'
		);
		$('#tmpl-elementor-add-section').text(text);

		$(elementor.$previewContents[0].body).on('click', '.vp-library-modal-btn', () => {
			if (dialog) {
				dialog.show();
				if (libraryState.initialized) {
					libraryState.currentPage = 1;
					libraryState.searchText = $('#vp-library-modal #elementor-template-library-filter-text').val() || '';
					if (Array.isArray(libraryState.allowedCategories) && libraryState.allowedCategories.length === 1) {
						libraryState.currentCategory = libraryState.allowedCategories[0];
					} else {
						libraryState.currentCategory = $('#vp-library-modal #elementor-template-library-filter-subtype').val() || 'all';
					}
					libraryState.currentElementType = $('#vp-library-modal #elementor-template-library-filter-element-type').val() || 'all';
					renderTemplates(false);
				} else {
					loadTemplates();
				}
				return;
			}
			const modalOptions = {
				id: 'vp-library-modal',
				headerMessage: $('#tmpl-elementor-vp-library-modal-header').html(),
				message: $('#tmpl-elementor-vp-library-modal').html(),
				className: 'elementor-templates-modal',
				closeButton: true,
				draggable: false,
				hide: { onOutsideClick: true, onEscKeyPress: true },
				position: { my: 'center', at: 'center' }
			};
			dialog = elementorCommon.dialogsManager.createWidget('lightbox', modalOptions);
			dialog.show();
			loadTemplates();
		});

		$(document).on('click', '#vp-library-modal .elementor-templates-modal__header__close', () => {
			if (dialog) dialog.hide();
		});
	});

})(jQuery);
