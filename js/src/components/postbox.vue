<template>
    <div>
        <table class="outline margin width100">
            <slot></slot>
            <tr class="cell0">
                <td colspan="2">
                    <div class="postToolbar">
                        <button title="Bold" @click="add('[b]', '[/b]');">
                            <i class="fa fa-bold"></i>
                        </button>
                        <button title="Italic" @click="add('[i]', '[/i]');">
                            <i class="fa fa-italic"></i>
                        </button>
                        <button title="Underlined" @click="add('[u]', '[/u]');">
                            <i class="fa fa-underline"></i>
                        </button>
                        <button title="Strikethrough" @click="add('[s]', '[/s]');">
                            <i class="fa fa-strikethrough"></i>
                        </button>
                        <button title="Superscript" @click="add('<sup>', '</sup>');">
                            <i class="fa fa-superscript"></i>
                        </button>
                        <button title="Subscript" @click="add('<sub>', '</sub>');">
                            <i class="fa fa-subscript"></i>
                        </button>
                        <button title="Link" @click="add('[url=http://whatever/]', '[/url]');">
                            <i class="fa fa-link"></i>
                        </button>
                        <button title="Image" @click="add('[img]', '[/img]');">
                            <i class="fa fa-picture-o"></i>
                        </button>
                        <button title="Quote" @click="add('[quote=Someone]', '[/quote]');">
                            <i class="fa fa-quote-left"></i>
                        </button>
                        <button title="Spoiler" @click="add('[spoiler]', '[/spoiler]');">
                            <i class="fa fa-ellipsis-h"></i>
                        </button>
                        <button title="Spoiler" @click="add(':)');">
                            :)
                        </button>
                        <span title="Attach file" ng-file-select="onFileSelect($files)" v-if="!uploading">
                            <button>
                                <i class="fa fa-paperclip"></i>
                            </button>
                        </span>
                        <div class="pollbarContainer" style="display: inline-block; width:200px;" v-if="uploading">
                            <div class="pollbar" :style="`width: ${uploadProgress}%;`">
                                {{ uploadProgress }}%
                            </div>
                        </div>
                    </div>

                    <div ref="textHold">
                        <textarea
                            ref="text"
                            id="text"
                            rows="5"
                            style="width: 100%; box-sizing: border-box; resize: none; overflow-y: hidden;"
                            v-model="draft.text"
                        ></textarea>
                    </div>
                </td>
            </tr>
            <tr class="cell2">
                <td colspan="2">
                    <button @click="submit()">{{ postButtonText }}</button>

                    <button @click="preview()">Preview</button>

                    <button v-if="dirty" @click="save()">Save</button>
                    <span v-if="saving">Saving...</span>
                    <span v-if="!dirty && saved">Saved!</span>
                </td>
            </tr>
        </table>

        <div v-html="previewHTML">
        </div>
    </div>
</template>

<script>
import api from '../api';

export default {
    data() {
        return {
            uploadProgress: 0,
            previewHTML: '',
            dirty: false,
            saving: false,
            saved: false,
            uploading: false,
        };
    },
    props: {
        draftType: Number,
        draftTarget: Number,
        draft: Object,
        postButtonText: String,
    },
    created() {
        // Just by instantiating one component
        // we will get draft autosaving in the entire page. Nice, hm?
        document.onclick = (ev) => {
            const event = ev || window.event; // IE specials
            const target = event.target || event.srcElement; // IE specials

            if(target.nodeName !== 'A') return true;
            if(target.onclick) return true;
            if(!this.dirty) return true;

            if(this.dirty) {
                this.save().then(() => {
                    window.location = target.href;
                });
                return false;
            }
            return true;
        };

        this.$watch('draft', () => {
            if(!this.dirty) {
                setTimeout(() => {
                    this.save();
                }, 5000);
            }
            this.dirty = true;
        }, { deep: true });


        this.$watch('draft.text', () => {
            this.resize();
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
        resize() {
            this.$refs.textHold.style.height = this.$refs.text.style.height;
            this.$refs.text.style.height = 'auto';
            console.log(this.$refs.text.scrollHeight);
            this.$refs.text.style.height = `${this.$refs.text.scrollHeight}px`;
            this.$refs.textHold.style.height = '';
        },
        preview() {
            api('/preview', {
                text: this.draft.text,
            }).then((res) => {
                this.previewHTML = res;
            });
        },
        submit() {
            this.$emit('submit');
        },
        save() {
            if(!this.dirty) return undefined;
            this.saving = true;
            return api('/savedraft', {
                type: this.draftType,
                target: this.draftTarget,
                data: this.draft,
            }).then(() => {
                this.saving = false;
                this.dirty = false;
                this.saved = true;
            });
        },
        add(before, after2) {
            let after = after2;
            if(after2 === undefined) after = '';

            const textEditor = this.$refs.text;

            let oldSelS = textEditor.selectionStart;
            const oldSelE = textEditor.selectionEnd;
            if(after === '') oldSelS = oldSelE;
            const scroll = textEditor.scrollTop;

            const selectedText = this.draft.text.substr(oldSelS, oldSelE - oldSelS);
            this.draft.text =
                this.draft.text.substr(0, oldSelS) +
                before + selectedText + after +
                this.draft.text.substr(oldSelE);

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
