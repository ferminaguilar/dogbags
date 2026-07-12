/**
 * Value Pack CubeWP Map Widget
 * 
 * Handles initialization and rendering of CubeWP Single Map Elementor widgets
 * Uses Leaflet.js (same as CubeWP framework)
 * 
 * @package valuepack-addons
 * @version 1.0.0
 */

/**
 * Initialize Value Pack CubeWP Single Map Widget
 * Reads data from data attributes and initializes the map
 */
function VP_Init_Single_Map($mapContainer) {
  if (!$mapContainer || !$mapContainer.length) {
    return;
  }

  // Check if already initialized (see VP_Refresh_Or_Init_Single_Map for reveal / resize)
  if ($mapContainer.data('map-initialized')) {
    return;
  }

  // Wait for Leaflet and map functions to be available
  function initMap() {
    if (typeof L !== 'undefined' && typeof Tiles === 'function') {
      var tiles = Tiles();
      if (!tiles) {
        return;
      }

      // Get map data from data attributes
      var mapId = $mapContainer.data('map-id') || $mapContainer.attr('data-map-id');
      var mapDataStr = $mapContainer.attr('data-map-locations');
      var mapPinHtml = $mapContainer.data('mappin-html') || $mapContainer.attr('data-mappin-html') || '';
      var mapZoomAttr = parseInt($mapContainer.attr('data-map-zoom'), 10);

      // Parse map data
      var mapData = [];
      if (mapDataStr) {
        try {
          mapData = JSON.parse(mapDataStr);
        } catch (e) {
          console.error('Error parsing map data:', e);
        }
      }

      // Get default map settings
      var mapLat = typeof value_pack_map_params !== 'undefined' ? parseFloat(value_pack_map_params.map_latitude) || 51.5072 : 51.5072;
      var mapLng = typeof value_pack_map_params !== 'undefined' ? parseFloat(value_pack_map_params.map_longitude) || -0.128 : -0.128;
      var mapZoom = typeof value_pack_map_params !== 'undefined' ? parseInt(value_pack_map_params.map_zoom, 10) || 12 : 12;
      if (!isNaN(mapZoomAttr) && mapZoomAttr >= 1 && mapZoomAttr <= 20) {
        mapZoom = mapZoomAttr;
      }

      // Use first location as center if available
      if (mapData && mapData.length > 0 && mapData[0][0] && mapData[0][1]) {
        mapLat = parseFloat(mapData[0][0]);
        mapLng = parseFloat(mapData[0][1]);
      }

      var latlng = L.latLng(mapLat, mapLng);

      // Generate unique map ID if not provided, or if already used by another element (e.g. duplicate in loop)
      if (!mapId) {
        mapId = 'vp-map-' + (typeof cwp_rand_id === 'function' ? cwp_rand_id(8) : Math.random().toString(36).slice(2, 10));
      }
      var existingEl = document.getElementById(mapId);
      if (existingEl && !$mapContainer[0].contains(existingEl)) {
        mapId = 'vp-map-' + (typeof cwp_rand_id === 'function' ? cwp_rand_id(8) : Math.random().toString(36).slice(2, 10));
      }

      // Create map container
      $mapContainer.empty().html('<div id="' + mapId + '" class="vp-cubewp-map"></div>');

      var map = L.map(mapId, {
        center: latlng,
        zoom: mapZoom,
        layers: [tiles],
        fullscreenControl: false
      });

      $mapContainer.data('map-initialized', true);
      $mapContainer.data('vp-leaflet-map', map);
      if (typeof window.VP_LeafletMaps === 'undefined') {
        window.VP_LeafletMaps = {};
      }
      window.VP_LeafletMaps[mapId] = map;

      map.addControl(new L.Control.Fullscreen());

      if (!mapData || !mapData.length) {
        // No data, just show the map centered
        setTimeout(function() {
          map.invalidateSize();
        }, 100);
        return;
      }

      var markers = (typeof CWP_MAP_SETTINGS !== 'undefined' && CWP_MAP_SETTINGS.cluster && CWP_MAP_SETTINGS.cluster.enabled) ?
        L.markerClusterGroup() :
        L.layerGroup();

      var showmap = false;
      var validMarkerCount = 0;
      var firstValidLatLng = null;

      for (var i = 0; i < mapData.length; i++) {
        var a = mapData[i];
        var lat = parseFloat(a[0]);
        var lng = parseFloat(a[1]);

        if (isNaN(lat) || isNaN(lng)) {
          continue;
        }

        if (!checkIfValidlatitudeAndlongitude(lat + ',' + lng)) {
          continue;
        }

        showmap = true;
        validMarkerCount++;
        if (!firstValidLatLng) {
          firstValidLatLng = [lat, lng];
        }

        var title = a[2] || '';
        var url = a[3] || '#';
        var thumbnail = a[4] || '';
        var pinIconUrl = a[5] || '';

        var markerOptions = {
          title: title
        };

        // Use HTML pin if available (check for non-empty string)
        if (mapPinHtml && mapPinHtml.trim() !== '' && typeof createCustomIcons === 'function') {
          markerOptions.icon = createCustomIcons(mapPinHtml);
        } 
        // Use image pin if available
        else if (pinIconUrl && pinIconUrl.trim() !== '' && typeof createCustomIcon === 'function') {
          markerOptions.icon = createCustomIcon(pinIconUrl);
        }
        // If no custom icon, use Leaflet's default icon explicitly
        else {
          // Use Leaflet's default blue marker icon
          markerOptions.icon = new L.Icon.Default();
        }

        var marker = L.marker([lat, lng], markerOptions);

        markers.addLayer(marker);
      }

      if (showmap) {
        map.addLayer(markers);
        if (validMarkerCount > 1) {
          map.fitBounds(markers.getBounds(), {
            padding: (typeof CWP_MAP_SETTINGS !== 'undefined' && CWP_MAP_SETTINGS.map && CWP_MAP_SETTINGS.map.fitPadding) ? CWP_MAP_SETTINGS.map.fitPadding : [50, 50],
            maxZoom: mapZoom
          });
        } else if (firstValidLatLng) {
          map.setView(firstValidLatLng, mapZoom);
        }

        setTimeout(function() {
          map.invalidateSize();
        }, 100);
      } else {
        // No valid markers, just center on default location
        map.setView(latlng, mapZoom);
        setTimeout(function() {
          map.invalidateSize();
        }, 100);
      }
    } else {
      // Retry after a short delay
      setTimeout(initMap, 200);
    }
  }

  // Start initialization
  initMap();
}

