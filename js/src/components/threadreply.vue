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
    methods: {
        submit() {
            api('/threadreply', {
                tid: this.draftTarget,
                ...this.draft,
            });
        },
    },
};
</script>
