(function ($) {
    'use strict';
    $.fn.countdown = function (options) {
        return $.fn.countdown.begin(this, $.extend({
            year: 2016,
            month: 1,
            day: 1,
            hour: 0,
            minute: 0,
            second: 0,
            labels: true,
            restart: false,
            onFinish: function () {}
        }, options));
    };

    $.fn.countdown.begin = function (parent, settings) {
        var start = new Date();
        var end = new Date(settings.year, settings.month - 1, settings.day, settings.hour, settings.minute, settings.second);
        var timespan = $.fn.countdown.getTimeRemaining(start, end, settings);

        // Save original duration only once
        if (!settings.original_duration) {
            settings.original_duration = end.getTime() - start.getTime();
            if (settings.restart_duration) {
                settings.original_duration = settings.restart_duration * 60 * 1000;
            }
        }

        // Check if already expired on first load and should hide
        if (!settings.init && settings.target_reached && settings.when_expired === 'hide' && !settings.restart) {
            parent.hide();
            parent.closest('.vp-countdown-wrapper').hide();
            return;
        }

        // Filter units based on show/hide settings
        var filteredTimespan = {};
        var unitLabels = {
            'days': settings.days_label || 'days',
            'hours': settings.hours_label || 'hours',
            'minutes': settings.minutes_label || 'minutes',
            'seconds': settings.seconds_label || 'seconds'
        };

        $.each(timespan, function (k, v) {
            // Check if this unit should be shown
            var showKey = 'show_' + k;
            if (settings[showKey] === 'yes' || settings[showKey] === undefined || settings[showKey] === '') {
                filteredTimespan[k] = v;
            }
        });

        // Helper function to wrap each digit in a span
        function wrapDigits(value) {
            var timeStr = (value < 10 ? '0' + value : value.toLocaleString()).toString();
            var digits = timeStr.split('');
            var $timeContainer = $('<span/>').addClass('time');
            $.each(digits, function(i, digit) {
                $timeContainer.append($('<span/>').addClass('digit').text(digit));
            });
            return $timeContainer;
        }

        if (!settings.init) {
            $.each(filteredTimespan, function (k, v) {
                var container = $('<div/>').addClass('wc-timezone-data').attr('id', k).attr('data-type', k);
                var wrapper = $('<div/>').addClass('wrapper');
                var time = wrapDigits(v);

                if (settings.labels) {
                    var customLabel = unitLabels[k] || k;
                    // Handle singular/plural: if value is 1 and label ends with 's', remove the 's'
                    var labelText = customLabel;
                    if (v === 1 && customLabel.endsWith('s') && customLabel.length > 1) {
                        labelText = customLabel.slice(0, -1);
                    } else if (v !== 1 && !customLabel.endsWith('s') && customLabel.length > 0) {
                        // If value is not 1 and label doesn't end with 's', add 's' (optional - user can control this)
                        // Actually, let's not auto-add 's' - let user control it completely
                    }
                    var label = $('<span/>').addClass('label').text(labelText);
                    container.append(wrapper.append(time).append(label));
                } else {
                    container.append(wrapper.append(time));
                }

                parent.append(container.addClass('animated rotateIn'));
            });

            settings.init = true;
        } else {
            $.each(filteredTimespan, function (k, v) {
                var $unit = $('#' + k, parent);
                if ($unit.length) {
                    var $timeContainer = $unit.find('.time');
                    if ($timeContainer.length) {
                        $timeContainer.empty();
                        var timeStr = (v < 10 ? '0' + v : v.toLocaleString()).toString();
                        var digits = timeStr.split('');
                        $.each(digits, function(i, digit) {
                            $timeContainer.append($('<span/>').addClass('digit').text(digit));
                        });
                    }
                    if (settings.labels) {
                        var customLabel = unitLabels[k] || k;
                        // Handle singular/plural: if value is 1 and label ends with 's', remove the 's'
                        var labelText = customLabel;
                        if (v === 1 && customLabel.endsWith('s') && customLabel.length > 1) {
                            labelText = customLabel.slice(0, -1);
                        }
                        $unit.find('.label').text(labelText);
                    }
                }
            });

            // Hide or show units based on settings
            $.each(['days', 'hours', 'minutes', 'seconds'], function (i, k) {
                var showKey = 'show_' + k;
                var $unit = $('#' + k, parent);
                if ($unit.length) {
                    if (settings[showKey] === 'no') {
                        $unit.hide();
                    } else {
                        $unit.show();
                    }
                }
            });
        }

        if (settings.target_reached) {
            if (!settings.finished_called) {
                settings.finished_called = true;
                settings.onFinish();
                
                // Handle when expired option
                if (settings.when_expired === 'hide') {
                    parent.fadeOut(300, function() {
                        $(this).hide();
                    });
                    parent.closest('.vp-countdown-wrapper').hide();
                    return; // Stop the countdown
                }
            }

            if (settings.restart) {
                // Restart: shift new target date by original duration from now
                const durationMinutes = settings.restart_duration || (settings.original_duration / (1000 * 60));
                const newTarget = new Date(new Date().getTime() + (durationMinutes * 60 * 1000));

                settings.year = newTarget.getFullYear();
                settings.month = newTarget.getMonth() + 1;
                settings.day = newTarget.getDate();
                settings.hour = newTarget.getHours();
                settings.minute = newTarget.getMinutes();
                settings.second = newTarget.getSeconds();

                settings.init = false;
                settings.target_reached = false;
                settings.finished_called = false;

                setTimeout(function () {
                    $.fn.countdown.begin(parent.empty(), settings);
                    // Show label when countdown restarts
                    parent.closest('.vp-countdown-wrapper').show();
                }, 1000);
            } else if (settings.when_expired === 'hide') {
                // If not restarting and should hide, don't continue
                return;
            }
        } else {
            setTimeout(function () {
                $.fn.countdown.begin(parent, settings);
            }, 1000);
        }
    };

    $.fn.countdown.singularize = function (str) {
        return str.substr(0, str.length - 1);
    };

    $.fn.countdown.getTimeRemaining = function (start, end, settings) {
        var timeleft = end.getTime() - start.getTime();
        timeleft = (timeleft < 0 ? 0 : timeleft);

        if (timeleft === 0) {
            settings.target_reached = true;
        }

        var remaining = {};
        if (settings.countdown_type === 'day') {
            remaining.days = Math.floor(timeleft / (24 * 60 * 60 * 1000));
            remaining.hours = Math.floor((timeleft % (24 * 60 * 60 * 1000)) / (1000 * 60 * 60));
            remaining.minutes = Math.floor((timeleft % (1000 * 60 * 60)) / (1000 * 60));
            remaining.seconds = Math.floor((timeleft % (1000 * 60)) / 1000);
        } else {
            var totalHours = Math.floor(timeleft / (1000 * 60 * 60));
            remaining.hours = totalHours;
            remaining.minutes = Math.floor((timeleft % (1000 * 60 * 60)) / (1000 * 60));
            remaining.seconds = Math.floor((timeleft % (1000 * 60)) / 1000);
        }

        return remaining;
    };

    function initCountdown($scope) {
        var $countdown = $scope.find('.wc-woo-products-countdown');
        if (!$countdown.length) return;
        $countdown.each(function () {
            var $this = $(this);
            
            // Skip if already initialized (prevent duplicate initialization)
            if ($this.data('countdown-initialized')) {
                return;
            }
            
            // Mark as initialized
            $this.data('countdown-initialized', true);
            
            var $is_restart = $(this).data('restart'),
                restartDuration = parseInt($this.data('duration'), 10),
                $restart;
            if ($is_restart == 'yes') {
                $restart = true;
            } else {
                $restart = false;
            }
            var whenExpired = $this.data('when-expired') || 'show_zero';
            var showDays = $this.data('show-days') || 'yes';
            var showHours = $this.data('show-hours') || 'yes';
            var showMinutes = $this.data('show-minutes') || 'yes';
            var showSeconds = $this.data('show-seconds') || 'yes';
            
            // Check for start date
            var hasStartDate = $this.data('has-start-date') == '1' || $this.data('has-start-date') == 1;
            var startDate = null;
            if (hasStartDate) {
                var startYear = parseInt($this.data('start-year'), 10);
                var startMonth = parseInt($this.data('start-month'), 10);
                var startDay = parseInt($this.data('start-day'), 10);
                var startHour = parseInt($this.data('start-hour'), 10) || 0;
                var startMinute = parseInt($this.data('start-minute'), 10) || 0;
                var startSecond = parseInt($this.data('start-second'), 10) || 0;
                
                if (startYear && startMonth && startDay) {
                    startDate = new Date(startYear, startMonth - 1, startDay, startHour, startMinute, startSecond);
                    var now = new Date();
                    
                    // If current time is before start date, hide countdown and label
                    if (now < startDate) {
                        $this.hide();
                        // Also hide the label if it exists
                        var countdownId = $this.attr('id');
                        if (countdownId) {
                            var widgetId = countdownId.replace('wc-woo-products-countdown-', '');
                            var $label = $('#vp-countdown-label-' + widgetId);
                            if ($label.length) {
                                $label.hide();
                            }
                        }
                        // Set up interval to check when start date is reached
                        var checkStartDate = setInterval(function() {
                            var now = new Date();
                            if (now >= startDate) {
                                clearInterval(checkStartDate);
                                $this.show();
                                var countdownId = $this.attr('id');
                                if (countdownId) {
                                    var widgetId = countdownId.replace('wc-woo-products-countdown-', '');
                                    var $label = $('#vp-countdown-label-' + widgetId);
                                    if ($label.length) {
                                        $label.show();
                                    }
                                }
                                // Initialize countdown now that start date is reached
                                $this.countdown({
                                    year: $this.data('year'),
                                    month: $this.data('month'),
                                    day: $this.data('day'),
                                    hour: $this.data('hour'),
                                    minute: $this.data('minute'),
                                    second: $this.data('second'),
                                    countdown_type: $this.data('type'),
                                    restart: $restart,
                                    restart_duration: isNaN(restartDuration) ? 60 : restartDuration,
                                    when_expired: whenExpired,
                                    show_days: showDays,
                                    show_hours: showHours,
                                    show_minutes: showMinutes,
                                    show_seconds: showSeconds,
                                    days_label: $this.data('days-label') || 'days',
                                    hours_label: $this.data('hours-label') || 'hours',
                                    minutes_label: $this.data('minutes-label') || 'minutes',
                                    seconds_label: $this.data('seconds-label') || 'seconds',
                                });
                            }
                        }, 1000); // Check every second
                        return; // Don't initialize countdown yet
                    }
                }
            }
            
            // Initialize countdown normally
            $this.countdown({
                year: $this.data('year'),
                month: $this.data('month'),
                day: $this.data('day'),
                hour: $this.data('hour'),
                minute: $this.data('minute'),
                second: $this.data('second'),
                countdown_type: $this.data('type'),
                restart: $restart,
                restart_duration: isNaN(restartDuration) ? 60 : restartDuration,
                when_expired: whenExpired,
                show_days: showDays,
                show_hours: showHours,
                show_minutes: showMinutes,
                show_seconds: showSeconds,
                days_label: $this.data('days-label') || 'days',
                hours_label: $this.data('hours-label') || 'hours',
                minutes_label: $this.data('minutes-label') || 'minutes',
                seconds_label: $this.data('seconds-label') || 'seconds',
            });
        });
    }

    // Initialize on Elementor frontend (for editor and frontend)
    (function checkElementor_countdown() {
        if (typeof elementorFrontend !== "undefined" && elementorFrontend.hooks) {
            elementorFrontend.hooks.addAction('frontend/element_ready/vp_countdown.default', initCountdown);
        } else {
            setTimeout(checkElementor_countdown, 50); // re-check every 50ms
        }
    })();

    // Fallback initialization for frontend (when Elementor hooks might not fire)
    jQuery(document).ready(function($) {
        // Initialize countdowns that are already in the DOM
        initCountdown($('body'));
        
        // Also initialize on Elementor frontend init (for AJAX loaded content)
        if (typeof elementorFrontend !== "undefined") {
            elementorFrontend.hooks.addAction('frontend/init', function() {
                initCountdown($('body'));
            });
        }
    });

    // Initialize on AJAX content load (for dynamic content)
    jQuery(document).on('elementor/popup/show', function() {
        setTimeout(function() {
            initCountdown(jQuery('body'));
        }, 100);
    });

    //Initialize on search results 
    jQuery(document).on('cubewp_search_results_loaded', function () {
        initCountdown(jQuery('body'));
    });

}(jQuery));