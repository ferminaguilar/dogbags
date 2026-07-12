/**
 * NextWP Admin JavaScript
 * 
 * Handles template setup process including:
 * - Theme installation
 * - Plugin installation
 * - Content import
 * - Status updates
 * 
 * @package    NextWP
 * @subpackage Admin
 * @since      1.0.0
 * @license    GPL-2.0+
 */

// Configuration constants
const MAX_RETRIES = 3;
const RETRY_DELAY = 2000;

window.onload = function () {
    const nwploader = document.getElementById('nwp-template-loader-main');
    if (nwploader) {
        nwploader.style.display = 'none';
    }

    const nextwpWizard = document.getElementById('nextwp-wizard');
    if (nextwpWizard) {
        nextwpWizard.style.display = 'block';
    }
};


// DOM Elements
const startProcessButton = document.getElementById('startProcess');
const getNowButton = document.getElementById('NWP_getNow');

// Initialize event listeners
if (startProcessButton) {
    startProcessButton.addEventListener('click', function(event) {
        // Verify nonce before proceeding
        const nonce = this.getAttribute('data-nonce');
        if (!verifyTemplateNonce(nonce)) {
            console.error('NextWP: Invalid nonce for template operations');
            return;
        }
        
        startSetup(event);
    });
}

// Run showProcess if step=2 is already in the URL
if (getQueryParam('step') === '2') {
    showProcess();
    const frame = document.getElementById('nwp-template-frame');
    if (frame) {
        frame.remove();
    }
}

// Attach event listener to "Get Now" button
if (getNowButton) {
    getNowButton.addEventListener('click', function (event) {
        // Verify nonce before proceeding
        const nonce = this.getAttribute('data-nonce');
        if (!verifyTemplateNonce(nonce)) {
            console.error('NextWP: Invalid nonce for template operations');
            return;
        }
        
        showProcess();
        addQueryString('step', '2');
        getNowButton.style.display = 'none';
        const previewOtherOptions = document.getElementById('nwp-preview-other');
        if (previewOtherOptions) {
            previewOtherOptions.style.display = 'none';
        }
    });
}


/**
 * Validates a template ID
 * @param {string} templateId 
 * @returns {boolean} True if valid
 */
function isValidTemplateId(templateId) {
    return /^\d+$/.test(templateId);
}

/**
 * Verifies nonce for template operations
 * @param {string} nonce The nonce to verify
 * @returns {boolean} True if nonce is valid
 */
function verifyTemplateNonce(nonce) {
    if (!nextwp_params || !nextwp_params.template_operations_nonce) {
        console.error('NextWP: Template operations nonce not available');
        return false;
    }
    return nonce === nextwp_params.template_operations_nonce;
}

function showProcess() {
    // Get nonce from button data attribute
    const startProcessBtn = document.getElementById('startProcess');
    if (startProcessBtn) {
        const nonce = startProcessBtn.getAttribute('data-nonce');
        if (!verifyTemplateNonce(nonce)) {
            console.error('NextWP: Invalid nonce for template operations');
            return;
        }
    }
    
    const templateDisplayContainer = document.querySelector('.template-display-container');
    const processStep2 = document.getElementById('nwp_process_step_2');

    if (templateDisplayContainer) {
        // Hide the template display container
        templateDisplayContainer.style.display = 'none';
    }

    if (processStep2) {
        // Show the process step 2 container
        processStep2.style.display = 'block';
    }
    history.pushState({
        page: 'template_process_step_2'
    }, '', window.location.href);
}

// Handle browser back button
window.addEventListener('popstate', function (event) {
    const state = event.state;

    if (state && state.page === 'template_process_step_2') {
        // Handle popstate specific to this page
        const templateDisplayContainer = document.querySelector('.template-display-container');
        const processStep2 = document.getElementById('nwp_process_step_2');

        if (templateDisplayContainer) {
            templateDisplayContainer.style.display = 'block';
        }
        if (processStep2) {
            processStep2.style.display = 'none';
        }
    } else {
        // Handle other popstate events (e.g., for other pages/tasks)
        console.log('Handling popstate for a different task or page', state);
    }
});



function addQueryString(key, value) {
    const url = new URL(window.location.href); // Get the current URL
    url.searchParams.set(key, value); // Add or update the query parameter
    history.pushState(null, '', url.toString()); // Update the URL without reloading the page
}

function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