/**
 * When a map was created hidden (e.g. display:none), Leaflet needs invalidateSize after it becomes visible.
 * Call this on card/tab clicks. If the map is not initialized yet, runs VP_Init_Single_Map.
 */
function VP_Refresh_Or_Init_Single_Map($mapContainer) {
  if (!$mapContainer || !$mapContainer.length) {
    return;
  }

  var mapId = $mapContainer.attr('data-map-id') || $mapContainer.data('map-id');
  var map = $mapContainer.data('vp-leaflet-map');
  if (!map && mapId && typeof window.VP_LeafletMaps !== 'undefined' && window.VP_LeafletMaps[mapId]) {
    map = window.VP_LeafletMaps[mapId];
  }

  if (map && typeof map.invalidateSize === 'function') {
    var refresh = function () {
      try {
        map.invalidateSize(true);
      } catch (e) {}
    };
    refresh();
    setTimeout(refresh, 100);
    setTimeout(refresh, 350);
    return;
  }

  if ($mapContainer.data('map-initialized')) {
    if (mapId && typeof window.VP_LeafletMaps !== 'undefined' && window.VP_LeafletMaps[mapId]) {
      try {
        window.VP_LeafletMaps[mapId].remove();
      } catch (e) {}
      delete window.VP_LeafletMaps[mapId];
    }
    $mapContainer.removeData('map-initialized');
    $mapContainer.removeData('vp-leaflet-map');
    $mapContainer.empty();
  }

  VP_Init_Single_Map($mapContainer);
}

/**
 * Initialize all Value Pack CubeWP Single Map widgets
 */
function VP_Init_All_Single_Maps($scope) {
  if (!$scope || !$scope.length) {
    $scope = jQuery(document);
  }

  var $mapContainers = $scope.find('.vp-cubewp-map-container');
  if (!$mapContainers.length) {
    return;
  }

  $mapContainers.each(function() {
    VP_Init_Single_Map(jQuery(this));
  });
}

