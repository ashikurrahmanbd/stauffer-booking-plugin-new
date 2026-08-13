(function($){

    $(document).ready(function(){

        // Main cleaning time selection
        var minHours = 2;
        var maxHours = 6;
        var step = 0.5;

        var recommendations = {
            '2':   'Empfohlen für: 1 Schlafzimmer, 1 Badezimmer',
            '2.5': 'Empfohlen für: 1 Schlafzimmer, 2 Badezimmer',
            '3':   'Empfohlen für: 2 Schlafzimmer, 1 Badezimmer',
            '3.5': 'Empfohlen für: 2 Schlafzimmer, 2 Badezimmer',
            '4':   'Empfohlen für: 3 Schlafzimmer, 1 Badezimmer',
            '4.5': 'Empfohlen für: 3 Schlafzimmer, 2 Badezimmer',
            '5':   'Empfohlen für: 4 Schlafzimmer, 1 Badezimmer',
            '5.5': 'Empfohlen für: 4 Schlafzimmer, 2 Badezimmer',
            '6':   'Empfohlen für: 5 Schlafzimmer, 1 Badezimmer'
        };

        function updateDisplay(hours) {
            $('#cleaning_hours').val(hours);
            $('.service-duration-value').text(hours);

            // $('.duration-recommendation').html(
            //     recommendations[hours] + ' <span class="info-icon" title="this is title">ⓘ</span>'
            // );

            $('.duration-recommendation').html(
                recommendations[hours] +
                `
                <span class="info-tooltip">
                    <span class="info-icon">ⓘ</span>

                    <span class="tooltip-content">
                        Wohnzimmer, Küche und alle Gemeinschaftsbereiche sind inbegriffen!
                    </span>
                </span>
                `
            );
        }

        $('.service-duration-plus').on('click', function() {
            var current = parseFloat($('#cleaning_hours').val());

            if (current < maxHours) {
                current += step;

                if (current > maxHours) {
                    current = maxHours;
                }

                updateDisplay(current);
            }
        });

        $('.service-duration-minus').on('click', function() {
            var current = parseFloat($('#cleaning_hours').val());

            if (current > minHours) {
                current -= step;

                if (current < minHours) {
                    current = minHours;
                }

                updateDisplay(current);
            }
        });

        updateDisplay(2);

        /* Newly Added: More Often weekly schedule logic */
        var weeklyMinCount = 2;
        var weeklyMaxCount = 6;
        var weeklyCount = 2;

        var weekDays = [
            'Montag',
            'Dienstag',
            'Mittwoch',
            'Donnerstag',
            'Freitag',
            'Samstag'
            
        ];

        var timeSlots = [
            '07:00', '07:30', '08:00', '08:30', '09:00',
            '09:30', '10:00', '10:30', '11:00', '11:30',
            '12:00', '12:30', '13:00', '13:30', '14:00',
            '14:30', '15:00', '15:30', '16:00', '16:30',
            '17:00', '17:30', '18:00', '18:30', '19:00',
            '19:30', '20:00'
        ];

        function buildTimeSlots(selectedTime) {
            var html = '';

            timeSlots.forEach(function(time) {
                var activeClass = time === selectedTime ? ' active' : '';

                html += '<button type="button" class="more-often-time-slot' + activeClass + '" data-time="' + time + '">' +
                    time +
                '</button>';
            });

            return html;
        }

        function buildWeekdayOptions(selectedDay) {
            var html = '';

            weekDays.forEach(function(day) {
                var selected = day === selectedDay ? 'selected' : '';

                html += '<option value="' + day + '" ' + selected + '>' + day + '</option>';
            });

            return html;
        }

        function createWeeklyScheduleRow(index) {
            var selectedDay = weekDays[index] || 'Montag';

            return `
                <div class="weekly-schedule-row" data-index="${index}">
                    
                    <div class="weekly-day-field">
                        <label>Wochentag</label>
                        <select class="weekly-day-select" name="weekly_schedule[${index}][day]">
                            ${buildWeekdayOptions(selectedDay)}
                        </select>
                    </div>

                    <div class="weekly-time-field">
                        <label>Start Datum/Uhrzeit</label>

                        <button type="button" class="weekly-time-picker">
                            <span class="weekly-selected-time">08:00</span>
                            <span class="weekly-time-icon">◴</span>
                        </button>

                        <div class="weekly-time-dropdown">
                            ${buildTimeSlots('08:00')}
                        </div>

                        <input type="hidden" class="weekly-time-input" name="weekly_schedule[${index}][time]" value="08:00">
                    </div>

                    <div class="weekly-hours-field">
                        <button type="button" class="weekly-hours-minus">−</button>

                        <span class="weekly-hours-value">2</span>

                        <button type="button" class="weekly-hours-plus">+</button>

                        <span class="weekly-hours-label">Stunden</span>

                        <input type="hidden" class="weekly-hours-input" name="weekly_schedule[${index}][hours]" value="2">
                    </div>

                </div>
            `;
        }

        function renderWeeklyScheduleRows() {
            var rowsWrapper = $('.weekly-schedule-rows');

            rowsWrapper.empty();

            for (var i = 0; i < weeklyCount; i++) {
                rowsWrapper.append(createWeeklyScheduleRow(i));
            }

            $('#weekly_cleaning_count').val(weeklyCount);
            $('.weekly-count-value').text(weeklyCount);

            updateBookingSummary();
        }

        function isMoreOftenSelected() {
            return $('input[name="cleaning_frequency"]:checked').val() === 'more-often';
        }

        function toggleMoreOftenSection() {
            if (isMoreOftenSelected()) {
                $('.cleaning-hours').hide();
                $('.more-often-schedule').show();
            } else {
                $('.more-often-schedule').hide();
                $('.cleaning-hours').show();
            }

            updateBookingSummary();
        }

        function getWeeklyScheduleTotalHours() {
            var total = 0;

            $('.weekly-hours-input').each(function() {
                total += parseFloat($(this).val()) || 0;
            });

            return total;
        }

        function collectWeeklyScheduleSummary() {
            var schedule = [];

            $('.weekly-schedule-row').each(function() {
                var day = $(this).find('.weekly-day-select').val();
                var time = $(this).find('.weekly-time-input').val();
                var hours = $(this).find('.weekly-hours-input').val();

                schedule.push(day + ' ' + time + ', ' + hours + ' Stunden');
            });

            return schedule;
        }
        /* End Newly Added: More Often weekly schedule logic 06-15-2026 */

        // Format minutes for extra service summary
        function formatExtraDuration(minutes) {
            if (minutes === 60) {
                return '1h';
            }

            if (minutes > 60) {
                return (minutes / 60).toFixed(1).replace('.0', '') + 'h';
            }

            return minutes + ' min';
        }

        // Format total cleaning hours
        function formatTotalHours(hours) {
            return hours.toFixed(1).replace('.0', '') + ' Reinigungsstunden';
        }

        // Booking summary update
        function updateBookingSummary() {

            // Frequency
            var frequencyText = $('input[name="cleaning_frequency"]:checked')
                .closest('.frequency-option')
                .find('.option-title')
                .text()
                .trim();

            $('.summary-frequency').text(frequencyText);

            // Main cleaning hours + predefined extra service minutes
            // var mainCleaningHours = parseFloat($('#cleaning_hours').val()) || 0;
            // var extraMinutes = 0;

            //newly added

            var mainCleaningHours = 0;

            if (isMoreOftenSelected()) {
                mainCleaningHours = getWeeklyScheduleTotalHours();
            } else {
                mainCleaningHours = parseFloat($('#cleaning_hours').val()) || 0;
            }

            var extraMinutes = 0;

            //end newly added for more frequent selection

            $('.extra-service-option input[type="checkbox"]:checked').each(function () {
                extraMinutes += parseInt($(this).data('minutes')) || 0;
            });

            var extraHours = extraMinutes / 60;
            var totalHours = mainCleaningHours + extraHours;

            //removed for the more frequent feature
            // $('.cleaning-hours-value').text(formatTotalHours(totalHours));

            //newly added
            if (isMoreOftenSelected()) {
                $('.cleaning-hours-value').text(formatTotalHours(totalHours));

                var weeklySchedule = collectWeeklyScheduleSummary();
                $('.summary-frequency').text(weeklyCount + ' pro Woche');

                if ($('.more-often-summary-list').length === 0) {
                    $('.cleaning-frequency-selection').append('<ul class="more-often-summary-list"></ul>');
                }

                var weeklyList = $('.more-often-summary-list');
                weeklyList.empty();

                weeklySchedule.forEach(function(item) {
                    weeklyList.append('<li>' + item + '</li>');
                });

            } else {
                $('.cleaning-hours-value').text(formatTotalHours(totalHours));
                $('.more-often-summary-list').remove();
            }
            //newly added code for the more frequent selection




            // Extra services summary
            var extraList = $('.cleaning-extra-services-list');
            extraList.empty();

            var selectedExtras = $('.extra-service-option input[type="checkbox"]:checked');

            if (selectedExtras.length === 0) {
                extraList.append('<li>Keine Zusatzleistungen ausgewählt</li>');
            } else {
                selectedExtras.each(function () {
                    var serviceWrapper = $(this).closest('.extra-service-option');
                    var serviceName = serviceWrapper.find('.service-title').text().trim();
                    var minutes = parseInt($(this).data('minutes')) || 0;
                    var duration = formatExtraDuration(minutes);

                    extraList.append(
                        '<li class="extra-service-value">' +
                            serviceName + ' x ' + duration +
                        '</li>'
                    );
                });
            }

            // Date
            var bookingDate = $('.booking-date-input').val();

            if (bookingDate) {
                $('.cleaning-service-booking-date-time').text(bookingDate);
            } else {
                $('.cleaning-service-booking-date-time').text('Kein Datum ausgewählt');
            }

            // Gross Total Calculation
            var hourlyRate = 50;
            var extraOnceFee = 50;

            var mainCleaningPrice = mainCleaningHours * hourlyRate;

            var selectedFrequency = $('input[name="cleaning_frequency"]:checked').val();
            var frequencyExtraFee = 0;

            if (selectedFrequency === 'once') {
                frequencyExtraFee = extraOnceFee;
            }

            var extraServicePrice = (extraMinutes / 60) * hourlyRate;

            var grossTotal = mainCleaningPrice + frequencyExtraFee + extraServicePrice;

            $('.total-amount').html(
                grossTotal + ' <span class="total-amount-currenty">CHF</span>'
            );
        }

        // Summary update events
        // $(document).on('change', 'input[name="cleaning_frequency"]', updateBookingSummary);
        $(document).on('change', 'input[name="cleaning_frequency"]', function() {
            toggleMoreOftenSection();
        });//newly added for frequent selection
        $(document).on('change', '.extra-service-option input[type="checkbox"]', updateBookingSummary);
        $(document).on('change', '.booking-date-input', updateBookingSummary);

        $('.service-duration-plus, .service-duration-minus').on('click', function () {
            setTimeout(updateBookingSummary, 0);
        });

        /* Newly Added: More Often weekly schedule events */
        $(document).on('click', '.weekly-count-plus', function() {
            if (weeklyCount < weeklyMaxCount) {
                weeklyCount++;
                renderWeeklyScheduleRows();
            }
        });

        $(document).on('click', '.weekly-count-minus', function() {
            if (weeklyCount > weeklyMinCount) {
                weeklyCount--;
                renderWeeklyScheduleRows();
            }
        });

        $(document).on('click', '.weekly-hours-plus', function() {
            var row = $(this).closest('.weekly-schedule-row');
            var input = row.find('.weekly-hours-input');
            var value = parseFloat(input.val()) || 2;

            if (value < 6) {
                value += 0.5;
            }

            input.val(value);
            row.find('.weekly-hours-value').text(value);

            updateBookingSummary();

        });

        $(document).on('click', '.weekly-hours-minus', function() {
            var row = $(this).closest('.weekly-schedule-row');
            var input = row.find('.weekly-hours-input');
            var value = parseFloat(input.val()) || 2;

            if (value > 2) {
                value -= 0.5;
            }

            input.val(value);
            row.find('.weekly-hours-value').text(value);

            updateBookingSummary();
        });
 
        //time-picker event
        $(document).on('click', '.weekly-time-picker', function(e) {
            e.stopPropagation();

            var row = $(this).closest('.weekly-schedule-row');
            var dropdown = $(this).siblings('.weekly-time-dropdown');

            $('.weekly-schedule-row').removeClass('time-open');
            $('.weekly-time-dropdown').not(dropdown).hide();

            if (dropdown.css('display') === 'grid') {
                dropdown.hide();
                row.removeClass('time-open');
            } else {
                row.addClass('time-open');
                dropdown.css('display', 'grid');
            }
        });
        //end time-picker event


        $(document).on('click', '.more-often-time-slot', function(e) {
            e.stopPropagation();

            var selectedTime = $(this).data('time');
            var field = $(this).closest('.weekly-time-field');

            field.find('.weekly-selected-time').text(selectedTime);
            field.find('.weekly-time-input').val(selectedTime);

            field.find('.more-often-time-slot').removeClass('active');
            $(this).addClass('active');

            field.find('.weekly-time-dropdown').hide();

            updateBookingSummary();
        });

        $(document).on('change', '.weekly-day-select', function() {
            updateBookingSummary();
        });

        $(document).on('click', function() {
            $('.weekly-time-dropdown').hide();
            $('.weekly-schedule-row').removeClass('time-open');
        });
        /* End Newly Added: More Often weekly schedule events */


        renderWeeklyScheduleRows();
        toggleMoreOftenSection();
        updateBookingSummary();

        // Collect extra services for AJAX email
        function collectExtraServices() {
            var extras = [];

            $('.cleaning-extra-services-list li').each(function () {
                var text = $(this).text().trim();

                if (text && text !== 'Keine Zusatzleistungen ausgewählt') {
                    extras.push(text);
                }
            });

            return extras.join("\n");
        }

        // Disable step 2 inputs initially
        $('#step-2 input').prop('disabled', true);

        // Step control
        $('.stfr-step-btn').on('click', function(e) {

            e.preventDefault();

            var button = $(this);

            // STEP 1 → STEP 2
            if ($('#step-1').is(':visible')) {

                var bookingDate = $('.booking-date-input').val();

                if (!bookingDate) {
                    $('.booking-date-error')
                        .text('Please select a booking date.')
                        .fadeIn(200);

                    $('.booking-date-input').focus();

                    return;
                }

                $('.booking-date-error').hide();

                $('#step-1').fadeOut(300, function() {

                    $('#step-2 input').prop('disabled', false);
                    $('#step-2').fadeIn(300);

                    $('.stauffer-form-steps > span').removeClass('active');
                    $('.stauffer-form-steps .step-2').addClass('active');

                    button.text(stauffer_ajax.button_step_2_text);

                });

                return;
            }

            // STEP 2 → AJAX SUBMIT
            var form = $('.stauffer-booking-form')[0];

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            var formData = {
                action: 'stauffer_submit_booking',
                nonce: stauffer_ajax.nonce,

                first_name: $('input[name="first_name"]').val(),
                last_name: $('input[name="last_name"]').val(),
                phone_number: $('input[name="phone_number"]').val(),
                email: $('input[name="email"]').val(),
                home_address: $('input[name="home_address"]').val(),

                frequency: $('.summary-frequency').text().trim(),
                extras: collectExtraServices(),
                hours: $('.cleaning-hours-value').text().trim(),
                date_time: $('.cleaning-service-booking-date-time').text().trim(),
                // total: $('.total-amount').text().trim()
                total: $('.total-amount').text().trim(),
                weekly_schedule: collectWeeklyScheduleSummary().join("\n")
            };

            button.prop('disabled', true).text('Submitting...');

            $.ajax({
                url: stauffer_ajax.ajax_url,
                type: 'POST',
                data: formData,

                success: function(response) {

                    if (response.success) {

                        $('#step-1, #step-2, .form-summery, .stauffer-form-steps').fadeOut(300, function() {
                            $('.form-booking-confirmation').fadeIn(300);
                        });

                        $('.stauffer-form-steps > span').removeClass('active');
                        $('.stauffer-form-steps .step-3').addClass('active');

                        button.hide();

                    } else {

                        alert(response.data);
                        button.prop('disabled', false).text(stauffer_ajax.button_step_2_text);

                    }
                },

                error: function() {

                    alert('Something went wrong. Please try again.');
                    button.prop('disabled', false).text(stauffer_ajax.button_step_2_text);

                }
            });

        });

        // Step 1 navigation click
        $('.stauffer-form-steps .step-1').on('click', function(e) {

            e.preventDefault();

            if ($('#step-2').is(':visible')) {

                $('#step-2').fadeOut(300, function() {

                    $('#step-2 input').prop('disabled', true);
                    $('#step-1').fadeIn(300);

                    $('.stauffer-form-steps > span').removeClass('active');
                    $('.stauffer-form-steps .step-1').addClass('active');

                    $('.stfr-step-btn')
                        .prop('disabled', false)
                        .text('Fill Personal Information');

                });

            }

        });

    });

})(jQuery);