function updateStatus(message, status = false, currentStep = 0) {
    const statusDiv = document.getElementById('nwp_import_process_status');
    statusDiv.classList.remove('hide');

    // Create document fragment for safe DOM manipulation
    const fragment = document.createDocumentFragment();

    var totalSteps = progressSteps();
    // Add percentage progress at the top
    if (currentStep > 0 && totalSteps > 0) {
        const percent = Math.min(Math.round((currentStep / totalSteps) * 100), 100);
        const progressBar = document.getElementById('nwp-progress-bar');
        if (progressBar) {
            progressBar.style.width = percent + '%';
        }
    }

    if (typeof message === 'string') {
        const p = document.createElement('p');
        p.textContent = message;
        fragment.appendChild(p);
    } else if (typeof message === 'object') {
        for (const [plugin, pluginStatus] of Object.entries(message)) {
            const p = document.createElement('p');
            p.textContent = `${plugin}: ${pluginStatus}`;
            fragment.appendChild(p);
        }
    }

    // Clear existing content safely
    while (statusDiv.firstChild) {
        statusDiv.removeChild(statusDiv.firstChild);
    }
    statusDiv.appendChild(fragment);

    if (status == true) {
        const titleElement = document.querySelector('.nwp-import-process-title');
        const descElement = document.querySelector('.nwp-import-process-desc');
        if (titleElement) {
            titleElement.innerHTML = '<img draggable="false" role="img" class="emoji" alt="🎉" src="https://s.w.org/images/core/emoji/15.1.0/svg/1f389.svg"><br>Congratulations! Your site is ready!';
        }

        if (titleElement) {
            descElement.innerHTML = 'The website was ready in <b>seconds!</b>';
        }

        const tweetBox = document.getElementById('nwp_after_import_tweet');
        if (tweetBox) {
            tweetBox.style.display = 'block';
        }

        const nextwpStepWrapper = document.getElementById('nextwp-wizard');
        if (nextwpStepWrapper) {
            nextwpStepWrapper.classList.add('nextwp-congrats-screen');
        }

        // const link1 = document.createElement('a');
        // link1.className = 'last-step-site-btn';
        // link1.href = nextwp_params.site_url;
        // link1.target = '_blank';
        // link1.textContent = 'Visit Your Site';

        // const link2 = document.createElement('a');
        // link2.className = 'last-step-dashboard-btn';
        // link2.href = nextwp_params.admin_url;
        // link2.target = '_blank';
        // link2.textContent = 'Visit Admin Dashboard';

        // tweetBox.appendChild(document.createElement('p'));
        // tweetBox.appendChild(link1);
        // tweetBox.appendChild(document.createTextNode(' '));
        // tweetBox.appendChild(link2);
        //startAnimations();
    }
}

function updateLoader(status = false) {
    const loaderDiv = document.getElementById('nwp_import_process_loader');
    const startProcessImg = document.getElementById('nwp-start-png-image');
    startProcessImg.style.display = 'none';
    loaderDiv.style.display = 'block';
    if (status == true) {
        loaderDiv.style.display = 'none';
    }
}

function percentCounter(after = '') {
    const step2Div = document.getElementById('nwp_process_step_2');
    const totalPlugins = parseInt(step2Div.dataset.totalPlugin, 10);
    let result = totalPlugins;

    if (after === 'plugins') {
        result += 1;
    }
    if (after === 'contentImport') {
        result += 2;
    }

    return result;
}

function progressSteps() {
    const step2Div = document.getElementById('nwp_process_step_2');
    return parseInt(step2Div.dataset.totalSteps, 10);
}

