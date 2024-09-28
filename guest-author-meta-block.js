( function( wp ) {
    const { registerBlockType } = wp.blocks;
    const { createElement } = wp.element;
    const { InspectorControls } = wp.blockEditor;
    const { PanelBody, RangeControl, ToggleControl } = wp.components;
    const { useSelect } = wp.data;

    // Register the block for displaying guest authors.
    registerBlockType( 'gam/guest-author-display', {
        title: 'Guest Author(s)',
        icon: 'admin-users',
        category: 'widgets',
        attributes: {
            fontSize: {
                type: 'number',
                default: 16,  // Default font size
            },
            isBold: {
                type: 'boolean',
                default: false,  // Bold formatting option
            },
            isItalic: {
                type: 'boolean',
                default: false,  // Italic formatting option
            },
            isUnderline: {
                type: 'boolean',
                default: false,  // Underline formatting option
            },
            isHidden: { 
                type: 'boolean', 
                default: false  // Whether to hide the block on frontend
            },
        },
        usesContext: ['postId'],  // Inheriting postId from context
        edit: function( props ) {
            const { attributes, setAttributes, context } = props;
            const { fontSize, isBold, isItalic, isUnderline, isHidden } = attributes;

            // Access postId from the context (inherited from parent block or Query Loop)
            const postId = context.postId;

            // Fetch guest author meta using postId
            const guestAuthor = useSelect( ( select ) => {
                const entityRecord = select( 'core' ).getEntityRecord( 'postType', 'post', postId );
                return entityRecord && entityRecord.meta ? entityRecord.meta._guest_author_name : null;
            }, [ postId ] );  // Adding postId as a dependency to refetch if it changes

            // Determine what to display based on guest author and isHidden
            let content;
            if (isHidden) {
                content = '<hidden placeholder>';  // Show hidden placeholder if the block is hidden
            } else if (!guestAuthor) {
                content = '<empty placeholder>';  // Show placeholder if guest author is not found
            } else {
                content = `By: ${guestAuthor}`;  // Show the guest author
            }

            // Create a style object for dynamic text formatting.
            const textStyle = {
                fontSize: `${fontSize}px`,
                fontWeight: isBold ? 'bold' : 'normal',
                fontStyle: isItalic ? 'italic' : 'normal',
                textDecoration: isUnderline ? 'underline' : 'none',
            };

            return createElement(
                'div',
                { className: 'wp-block-guest-author', style: textStyle },
                [
                    content,  // The guest author content or placeholder
                    createElement(
                        InspectorControls,
                        {},
                        createElement(
                            PanelBody,
                            { title: 'Text Formatting Settings', initialOpen: true },
                            // Font size range control
                            createElement(
                                RangeControl,
                                {
                                    label: 'Font Size',
                                    value: fontSize,
                                    onChange: ( newSize ) => setAttributes( { fontSize: newSize } ),
                                    min: 10,
                                    max: 50,
                                }
                            ),
                            // Bold toggle control
                            createElement(
                                ToggleControl,
                                {
                                    label: 'Bold',
                                    checked: isBold,
                                    onChange: ( newBold ) => setAttributes( { isBold: newBold } ),
                                }
                            ),
                            // Italic toggle control
                            createElement(
                                ToggleControl,
                                {
                                    label: 'Italic',
                                    checked: isItalic,
                                    onChange: ( newItalic ) => setAttributes( { isItalic: newItalic } ),
                                }
                            ),
                            // Underline toggle control
                            createElement(
                                ToggleControl,
                                {
                                    label: 'Underline',
                                    checked: isUnderline,
                                    onChange: ( newUnderline ) => setAttributes( { isUnderline: newUnderline } ),
                                }
                            ),
                            // Toggle control for visibility (isHidden)
                            createElement(
                                ToggleControl,
                                {
                                    label: 'Hide',
                                    checked: isHidden,
                                    onChange: ( newHide ) => setAttributes( { isHidden: newHide } ),
                                }
                            )
                        )
                    )
                ]
            );
        },
        save: function() {
            // Dynamic block, rendering is handled on the server-side via PHP.
            return null;
        },
    } );
} )( window.wp );
