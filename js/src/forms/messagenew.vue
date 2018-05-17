<template>
    <div>
        <postbox
            postButtonText="Send message"
            @submit="submit()"
            :draft="draft"
            :draftType="draftType"
            :draftTarget="draftTarget"
            >
            <tr class="header1">
                <th colspan="2">
                    New private message
                </th>
            </tr>
            <tr class="cell1">
                <td>
                    Title
                </td>
                <td>
                    <input type="text" v-model="draft.title">
                </td>
            </tr>
            <tr class="cell0">
                <td>
                    Recipients
                </td>
                <td>
                    <div v-for="(r, index) in draft.recipients">
                        {{ index+1 }}.
                        <input type="text" v-model="draft.recipients[index]"/>
                        <button v-if="draft.recipients.length > 1" @click="draft.recipients.splice(index, 1)">Delete</button>
                    </div>
                    <button @click="draft.recipients.push(0)">Add recipient</button>
                </td>
            </tr>

            <tr class="header1" style="height: 5px">
                <th colspan="2">
                </th>
            </tr>
        </postbox>
    </div>
</template>

<script>
import Vue from 'vue';
import api from '../api';
import postbox from '../components/postbox';

export default {
    components: {
        postbox,
    },
    props: {
        draftType: Number,
        draftTarget: Number,
        draft: Object,
    },
    created() {
        const defaults = {
            text: '',
            recipients: ['a', 'b', 'c'],
        };
        for(const k of Object.keys(defaults)) {
            if(!(k in this.draft)) {
                Vue.set(this.draft, k, defaults[k]);
            }
        }
    },
    data() {
        return {
        };
    },
    methods: {
        submit() {
            api('/messagenew', {
                fid: this.draftTarget,
                ...this.draft,
            });
        },
    },
};
</script>
