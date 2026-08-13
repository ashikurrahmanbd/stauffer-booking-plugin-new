(function($){

    $(document).ready(function(){


        function activateTab(tabId) {
            if (!tabId || !$(tabId).length) {
                tabId = '#step-navigation';
            }

            $('.stauffer-booking-tabs .nav-tab').removeClass('nav-tab-active');
            $('.stauffer-booking-tabs .nav-tab[href="' + tabId + '"]').addClass('nav-tab-active');

            $('.stauffer-tab-content').removeClass('active');
            $(tabId).addClass('active');
        }

        var currentHash = window.location.hash;

        activateTab(currentHash);

        $('.stauffer-booking-tabs .nav-tab').on('click', function(e) {
            e.preventDefault();

            var target = $(this).attr('href');

            window.location.hash = target;

            activateTab(target);
        });
        //end code for option page top tab


    });

})(jQuery);