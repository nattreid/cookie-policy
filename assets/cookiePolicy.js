;(function ($, window) {
    if (window.jQuery === undefined) {
        console.error('Plugin "jQuery" required by "cookiePolicy.js" is missing!');
        return;
    }

    $(document).ready(function () {
        $(document).on('click', '.nattreid-cookiePolicy .nattreid-cookiePolicy-modal .nc-modal-background, .nattreid-cookiePolicy .nattreid-cookiePolicy-modal .nc-modal-wrapper .nc-modal-close', function () {
            $(this).closest('.nattreid-cookiePolicy-modal').fadeOut();
        });

        $(document).on('click', '.nattreid-cookiePolicy .nattreid-cookiePolicy-modal .nc-modal-wrapper ul li a', function () {
            var obj = $(this);
            obj.closest('ul').find('li').removeClass('active');
            var name = obj.parent().attr('class');

            obj.parent().addClass('active');

            var content = obj.closest('.nc-modal-wrapper').find('.nc-modal-content');
            content.find('> div').removeClass('active');

            content.find('.' + name).addClass('active');
            return false;
        });

        $(document).on('click', '.nattreid-cookiePolicy .nattreid-cookiePolicy-modal .nc-modal-wrapper .checkbox', function () {
            var obj = $(this);

            var container = $('.nattreid-cookiePolicy');
            var active = container.data('active');
            var inactive = container.data('inactive');

            var bool = !obj.data('active') ? 1 : 0;
            obj.data('active', bool);
            obj.attr('data-active', bool);

            obj.find('p').text(bool ? active : inactive);

            return false;
        });

        $(document).on('click', '.nattreid-cookiePolicy .nattreid-cookiePolicy-modal .nc-modal-wrapper .buttons .allowAll', function () {
            var obj = $(this);
            $.nette.ajax({
                url: obj.data('href'),
                complete: function () {
                    obj.closest('.nattreid-cookiePolicy').find('.nattreid-cookiePolicy-modal').fadeOut();
                }
            });
            return false;
        });

        $(document).on('click', '.nattreid-cookiePolicy .nattreid-cookiePolicy-modal .nc-modal-wrapper .buttons .save', function () {
            var button = $(this);

            var data = {};
            button.closest('.nc-modal-wrapper').find('.checkbox').each(function () {
                var obj = $(this);
                data[obj.data('type')] = obj.data('active');
            });

            $.nette.ajax({
                url: button.data('href').replace('replaceQuery', JSON.stringify(data)),
                complete: function () {
                    button.closest('.nattreid-cookiePolicy-modal').fadeOut();
                }
            });

            return false;
        });

        $(document).on('click', '.nattreid-cookiePolicy .nattreid-cookiePolicy-docker .nc-docker-head', function () {
            var obj = $(this).parent();
            obj.toggleClass('hover');
            obj.find('.nc-docker-content').slideToggle();
        });
    });

})(jQuery, window);