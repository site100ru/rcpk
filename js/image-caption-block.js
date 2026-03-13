(function(blocks, element, editor, components, i18n) {
    var el = element.createElement;
    var MediaUpload = editor.MediaUpload;
    var InspectorControls = editor.InspectorControls;
    var TextControl = components.TextControl;
    var SelectControl = components.SelectControl;
    var PanelBody = components.PanelBody;
    var Button = components.Button;
    var __ = i18n.__;

    blocks.registerBlockType('mytheme/image-caption', {
        title: __('Изображение с подписью', 'mytheme'),
        icon: 'format-image',
        category: 'media',
        attributes: {
            imageUrl: {
                type: 'string',
                default: ''
            },
            imageId: {
                type: 'number',
                default: 0
            },
            caption: {
                type: 'string',
                default: 'Краткое описание'
            },
            alignment: {
                type: 'string',
                default: 'right'
            }
        },

        edit: function(props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            function onSelectImage(media) {
                setAttributes({
                    imageUrl: media.url,
                    imageId: media.id
                });
            }

            function onRemoveImage() {
                setAttributes({
                    imageUrl: '',
                    imageId: 0
                });
            }

            return [
                // Боковая панель настроек
                el(InspectorControls, {},
                    el(PanelBody, { title: __('Настройки изображения', 'mytheme') },
                        el(TextControl, {
                            label: __('Подпись к изображению', 'mytheme'),
                            value: attributes.caption,
                            onChange: function(value) {
                                setAttributes({ caption: value });
                            }
                        }),
                        el(SelectControl, {
                            label: __('Выравнивание', 'mytheme'),
                            value: attributes.alignment,
                            options: [
                                { label: __('Справа', 'mytheme'), value: 'right' },
                                { label: __('Слева', 'mytheme'), value: 'left' }
                            ],
                            onChange: function(value) {
                                setAttributes({ alignment: value });
                            }
                        })
                    )
                ),

                // Содержимое блока в редакторе
                el('div', { className: 'image-caption-block-editor', style: { border: '1px solid #ddd', padding: '20px', marginBottom: '20px' } },
                    !attributes.imageUrl ?
                        // Кнопка загрузки изображения
                        el(MediaUpload, {
                            onSelect: onSelectImage,
                            allowedTypes: ['image'],
                            render: function(obj) {
                                return el(Button, {
                                    onClick: obj.open,
                                    isPrimary: true
                                }, __('Загрузить изображение', 'mytheme'));
                            }
                        })
                    :
                        // Превью изображения
                        el('div', {},
                            el('img', {
                                src: attributes.imageUrl,
                                alt: attributes.caption,
                                style: { maxWidth: '100%', height: 'auto' }
                            }),
                            el('p', { style: { marginTop: '10px', fontWeight: 'bold' } }, attributes.caption),
                            el(Button, {
                                onClick: onRemoveImage,
                                isDestructive: true,
                                style: { marginTop: '10px' }
                            }, __('Удалить изображение', 'mytheme'))
                        )
                )
            ];
        },

        save: function() {
            // Возвращаем null, так как используем PHP render_callback
            return null;
        }
    });

})(
    window.wp.blocks,
    window.wp.element,
    window.wp.blockEditor || window.wp.editor,
    window.wp.components,
    window.wp.i18n
);