async function startSetup(event) {
    try {
        // Handle case where event might be undefined
        let button;
        if (event && event.target) {
            button = event.target;
        } else {
            // Fallback: get the button by ID if event is not available
            button = document.getElementById('startProcess');
        }
        
        if (!button) {
            console.error('NextWP: Could not find start process button');
            return;
        }
        
        const templateId = button.getAttribute('nextwp-template-id');
        if (!templateId) {
            console.error('NextWP: Template ID not found on button');
            return;
        }
        
        button.style.display = 'none';
        updateLoader();

        const titleElement = document.querySelector('.nwp-import-process-title');
        if (titleElement) {
            titleElement.textContent = "We're building your site...";
        }

        const descElement = document.querySelector('.nwp-import-process-desc');
        if (descElement) {
            descElement.textContent = "This usually takes less than a minute. Hang tight while the magic happens.";
        }

        updateStatus('Required theme and plugins are being installed. Please wait while we set everything up for you.', false, .5);

        // Step 1: Install the theme
        const themeResponse = await installTheme(templateId);
        const themeData = await themeResponse.json();
        if (themeData.status !== 'success') {
            throw new Error(
                themeData.message ||
                'Theme installation failed. Please ensure the theme file is a valid ZIP, your server meets the requirements, and you have sufficient permissions. Contact support if the issue persists.'
            );
        }
        updateStatus(themeData.message, false, 1);

        // Step 2: Install plugins
        let nextCall = null;
        await handlePluginInstallation(templateId, nextCall);
        const currentPercent = percentCounter('plugins');

        // Step 3: Import content
        updateStatus('Content files are being downloaded. Please wait while we retrieve all necessary data.', false, currentPercent);
        nextCall = null;
        await handleContentImport(templateId, nextCall);

        updateLoader(true);

    } catch (error) {
        console.error('NextWP: Setup failed:', error);
        updateStatus(`Setup failed: ${error.message}`);
    }
}

async function handlePluginInstallation(templateId, nextCall) {
    var nextcall = null;
    var pluginCount = 1;

    if (nextCall !== null) {
        var nextcall = nextCall;
        pluginCount = nextCall;
    }
    const currentPercent = pluginCount + 1;

    // Make the first call with just the templateId, then subsequent calls with nextCall
    const pluginResponse = await installPlugins(templateId, nextcall);
    const pluginData = await pluginResponse.json();

    if (pluginData.status === 'continue') {
        updateStatus(pluginData.message, false, currentPercent);
        nextcall = pluginData.nextcall;
        await handlePluginInstallation(templateId, nextcall);
    } else if (pluginData.status === 'success') {
        updateStatus(pluginData.message, false, currentPercent);
    } else {
        throw new Error(
            pluginData.message ||
            'Plugin installation failed. Please check the plugin file format and ensure your server meets the requirements. Contact support if the issue persists.'
        );
    }
}

async function handleContentImport(templateId, nextCall) {
    var nextcall = null;

    if (nextCall !== null) {
        var nextcall = nextCall;
    }

    // Make the first call with just the templateId, then subsequent calls with nextCall
    const contentResponse = await importContent(templateId, nextcall);
    const contentData = await contentResponse.json();

    if (contentData.status === 'continue') {
        updateStatus(contentData.message);
        nextcall = contentData.nextcall;
        if (nextcall == 'media') {
            const currentPercent = percentCounter('contentImport');
            updateStatus('Media files are being downloaded. This may take a few moments, please be patient.', false, currentPercent);
        }
        await handleContentImport(templateId, nextcall);
    } else if (contentData.status === 'success') {
        updateStatus(contentData.message, true, progressSteps());
    } else {
        throw new Error(contentData.message || 'Content import failed');
    }
}



function installTheme(templateId) {
    return fetch(`${nextwp_params.apiUrl}setup-helper/v1/theme-install`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': nextwp_params.nonce // Ensure nonce for authentication
        },
        body: JSON.stringify({
            template_id: templateId // Include template ID in the request body
        })
    });
}

function installPlugins(templateId, nextCall) {
    return fetch(`${nextwp_params.apiUrl}setup-helper/v1/plugin-install`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': nextwp_params.nonce
        },
        body: JSON.stringify({
            template_id: templateId,
            nextcall: nextCall
        })
    });
}

function importContent(templateId, nextCall) {
    return fetch(`${nextwp_params.apiUrl}setup-helper/v1/content-import`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': nextwp_params.nonce
        },
        body: JSON.stringify({
            template_id: templateId,
            nextcall: nextCall
        })
    });
}

jQuery(document).ready(function ($) {
    $('#sync-now-button').on('click', function (e) {
        e.preventDefault();

        // Disable button and show a loading spinner
        const $button = $(this);
        $button.prop('disabled', true).html('<span class="dashicons dashicons-update dashicons-spin"></span> Syncing...');

        // Send AJAX request
        $.ajax({
            url: nextwp_params.ajax_url,
            type: 'POST',
            data: {
                action: 'get_and_save_templates',
                nonce: nextwp_params.template_nonce,
                is_ajax: true
            },
            success: function (response) {
                if (response.success) {
                    // Reload the page
                    setTimeout(function () {
                        const url = new URL(window.location.href);
                        url.searchParams.set('step', '3');
                        window.location.href = url.toString();
                    }, 100);
                }
            },
            error: function () {
                console.log('Error occurred while syncing!');
            },
            complete: function () {
                // Re-enable button after request is complete
                $button.prop('disabled', false).html('<span class="dashicons dashicons-update"></span> Synced Successfully');
            },
        });
    });
});

