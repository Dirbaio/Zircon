<template>
    <div>
        <postbox
            postButtonText="Post thread"
            @submit="submit()"
            :draft="draft"
            :draftType="draftType"
            :draftTarget="draftTarget"
            >
            <tr class="header1">
                <th colspan="2">
                    New thread
                </th>
            </tr>
            <tr class="cell1">
                <td style="width: 150px">
                    Title
                </td>
                <td>
                    <input type="text" v-model="draft.title">
                </td>
            </tr>

            <tr class="header1" style="height: 5px">
                <th colspan="2">
                </th>
            </tr>

            <tr class="cell0">
                <td style="width: 150px">
                </td>
                <td>
                    <label>
                        <input type="checkbox" v-model="draft.poll">
                        Poll
                    </label>
                </td>
            </tr>
            <tr class="cell1" v-if="draft.poll" >
                <td style="width: 150px">
                    Poll question
                </td>
                <td>
                    <input type="text" v-model="draft.pollquestion">
                </td>
            </tr>
            <tr class="cell0" v-if="draft.poll">
                <td style="width: 150px">
                    Poll choices
                </td>
                <td>
                    <div v-for="(choice, index) in draft.pollchoices">
                        {{ index+1 }}.
                        <input type="text" v-model="choice.text">
                        Color:
                        <input type="color" v-model="choice.color">
                        {{ choice.color }}
                        <button v-if="draft.pollchoices.length > 2" @click="draft.pollchoices.splice(index, 1)">Delete</button>
                    </div>
                    <button @click="draft.pollchoices.push({text:''})">Add choice</button>
                </td>
            </tr>
            <tr class="cell1" v-if="draft.poll">
                <td style="width: 150px">
                </td>
                <td>
                    <label>
                        <input type="checkbox" v-model="draft.polldoublevote">
                        Multivote
                    </label>
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
            title: '',
            poll: false,
            pollquestion: '',
            pollchoices: [],
            polldoublevote: false,
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
            api('/threadnew', {
                fid: this.draftTarget,
                ...this.draft,
            });
        },
    },
};
</script>
