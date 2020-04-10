// The editor creator to use.
import ClassicEditorBase from '@ckeditor/ckeditor5-build-classic';
import CKFinder from '@ckeditor/ckeditor5-ckfinder/src/ckfinder';

import Essentials from '@ckeditor/ckeditor5-essentials/src/essentials';
import UploadAdapter from '@ckeditor/ckeditor5-adapter-ckfinder/src/uploadadapter';
import AutoFormat from '@ckeditor/ckeditor5-autoformat/src/autoformat';
import Bold from '@ckeditor/ckeditor5-basic-styles/src/bold';
import Italic from '@ckeditor/ckeditor5-basic-styles/src/italic';
import BlockQuote from '@ckeditor/ckeditor5-block-quote/src/blockquote';
import Heading from '@ckeditor/ckeditor5-heading/src/heading';
import Image from '@ckeditor/ckeditor5-image/src/image';
import ImageCaption from '@ckeditor/ckeditor5-image/src/imagecaption';
import ImageStyle from '@ckeditor/ckeditor5-image/src/imagestyle';
import ImageToolbar from '@ckeditor/ckeditor5-image/src/imagetoolbar';
import ImageUpload from '@ckeditor/ckeditor5-image/src/imageupload';
import Link from '@ckeditor/ckeditor5-link/src/link';
import List from '@ckeditor/ckeditor5-list/src/list';
import Paragraph from '@ckeditor/ckeditor5-paragraph/src/paragraph';

import Alignment from '@ckeditor/ckeditor5-alignment/src/alignment';
import WordCount from '@ckeditor/ckeditor5-word-count/src/wordcount';

export default class ClassicEditor extends ClassicEditorBase {
}

// Plugins to include in the build.
ClassicEditor.builtinPlugins = [
    Essentials,
    UploadAdapter,
    AutoFormat,
    Bold,
    Italic,
    BlockQuote,
    Heading,
    Image,
    ImageCaption,
    ImageStyle,
    ImageToolbar,
    ImageUpload,
    Link,
    List,
    Paragraph,
    Alignment,
    CKFinder,
    WordCount
];

// Editor configuration.
ClassicEditor.defaultConfig = {
    toolbar: {
        items: [
            'imageUpload',
            'heading',
            '|',
            'alignment',
            'bold',
            'italic',
            'link',
            'bulletedList',
            'numberedList',
            'blockQuote',
            'undo',
            'redo',
            'ckfinder'
        ]
    },
    image: {
        toolbar: [
            'imageStyle:full',
            'imageStyle:side',
            '|',
            'imageTextAlternative'
        ]
    },
    // This value must be kept in sync with the language defined in webpack.config.js.
    language: 'de'
};


ClassicEditor
    .create(document.querySelector('#editor'), {

        ckfinder: {
            // Upload the images to the server using the CKFinder QuickUpload command.
            uploadUrl: 'http://alt.localhost/admin2/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images&responseType=json',
            options: {
                resourceType: 'Images'
            }
        }

    })
    .then(editor => {
        const wordCountPlugin = editor.plugins.get('WordCount');
        const wordCountWrapper = document.getElementById('word-count');

        wordCountWrapper.appendChild(wordCountPlugin.wordCountContainer);
    })
    .catch(error => {
        console.error(error.stack);
    });