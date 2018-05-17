<template>
    <div>
        <table class="outline margin width100">
            <slot></slot>
            <tr class="cell0">
                <td colspan="2">
                    <posteditor v-model="draft.text"/>
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
import posteditor from './posteditor';

export default {
    components: {
        posteditor,
    },
    data() {
        return {
            previewHTML: '',
            dirty: false,
            saving: false,
            saved: false,
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
    },
    methods: {
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
    },
};
</script>