jQuery(function ($) {
    let currentStep = parseInt($('.current-step').data('current-step')) || 1;

    function isSelectionMade() {
        let $step = $('#nextwp-step-' + currentStep);
        let isValid = true;

        $step.find('.required-field').each(function () {
            const $field = $(this);

            if ($field.is(':radio')) {
                const name = $field.attr('name');
                if ($step.find('input[name="' + name + '"]:checked').length === 0) {
                    isValid = false;
                    return false;
                }
            } else if ($field.is('select')) {
                if (!$field.val()) {
                    isValid = false;
                    return false;
                }
            } else if ($field.is(':input')) {
                if (!$field.val().trim()) {
                    isValid = false;
                    return false;
                }
            }
        });

        return isValid;
    }

    function toggleNextButton() {
        if (currentStep >= 3) return; // Skip for step 3
        $('.nextwp-next-btn').prop('disabled', !isSelectionMade());
    }

    function updateProgressBar() {
        const totalSteps = $('.nextwp-step').length + 1;
        const progressPercentage = (currentStep / totalSteps) * 100;
        $('.nextwp-steps-progress-bar').css('width', progressPercentage + '%');
    }


    function showStep(step) {
        $('.nextwp-step').hide();
        $('#nextwp-step-' + step).show();

        if (step === 3) {
            $('#sync-now-button').show();
            $('.nextwp-next-btn').hide();
        } else {
            $('#sync-now-button').hide();
            $('.nextwp-next-btn').show();
        }

        if (step === 1) {
            $('.nextwp-prev-btn').hide();
        } else {
            $('.nextwp-prev-btn').show();
        }

        currentStep = step;
        toggleNextButton();
        updateProgressBar();
    }

    showStep(currentStep);

    // $('.nextwp-next-btn').on('click', function (e) {
    //     e.preventDefault();
    //     if (currentStep < 3) {
    //         showStep(currentStep + 1);
    //     }
    // });
    $(document).on('click', '.nextwp-steps-radio-options input[type=radio]', function () {
        if (currentStep < 3) {
            showStep(currentStep + 1);
        }
    });


    $('.nextwp-prev-btn').on('click', function (e) {
        e.preventDefault();
        if (currentStep > 1) {
            showStep(currentStep - 1);
            const url = new URL(window.location.href);
            url.searchParams.delete('step');
            url.searchParams.delete('id');
            window.history.replaceState({}, '', url.pathname + url.search);
        }
    });

    $(document).on('change input', '.required-field', function () {
        toggleNextButton();
    });


    const $stepThree = $('#nextwp-step-3');
    if ($stepThree.length) {
        setTimeout(function () {
            $stepThree.find('.nextwp-templates-loader').fadeOut(500, function () {
                $stepThree.find('.nextwp-templates-content').fadeIn(500);
            });
        }, 3000);
    }


    jQuery(document).ready(function ($) {
        $('#nwp-preview-other').on('click', function (e) {
            e.preventDefault();
            const $sliderContainer = $('.nextwp-templates-slider-container');
            const $wrapper = $('.nextwp-step-wrapper-content');
            $wrapper.toggleClass('is-visible');
            if ($wrapper.hasClass('is-visible')) {
                $('#nwp-preview-other').text('Hide Other Options');
            } else {
                $('#nwp-preview-other').text('Preview Other Options');
            }
        });
    });

    new Swiper('.nextwp-templates-slider.swiper', {
        loop: true,
        autoplay: {
            delay: 2000,
            disableOnInteraction: false,
        },
        arrows: false,
        slidesPerView: 8,
        spaceBetween: 11,
    });


    const $iframe = $('#nwp-template-frame');
    const $loader = $('#nwp-template-loader');

    if ($iframe.length && $loader.length) {
        $iframe.on('load', function () {
            $loader.fadeOut(300);
        });
    }

    $(document).on('click', '.visit-website', function (e) {
        e.preventDefault();
        window.open(nextwp_params.site_url, '_blank');
        window.location.href = nextwp_params.admin_url;
    });

});