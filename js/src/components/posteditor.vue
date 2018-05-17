<template>
    <div>
        <div class="postToolbar">
            <button title="Bold" @click.prevent="add('[b]', '[/b]');">
                <i class="fa fa-bold"></i>
            </button>
            <button title="Italic" @click.prevent="add('[i]', '[/i]');">
                <i class="fa fa-italic"></i>
            </button>
            <button title="Underlined" @click.prevent="add('[u]', '[/u]');">
                <i class="fa fa-underline"></i>
            </button>
            <button title="Strikethrough" @click.prevent="add('[s]', '[/s]');">
                <i class="fa fa-strikethrough"></i>
            </button>
            <button title="Superscript" @click.prevent="add('<sup>', '</sup>');">
                <i class="fa fa-superscript"></i>
            </button>
            <button title="Subscript" @click.prevent="add('<sub>', '</sub>');">
                <i class="fa fa-subscript"></i>
            </button>
            <button title="Link" @click.prevent="add('[url=http://whatever/]', '[/url]');">
                <i class="fa fa-link"></i>
            </button>
            <button title="Image" @click.prevent="add('[img]', '[/img]');">
                <i class="fa fa-picture-o"></i>
            </button>
            <button title="Quote" @click.prevent="add('[quote=Someone]', '[/quote]');">
                <i class="fa fa-quote-left"></i>
            </button>
            <button title="Spoiler" @click.prevent="add('[spoiler]', '[/spoiler]');">
                <i class="fa fa-ellipsis-h"></i>
            </button>
            <button title="Spoiler" @click.prevent="add(':)');">
                :)
            </button>
            <upload multiple @upload="file($event)" title="Attach files">
                <button>
                    <i class="fa fa-paperclip"></i>
                </button>
            </upload>
        </div>

        <div ref="textHold">
            <textarea
                ref="text"
                id="text"
                rows="5"
                style="width: 100%; box-sizing: border-box; resize: none; overflow-y: hidden;"
                v-model="value"
            ></textarea>
        </div>
    </div>
</template>

<script>
import api from '../api';
import upload from './upload';

export default {
    components: {
        upload,
    },
    data() {
        return {
        };
    },
    props: {
        value: String,
    },
    created() {
        this.$watch('value', () => {
            this.resize();
            this.$emit('input', this.value);
        });
        setTimeout(() => {
            this.resize();
        }, 0);

        window.quote = (pid) => {
            api('/getquote', { pid }).then((stuff) => {
                this.add(stuff);
            });
        };
    },
    methods: {
        file(file) {
            if(file.file.type.indexOf('image') > -1) {
                this.add(`[imgs]${file.url}[/imgs]\n`);
            } else {
                this.add(`[url=${file.url}]${file.file.name}[/url]\n`);
            }
        },
        resize() {
            this.$refs.textHold.style.height = this.$refs.text.style.height;
            this.$refs.text.style.height = 'auto';
            this.$refs.text.style.height = `${this.$refs.text.scrollHeight}px`;
            this.$refs.textHold.style.height = '';
        },
        add(before, after2) {
            let after = after2;
            if(after2 === undefined) after = '';

            const textEditor = this.$refs.text;

            let oldSelS = textEditor.selectionStart;
            const oldSelE = textEditor.selectionEnd;
            if(after === '') oldSelS = oldSelE;
            const scroll = textEditor.scrollTop;

            const selectedText = this.value.substr(oldSelS, oldSelE - oldSelS);
            this.value =
                this.value.substr(0, oldSelS) +
                before + selectedText + after +
                this.value.substr(oldSelE);

            setTimeout(() => {
                textEditor.selectionStart = oldSelS + before.length;
                textEditor.selectionEnd = oldSelS + before.length + selectedText.length;
                textEditor.scrollTop = scroll;
                textEditor.focus();
            }, 1);
        },
    },
};
</script>
