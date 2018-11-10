/*global jQuery*/

var THEME_VERSION = '1.3.1';

jQuery(function ($) {
    var position;

    //Check if the local storage has the information that the fullscreen button was pressed
    if (CacheLibrary.getLocalStorageItem('gcrud_fullscreen') === 'true') {
        $('.gc-full-width').find('i.fa:first').toggleClass('fa-expand');
        $('.gc-full-width').find('i.fa:first').toggleClass('fa-compress');
        $('.gc-container').addClass('container-full no-transition');

        //Get enough time so the transition will not be triggered
        setTimeout(function (){
            $('.gc-container').removeClass('no-transition');
        }, 400);
    }

    $('.gc-full-width').click(function () {

        $(this).find('i.fa:first').toggleClass('fa-expand');
        $(this).find('i.fa:first').toggleClass('fa-compress');

        if ($(this).closest('.gc-container').hasClass('container-full')) {
            $(this).closest('.gc-container').removeClass('container-full');
            var scroll_top = $(this).closest('.gc-container').offset().top - 10;
            $('html,body').animate({scrollTop: scroll_top}, 750);

            CacheLibrary.setLocalStorageItem('gcrud_fullscreen', 'false');

            return true;
        }

        position = $(this).closest('.gc-container').offset();

        $(this).closest('.gc-container')
            .css('left', position.left + 'px')
            .css('top', position.top + 'px')
            .addClass('container-before-resize');

        $('html,body').animate({scrollTop: '0'}, 750);

        $(this).closest('.gc-container').removeClass('container-before-resize')
            .removeAttr('style')
            .addClass('container-full');

        CacheLibrary.setLocalStorageItem('gcrud_fullscreen', 'true');

    });

    $('.minimize-maximize').click(function () {
        $(this).find('i').toggleClass('fa-caret-down');
        $(this).find('i').toggleClass('fa-caret-up');

        $(this).closest('.gc-container').find('.table-container:first').slideToggle('slow');
    });

    $('.gc-full-width').hover(
        function () {
            $(this).find('i.fa:first').addClass('fa-lg');
        },
        function () {
            $(this).find('i.fa:first').removeClass('fa-lg');
        }
    );
});