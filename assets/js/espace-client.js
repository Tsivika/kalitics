
const $ = require('jquery');
global.$ = global.jQuery = $;

import '../css/espace-client.css';
import './jquery.min';
import './bootstrap.bundle';
import './meeting';
import './subscription';
import './coupon';
import './guide';
import './profil';
import './backOffice';

import tinymce from "tinymce";
import 'tinymce/themes/silver/theme';
import 'tinymce/plugins/paste';
import 'tinymce/plugins/link';

tinymce.init({
    selector: "textarea",
    branding: false,
    language : "fr_FR",
    height: 300,
    width: 660,
    plugins: 'link',
    toolbar1: "bold italic underline | alignleft aligncenter alignright alignjustify",
    toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor",

    menubar: false,
    toolbar_items_size: 'small',

    style_formats: [{
        title: 'Bold text',
        inline: 'b'
    }, {
        title: 'Red text',
        inline: 'span',
        styles: {
            color: '#ff0000'
        }
    }, {
        title: 'Red header',
        block: 'h1',
        styles: {
            color: '#ff0000'
        }
    }, {
        title: 'Example 1',
        inline: 'span',
        classes: 'example1'
    }, {
        title: 'Example 2',
        inline: 'span',
        classes: 'example2'
    }, {
        title: 'Table styles'
    }, {
        title: 'Table row 1',
        selector: 'tr',
        classes: 'tablerow1'
    }],

    templates: [{
        title: 'Test template 1',
        content: 'Test 1'
    }, {
        title: 'Test template 2',
        content: 'Test 2'
    }],
    content_css: [
        '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
        '//www.tinymce.com/css/codepen.min.css'
    ]
});
