tinymce.PluginManager.add('fixedtoolbar', function (editor, url) {

    function getOffset(el) {
        var _y = 0;
        while (el && !isNaN(el.offsetTop)) {
            _y += el.offsetTop;
            el = el.offsetParent;
        }
        return _y;
    }

    editor.on('init', function () {
        var container = editor.editorContainer;
        var toolbar = container.querySelector('.mce-toolbar-grp');
        var area = container.querySelector('.mce-edit-area');

        toolbar.style.left = '0px';
        toolbar.style.width = '100%';
        toolbar.style.borderWidth = '0px';

        window.addEventListener('scroll', function () {
            console.log(container);
            var scrollTop = window.pageYOffset;
            var position = getOffset(container);

            if (scrollTop > position && scrollTop < (position + container.clientHeight - 100 )) {
                toolbar.style.boxShadow = '0 2px 3px -1px rgba(0,0,0,0.2)';
                toolbar.style.position = 'absolute';
                toolbar.style.top = (scrollTop - position - 1 ) + 'px';
                area.style.paddingTop = toolbar.clientHeight + 'px';
				//$('#fine-uploader-gallery').css({'position':'fixed', 'bottom':'-1px'});
				
            } else {
                toolbar.style.boxShadow = 'none';
                toolbar.style.position = 'static';
                area.style.paddingTop = '0px';
				//$('#fine-uploader-gallery').css({'position':'relative', 'bottom':'0'});
            }
			//ПРИЖИМАЕМ ЗАГРУЗЧИК КАРТИНОК К НИЗУ СТРАНИЦЫ
			if ((scrollTop + Math.max($(window).height())-280 ) > position && (scrollTop) < (position + container.clientHeight - Math.max($(window).height())+80 )) {
				//$('#fine-uploader-gallery').css({'position':toolbar.style.position, 'top':(scrollTop - position - 1 ) + 'px'});
				$('#fine-uploader-gallery-wrap').css({'position':'fixed', 'bottom':'0'});
				$('#fine-uploader-gallery-wrap .slide').show();
			} else {
				$('#fine-uploader-gallery-wrap').css({'position':'relative', 'bottom':'0'});
				$('#fine-uploader-gallery-wrap .slide').hide();
				$("#fine-uploader-gallery").slideDown(0);
			}
        });
    });
});