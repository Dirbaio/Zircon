<template>
    <div>
        <postbox
            postButtonText="Post reply"
            @submit="submit()"
            :draft="draft"
            :draftType="draftType"
            :draftTarget="draftTarget"
            >
            <tr class="header1">
                <th colspan="2">
                    Post reply
                </th>
            </tr>
        </postbox>
    </div>
</template>

<script>
import Vue from 'vue';
import api from '../api';
import postbox from './postbox';

export default {
    components: {
        postbox,
    },
    props: {
        draftType: Number,
        draftTarget: Number,
        draft: Object,
    },
    data() {
        return {
        };
    },
    created() {
        const defaults = {
            text: '',
        };
        for(const k of Object.keys(defaults)) {
            if(!(k in this.draft)) {
                Vue.set(this.draft, k, defaults[k]);
            }
        }
    },
    methods: {
        submit() {
            api('/messagereply', {
                tid: this.draftTarget,
                ...this.draft,
            });
        },
    },
};
</script>