// Initialize on document ready
(function($) {
  // Function to initialize maps when ready
  function initMapsWhenReady($scope) {
    if (!$scope || !$scope.length) {
      $scope = $(document);
    }

    function checkAndInit() {
      if (typeof L !== 'undefined' && typeof Tiles === 'function') {
        VP_Init_All_Single_Maps($scope);
      } else {
        setTimeout(checkAndInit, 200);
      }
    }
    checkAndInit();
  }

  // Initialize on document ready (frontend)
  $(document).ready(function() {
    setTimeout(function() {
      initMapsWhenReady($(document));
    }, 300);
  });

  // Initialize for Elementor Frontend
  if (typeof elementorFrontend !== "undefined" && elementorFrontend.hooks) {
    elementorFrontend.hooks.addAction('frontend/element_ready/vp_cubewp_map.default', function($scope) {
      initMapsWhenReady($scope);
    });
  }

  // Initialize for Elementor Editor Preview
  if (typeof elementor !== "undefined") {
    // Listen for preview loaded event (editor iframe)
    if (elementor.on) {
      elementor.on('preview:loaded', function() {
        setTimeout(function() {
          var $previewDoc = elementor.$previewContents && elementor.$previewContents.length 
            ? $(elementor.$previewContents[0]) 
            : $(document);
          initMapsWhenReady($previewDoc);
        }, 500);
      });
    }

    // Listen for element render/update in editor
    if (elementor.hooks && elementor.hooks.addAction) {
      // When widget settings are changed
      elementor.hooks.addAction('panel/open_editor/widget/vp_cubewp_map', function() {
        setTimeout(function() {
          var $previewDoc = elementor.$previewContents && elementor.$previewContents.length 
            ? $(elementor.$previewContents[0]) 
            : $(document);
          initMapsWhenReady($previewDoc);
        }, 300);
      });

      // When element is rendered
      elementor.hooks.addAction('panel/open_editor/widget', function(panel, model, view) {
        if (model && model.get('widgetType') === 'vp_cubewp_map') {
          setTimeout(function() {
            var $previewDoc = elementor.$previewContents && elementor.$previewContents.length 
              ? $(elementor.$previewContents[0]) 
              : $(document);
            initMapsWhenReady($previewDoc);
          }, 300);
        }
      });
    }

    // Also check periodically in editor mode (fallback)
    if (elementor.$previewContents && elementor.$previewContents.length) {
      var editorCheckInterval = setInterval(function() {
        var $previewDoc = $(elementor.$previewContents[0]);
        if ($previewDoc.find('.vp-cubewp-map-container').length > 0) {
          initMapsWhenReady($previewDoc);
        }
      }, 1000);

      // Stop checking after 30 seconds
      setTimeout(function() {
        clearInterval(editorCheckInterval);
      }, 30000);
    }
  }

  // Fallback: Initialize when window loads (for editor preview)
  $(window).on('load', function() {
    // Check if we're in Elementor editor preview
    if (typeof elementor !== "undefined" && elementor.$previewContents && elementor.$previewContents.length) {
      setTimeout(function() {
        initMapsWhenReady($(elementor.$previewContents[0]));
      }, 500);
    }
  });

  // Direct initialization for editor preview iframe
  // This runs inside the preview iframe
  if (window.parent !== window && typeof window.parent.elementor !== "undefined") {
    // We're in the preview iframe
    function initInPreview() {
      initMapsWhenReady($(document));
    }

    $(document).ready(function() {
      setTimeout(initInPreview, 500);
    });

    // Also listen for when the preview document is ready
    if (document.readyState === 'complete') {
      setTimeout(initInPreview, 500);
    } else {
      $(window).on('load', function() {
        setTimeout(initInPreview, 500);
      });
    }

    // Periodic check in preview iframe (for widgets added dynamically)
    var previewCheckInterval = setInterval(function() {
      var $containers = $('.vp-cubewp-map-container').filter(function() {
        return !$(this).data('map-initialized');
      });
      if ($containers.length > 0) {
        initMapsWhenReady($(document));
      }
    }, 1000);

    // Stop checking after 60 seconds
    setTimeout(function() {
      clearInterval(previewCheckInterval);
    }, 60000);
  }

  // Also listen for DOM mutations in editor (when widgets are added/updated)
  if (typeof MutationObserver !== 'undefined') {
    var observer = new MutationObserver(function(mutations) {
      var hasMapContainer = false;
      mutations.forEach(function(mutation) {
        if (mutation.addedNodes && mutation.addedNodes.length) {
          for (var i = 0; i < mutation.addedNodes.length; i++) {
            var node = mutation.addedNodes[i];
            if (node.nodeType === 1) { // Element node
              if ($(node).find('.vp-cubewp-map-container').length || $(node).hasClass('vp-cubewp-map-container')) {
                hasMapContainer = true;
                break;
              }
            }
          }
        }
      });
      
      if (hasMapContainer) {
        setTimeout(function() {
          var $scope = typeof elementor !== "undefined" && elementor.$previewContents && elementor.$previewContents.length 
            ? $(elementor.$previewContents[0]) 
            : $(document);
          initMapsWhenReady($scope);
        }, 300);
      }
    });

    // Start observing when DOM is ready
    $(document).ready(function() {
      var targetNode = typeof elementor !== "undefined" && elementor.$previewContents && elementor.$previewContents.length
        ? elementor.$previewContents[0].body || document.body
        : document.body;
      
      if (targetNode) {
        observer.observe(targetNode, {
          childList: true,
          subtree: true
        });
      }
    });

    //Initialize on search results 
    $(document).on('cubewp_search_results_loaded', function () {
      VP_Init_All_Single_Maps($(document));
    });

  }
})(jQuery);